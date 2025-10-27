<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit;
}
$userName = $_SESSION['user_name'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AI Job Matching — JobSkills</title>
  <meta name="description" content="AI Job Matching form where users can enter CV details or generate one automatically.">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: { 500: '#2b89f0', 600: '#1f6ed4' },
            accent: { 500: '#7a5cff', 600: '#6b50eb' },
            darkbg: '#0b1020',
            panel: '#111827'
          },
          backgroundImage: {
            dash: 'linear-gradient(180deg, #0b1020, #0b1225)'
          },
          boxShadow: {
            soft: '0 10px 30px rgba(2,6,23,0.12)',
          }
        }
      }
    }
  </script>
  <style>
    html { scroll-behavior: smooth; }
  #tourPopup {
    animation: fadeInScale 0.25s ease-out;
  }
  @keyframes fadeInScale {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }
   /* Typing effect */
  #typingText::after {
    content: '|';
    animation: blink 0.7s infinite;
  }
  @keyframes blink {
    0%, 50% { opacity: 1; }
    50%, 100% { opacity: 0; }
  }

  /* Ball animation on hover/click */
  .upload-box.active {
    background-color: rgba(122, 92, 255, 0.15);
    box-shadow: 0 0 20px rgba(122, 92, 255, 0.5);
    transition: all 0.5s ease-in-out;
  }
</style>

</head>

<body class="min-h-screen bg-dash text-white relative">

  <!-- Navigation -->
  <nav class="fixed top-0 w-full z-50 bg-slate-900/80 backdrop-blur border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent-500 to-brand-500 grid place-items-center">⚡</div>
        <span class="font-semibold tracking-tight">AI Job Matching</span>
      </div>
      <div class="flex gap-4">
        <a href="dashboard.php" class="text-sm px-3 py-1.5 rounded-lg border border-white/15 hover:bg-white/5">← Home</a>
        <a href="create_CV.php"><button id="tourCreateCV" class="text-sm px-3 py-1.5 rounded-lg bg-accent-600 hover:bg-accent-500 transition font-semibold focus:ring-4 focus:ring-accent-500/30">
          Create CV
        </button></a>
      </div>
    </div>
  </nav>

  <!-- Overlay for onboarding tour -->
  <div id="tourOverlay" class="hidden fixed inset-0 bg-black/70 z-40"></div>

  <!-- Tooltip popup -->
  <div id="tourPopup" class="hidden fixed z-50 bg-white text-gray-900 rounded-xl shadow-lg p-5 w-80">
    <p class="text-sm mb-4">Please feel free to create your own CV if you don't already have one.</p>
    <div class="flex justify-between">
      <button id="prevStep" class="px-3 py-1 text-sm rounded-lg bg-gray-200 hover:bg-gray-300">Previous</button>
      <button id="nextStep" class="px-3 py-1 text-sm rounded-lg bg-brand-600 text-white hover:bg-brand-500">Next</button>
      <button id="skipTour" class="px-3 py-1 text-sm rounded-lg bg-gray-200 hover:bg-gray-300">Skip</button>
    </div>
  </div>

  <!-- Form Section -->
  <section class="pt-24 pb-10 sm:py-14">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Typing Effect Section -->
<div class="text-center mb-10 relative">
  <h1 id="typingText" class="text-2xl sm:text-3xl font-bold mb-3 text-white"></h1>
  <p class="text-white/70">
    Please follow the simple steps below to get started.<br>
    You can also create your own CV — just click the button above.
  </p>
</div>


<!-- Upload Boxes Grid -->
<div class="grid sm:grid-cols-2 gap-8 relative pb-10">
  <!-- Rolling Ball -->
  <div id="rollingBall" class="absolute w-6 h-6 rounded-full bg-accent-500 shadow-lg shadow-accent-500/50 transition-all duration-700 ease-in-out top-0 left-0 z-10"></div>

  <!-- Upload CV -->
  <div id="box-cv" class="upload-box bg-white/5 border border-white/10 rounded-2xl p-6 text-center cursor-pointer hover:bg-white/10 transition relative">
    <h2 class="text-lg font-semibold text-accent-500 mb-2">Upload Your CV</h2>
    <input type="file" id="uploadCV" class="hidden" accept=".pdf,.doc,.docx">
    <p class="text-sm text-white/70">Click anywhere to upload CV</p>
  </div>

  <!-- Upload Certificate -->
  <div id="box-cert" class="upload-box bg-white/5 border border-white/10 rounded-2xl p-6 text-center cursor-pointer hover:bg-white/10 transition relative">
    <h2 class="text-lg font-semibold text-accent-500 mb-2">Upload Certificate</h2>
    <input type="file" id="uploadCert" class="hidden" accept=".pdf,.jpg,.png">
    <p class="text-sm text-white/70">Click anywhere to upload Certificate</p>
  </div>

  <!-- Upload Passport Photo -->
  <div id="box-pass" class="upload-box bg-white/5 border border-white/10 rounded-2xl p-6 text-center cursor-pointer hover:bg-white/10 transition sm:col-span-2 max-w-sm mx-auto">
    <h2 class="text-lg font-semibold text-accent-500 mb-2">Upload Passport Photo</h2>
    <input type="file" id="uploadPass" class="hidden" accept="image/*">
    <p class="text-sm text-white/70">Click anywhere to upload Passport Photo</p>
  </div>
</div>



      <form id="cvForm" class="space-y-6 bg-white/5 p-6 rounded-2xl border border-white/10 shadow-soft">
        <h2 class="text-lg font-semibold mb-4 text-accent-500">Personal Information</h2>

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1 text-white/80">Full Name</label>
            <input type="text" id="fullName" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" required>
          </div>
          <div>
            <label class="block text-sm mb-1 text-white/80">Email</label>
            <input type="email" id="email" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" required>
          </div>
          <div>
            <label class="block text-sm mb-1 text-white/80">Phone Number</label>
            <input type="text" id="phone" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" required>
          </div>
          <div>
            <label class="block text-sm mb-1 text-white/80">Location</label>
            <input type="text" id="location" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none">
          </div>
        </div>

        <button type="submit" class="mt-6 w-full py-3 rounded-xl bg-accent-600 hover:bg-accent-500 transition font-semibold">Submit Details</button>
      </form>

      <div class="mt-8 text-center">
        <button id="generateBtn" class="px-5 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 transition font-semibold">
          Generate a CV if you don't have one
        </button>
      </div>

      <!-- CV Output -->
      <div id="cvOutput" class="mt-10 hidden bg-white text-gray-900 rounded-lg overflow-hidden shadow-xl">
        <div class="grid grid-cols-3">
          <!-- Left Panel -->
          <div class="col-span-1 bg-panel text-white p-6 space-y-6">
            <div class="flex flex-col items-center">
              <img id="cvPhoto" src="" class="w-32 h-32 rounded-full object-cover border-4 border-accent-500 hidden mb-3">
              <h2 id="cvName" class="text-xl font-bold text-center"></h2>
              <p class="text-sm text-gray-300" id="cvTitle">Software Engineer</p>
            </div>
            <div>
              <h3 class="font-semibold border-b border-gray-500 pb-1 mb-2">CONTACT</h3>
              <p id="cvEmail" class="text-sm"></p>
              <p id="cvPhone" class="text-sm"></p>
              <p id="cvLocation" class="text-sm"></p>
            </div>
            <div>
              <h3 class="font-semibold border-b border-gray-500 pb-1 mb-2">SKILLS</h3>
              <p id="cvSkills" class="text-sm whitespace-pre-line"></p>
            </div>
            <div>
              <h3 class="font-semibold border-b border-gray-500 pb-1 mb-2">ACHIEVEMENTS</h3>
              <p id="cvAchievements" class="text-sm whitespace-pre-line"></p>
            </div>
          </div>

          <!-- Right Panel -->
          <div class="col-span-2 bg-white text-gray-900 p-6">
            <h3 class="text-xl font-bold text-accent-600 mb-2">PROFILE</h3>
            <p id="cvProfile" class="text-sm mb-4">A dedicated and results-driven professional seeking to leverage my skills and experience to secure opportunities through AI job matching.</p>

            <h3 class="text-xl font-bold text-accent-600 mb-2">WORK EXPERIENCE</h3>
            <p id="cvExperience" class="text-sm whitespace-pre-line mb-4"></p>

            <h3 class="text-xl font-bold text-accent-600 mb-2">EDUCATION</h3>
            <p id="cvEducation" class="text-sm whitespace-pre-line"></p>
          </div>
        </div>
      </div>
    </div>
  </section>

 <script>
// --- Onboarding Tour Logic ---
const tourBtn = document.getElementById('tourCreateCV');
const overlay = document.getElementById('tourOverlay');
const popup = document.getElementById('tourPopup');
const skipBtn = document.getElementById('skipTour');
const nextBtn = document.getElementById('nextStep');
const prevBtn = document.getElementById('prevStep');

function positionPopup() {
  if (!tourBtn || !popup) return;
  const rect = tourBtn.getBoundingClientRect();
  const popupWidth = 320;
  const margin = 10;
  let top = rect.bottom + margin;
  let left = rect.left;
  if (left + popupWidth > window.innerWidth - margin) {
    left = window.innerWidth - popupWidth - margin;
  }
  if (top < margin) top = margin;
  popup.style.top = `${top}px`;
  popup.style.left = `${left}px`;
}

function showTour() {
  if (!overlay || !popup || !tourBtn) return;
  overlay.classList.remove('hidden');
  popup.classList.remove('hidden');
  positionPopup();
  tourBtn.classList.add('ring-4', 'ring-accent-500', 'ring-offset-2');
}

function hideTour() {
  if (!overlay || !popup || !tourBtn) return;
  overlay.classList.add('hidden');
  popup.classList.add('hidden');
  tourBtn.classList.remove('ring-4', 'ring-accent-500', 'ring-offset-2');
}

window.addEventListener('load', () => {
  setTimeout(showTour, 800);
});

[skipBtn, nextBtn, prevBtn, overlay].forEach(el => {
  if (el) el.addEventListener('click', hideTour);
});

// --- Existing CV Logic (Error-Proof) ---
const form = document.getElementById('cvForm');
const generateBtn = document.getElementById('generateBtn');
const cvOutput = document.getElementById('cvOutput');

// Optional elements (only used if they exist)
const passportInput = document.getElementById('passportPhoto');
const photoPreview = document.getElementById('photoPreview');
const cvPhoto = document.getElementById('cvPhoto');

// Safe event binding for passport upload
if (passportInput && photoPreview) {
  passportInput.addEventListener('change', () => {
    const file = passportInput.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => {
        photoPreview.src = e.target.result;
        photoPreview.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    } else {
      photoPreview.classList.add('hidden');
      photoPreview.src = '';
    }
  });
}

if (generateBtn) {
  generateBtn.addEventListener('click', () => {
    const name = document.getElementById('fullName')?.value.trim() || '';
    const email = document.getElementById('email')?.value.trim() || '';
    const phone = document.getElementById('phone')?.value.trim() || '';
    const location = document.getElementById('location')?.value.trim() || '';
    const education = document.getElementById('education')?.value.trim() || '';
    const experience = document.getElementById('experience')?.value.trim() || '';
    const skills = document.getElementById('skills')?.value.trim() || '';
    const achievements = document.getElementById('achievements')?.value.trim() || '';
    const file = passportInput ? passportInput.files[0] : null;

    if (!name || !email || !phone) {
      alert('Please fill in your basic details first.');
      return;
    }

    document.getElementById('cvName').textContent = name;
    document.getElementById('cvEmail').textContent = email;
    document.getElementById('cvPhone').textContent = phone;
    document.getElementById('cvLocation').textContent = location;
    document.getElementById('cvSkills').textContent = skills;
    document.getElementById('cvExperience').textContent = experience;
    document.getElementById('cvEducation').textContent = education;
    document.getElementById('cvAchievements').textContent = achievements;

    if (file && cvPhoto) {
      const reader = new FileReader();
      reader.onload = e => {
        cvPhoto.src = e.target.result;
        cvPhoto.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    } else if (cvPhoto) {
      cvPhoto.classList.add('hidden');
    }

    if (cvOutput) {
      cvOutput.classList.remove('hidden');
      window.scrollTo({ top: cvOutput.offsetTop, behavior: 'smooth' });
    }
  });
}

if (form) {
  form.addEventListener('submit', e => {
    e.preventDefault();
    alert('Your details have been submitted successfully!');
    form.reset();
  });
}

// --- Typing Animation (Independent) ---
const typingText = document.getElementById("typingText");
if (typingText) {
  const textToType = "Let AI do the job-hunting for you 🤖";
  let i = 0;
  function typeWriter() {
    if (i < textToType.length) {
      typingText.textContent += textToType.charAt(i);
      i++;
      setTimeout(typeWriter, 70);
    }
  }
  window.addEventListener("load", typeWriter);
}

// --- Rolling Ball Animation (Independent) ---
const ball = document.getElementById("rollingBall");
const boxes = document.querySelectorAll(".upload-box");

if (ball && boxes.length > 0) {
  function moveBallToBox(box) {
    const rect = box.getBoundingClientRect();
    const parentRect = box.parentElement.getBoundingClientRect();
    const top = rect.top - parentRect.top + rect.height / 2 - 12;
    const left = rect.left - parentRect.left + rect.width / 2 - 12;
    ball.style.top = `${top}px`;
    ball.style.left = `${left}px`;
    ball.animate(
      [
        { transform: "translateY(0px)" },
        { transform: "translateY(-20px)" },
        { transform: "translateY(0px)" }
      ],
      { duration: 600, easing: "ease-in-out" }
    );
    boxes.forEach(b => b.classList.remove("active"));
    box.classList.add("active");
  }

  // Start ball at first box safely
  setTimeout(() => {
    const firstBox = document.getElementById("box-cv");
    if (firstBox) moveBallToBox(firstBox);
  }, 1000);

  // Move ball when clicking boxes
  boxes.forEach(box => {
    box.addEventListener("click", () => moveBallToBox(box));
  });
}
// --- Make Entire Upload Boxes Clickable ---
const uploadBoxes = document.querySelectorAll('.upload-box');

uploadBoxes.forEach(box => {
  box.addEventListener('click', () => {
    const fileInput = box.querySelector('input[type="file"]');
    if (fileInput) {
      fileInput.click(); // trigger upload
    }
  });
});

</script>

</body>
</html>
