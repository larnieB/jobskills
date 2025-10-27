<?php
// freelance.php
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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Freelance Jobs — JobSkills</title>
  <meta name="description" content="Top legitimate remote and freelance job platforms for Africans and Kenyans." />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: { 500: '#2b89f0', 600: '#1f6ed4' },
            accent: { 500: '#7a5cff', 600: '#6b50eb' }
          },
          backgroundImage: {
            dash: 'radial-gradient(1000px 400px at 0% -10%, rgba(122,92,255,.18), transparent 40%), radial-gradient(900px 300px at 100% -20%, rgba(43,137,240,.18), transparent 40%), linear-gradient(180deg, #0b1020, #0b1225)'
          },
          boxShadow: {
            soft: '0 10px 30px rgba(2,6,23,0.12)'
          }
        }
      }
    }
  </script>
  <style>html{scroll-behavior:smooth}</style>
</head>
<body class="min-h-screen bg-dash text-white">

  <!-- Top Bar -->
  <header class="sticky top-0 z-40 backdrop-blur bg-slate-900/60 border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent-500 to-brand-500 grid place-items-center">🌍</div>
        <span class="font-semibold tracking-tight">Freelance Jobs</span>
      </div>
      <div class="flex items-center gap-3 text-sm">
        <span class="hidden sm:inline text-white/70">👋 Hi, <strong><?= htmlspecialchars($userName) ?></strong></span>
        <a href="dashboard.php" class="px-3 py-1.5 rounded-lg border border-white/15 hover:bg-white/5">Dashboard</a>
      </div>
    </div>
  </header>

  <!-- Intro -->
  <section class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h1 class="text-3xl font-bold mb-2">Top Legitimate Remote Tasks & Online Jobs</h1>
      <p class="text-white/80">
        A curated list of trusted online job platforms for Africans — especially Kenyans —
        offering real earning opportunities through remote work and freelancing.
      </p>
    </div>
  </section>

  <!-- Jobs List -->
  <section class="pb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

      <!-- Platform Card Template -->
      <?php
      $platforms = [
        [
          'name' => 'Remote4Africa',
          'url' => 'https://remote4africa.com/',
          'desc' => 'Verified remote job listings for African professionals with filters for Kenyan roles.',
          'type' => 'Job board • Africa-focused'
        ],
        [
          'name' => 'Remotasks',
          'url' => 'https://www.remotasks.com/en',
          'desc' => 'Earn from micro-tasks like data labeling and transcription. Free training provided.',
          'type' => 'Microtasks • AI data'
        ],
        [
          'name' => 'Indeed',
          'url' => 'https://www.indeed.com/',
          'desc' => 'Global job engine with remote filters. Includes roles open to Kenyans.',
          'type' => 'Global jobs • Professional'
        ],
        [
          'name' => 'FlexJobs',
          'url' => 'https://www.flexjobs.com/',
          'desc' => 'Vetted listings for remote and hybrid jobs. Subscription-based but scam-free.',
          'type' => 'Premium jobs • Global'
        ],
        [
          'name' => 'WeWorkRemotely',
          'url' => 'https://weworkremotely.com/',
          'desc' => 'One of the biggest remote job boards. Many listings open worldwide.',
          'type' => 'Remote jobs • Tech & Creative'
        ],
        [
          'name' => 'Upwork',
          'url' => 'https://www.upwork.com/',
          'desc' => 'Freelance marketplace for web, design, writing, and tech gigs. Widely used in Kenya.',
          'type' => 'Freelance marketplace'
        ],
        [
          'name' => 'African Freelancers',
          'url' => 'https://www.africanfreelancers.com/',
          'desc' => 'Community platform for African freelancers with tips, resources, and client leads.',
          'type' => 'Community • Africa-focused'
        ],
        [
          'name' => 'Himalayas.app',
          'url' => 'https://himalayas.app/jobs/countries/kenya',
          'desc' => 'Remote job board with Kenyan filters and AI-powered resume tools.',
          'type' => 'Remote jobs • Global reach'
        ],
        [
          'name' => 'Fiverr',
          'url' => 'https://www.fiverr.com/',
          'desc' => 'Offer freelance services starting at $5 — ideal for creatives and digital workers.',
          'type' => 'Freelance gigs • Global'
        ],
        [
          'name' => 'Remote Jobs Africa',
          'url' => 'https://remotejobs.africa/',
          'desc' => 'Dedicated to connecting Africans with remote jobs and digital careers.',
          'type' => 'Job board • Africa-focused'
        ]
      ];

      foreach ($platforms as $p):
      ?>
      <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
        <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-accent-500/10 blur-2xl"></div>
        <h3 class="text-lg font-semibold"><?= htmlspecialchars($p['name']) ?></h3>
        <p class="text-sm text-white/70 mt-1"><?= htmlspecialchars($p['type']) ?></p>
        <p class="mt-3 text-white/80 text-sm leading-relaxed"><?= htmlspecialchars($p['desc']) ?></p>
        <a href="<?= htmlspecialchars($p['url']) ?>" target="_blank"
           class="mt-4 inline-flex items-center gap-2 text-accent-500 hover:underline">
          Visit Site
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 3h7v7h-2V6.414l-9.293 9.293-1.414-1.414L17.586 5H14V3Z"/><path d="M5 5h5V3H3v7h2V5Z"/></svg>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="border-t border-white/10 bg-slate-950/40 py-6 text-center text-white/60 text-sm">
    © <?= date('Y') ?> JobSkills — Empowering Africans to work remotely.
  </footer>

</body>
</html>
