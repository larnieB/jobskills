<?php
// backend.php
// Validates email + password, inserts into MySQL (jobmatchdb.members), starts session, returns JSON.

header('Content-Type: application/json');

// ----- CONFIG -----
const DB_HOST = 'localhost';
const DB_NAME = 'jobmatchdb';
const DB_USER = 'root';
const DB_PASS = '';

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed. Use POST.']);
    exit;
}

if (
    ($_POST['action'] ?? '') === 'signin' ||
    (isset($_POST['email']) && isset($_POST['password']) && !isset($_POST['name']))
) {
    session_start();

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
        exit;
    }

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=jobmatchdb;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT id, full_name, email, password_hash FROM members WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'No account found with that email.']);
            exit;
        }

        if (!password_verify($password, $user['password_hash'])) {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
            exit;
        }

        // ✅ Login success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];

        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful!',
            'redirect' => 'dashboard.php'
        ]);
        exit;

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
}


// ---- Read JSON or form-encoded POST ----
$payload = [];
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw, true);
    if (!is_array($payload)) $payload = [];
}

$name     = trim($payload['name']     ?? $_POST['name']     ?? '');
$email    = trim($payload['email']    ?? $_POST['email']    ?? '');
$password =        $payload['password'] ?? $_POST['password'] ?? '';

$errors = [];

// ---- Email validation ----
if ($email === '') {
    $errors['email'] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Enter a valid email address.';
}

// ---- Password validation ----
if ($password === '') {
    $errors['password'] = 'Password is required.';
} else {
    $lengthOK   = (strlen($password) >= 8);
    $upperOK    = preg_match('/[A-Z]/', $password);
    $lowerOK    = preg_match('/[a-z]/', $password);
    $specialOK  = preg_match('/[^A-Za-z0-9]/', $password);
    $noSpacesOK = !preg_match('/\s/', $password);

    if (!$lengthOK)        { $errors['password'] = 'Password must be at least 8 characters long.'; }
    elseif (!$upperOK)     { $errors['password'] = 'Password must include at least 1 uppercase letter.'; }
    elseif (!$lowerOK)     { $errors['password'] = 'Password must include at least 1 lowercase letter.'; }
    elseif (!$specialOK)   { $errors['password'] = 'Password must include at least 1 special character.'; }
    elseif (!$noSpacesOK)  { $errors['password'] = 'Password cannot contain spaces.'; }
}

// Optional name validation
if ($name === '') {
    $errors['name'] = 'Full name is required.';
}

if (!empty($errors)) {
    echo json_encode(['ok' => false, 'errors' => $errors]);
    exit;
}

// ---- Database Insert ----
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Check duplicate email
    $stmt = $pdo->prepare("SELECT id FROM members WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode([
            'ok' => false,
            'errors' => ['email' => 'This email is already registered.']
        ]);
        exit;
    }

    // Hash password and insert
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO members (full_name, email, password_hash)
        VALUES (:name, :email, :hash)
    ");
    $stmt->execute([
        ':name'  => $name,
        ':email' => $email,
        ':hash'  => $hash
    ]);

    $newId = (int)$pdo->lastInsertId();

    // ---- Start session & store user ----
    session_start();
    $_SESSION['user_id']   = $newId;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;

    // ---- Respond to client ----
    echo json_encode([
        'ok' => true,
        'message' => 'Signup successful.',
        'user_id' => $newId,
        'redirect' => 'dashboard.php'
    ]);

} catch (PDOException $e) {
    if ((int)$e->getCode() === 23000) {
        echo json_encode([
            'ok' => false,
            'errors' => ['email' => 'This email is already registered.']
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}
