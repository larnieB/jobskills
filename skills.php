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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Courses — JobSkills</title>
  <meta name="description" content="Explore AI basics, interview tips, and communication skills with JobSkills courses." />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: { 500: '#2b89f0', 600: '#1f6ed4' },
            accent: { 500: '#7a5cff', 600: '#6b50eb' }
          },
          boxShadow: {
            soft: '0 10px 30px rgba(2,6,23,0.12)',
            glow: '0 0 0 4px rgba(122,92,255,0.2)'
          },
          backgroundImage: {
            dash: 'radial-gradient(1000px 400px at 0% -10%, rgba(122,92,255,.18), transparent 40%), radial-gradient(900px 300px at 100% -20%, rgba(43,137,240,.18), transparent 40%), linear-gradient(180deg, #0b1020, #0b1225)'
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
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent-500 to-brand-500 grid place-items-center">🎓</div>
        <span class="font-semibold tracking-tight">JobSkills Learning</span>
      </div>
      <div class="flex items-center gap-3 text-sm">
        <span class="hidden sm:inline text-white/70">👋 Hi, <strong><?= htmlspecialchars($userName) ?></strong></span>
        <a href="dashboard.php" class="px-3 py-1.5 rounded-lg border border-white/15 hover:bg-white/5">Dashboard</a>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section class="relative py-16 text-center overflow-hidden">
    <div class="max-w-4xl mx-auto px-6">
      <h1 class="text-3xl sm:text-5xl font-bold mb-4">Welcome to JobSkills</h1>
      <p class="text-white/80 max-w-2xl mx-auto">
        Your go-to hub for mastering AI basics, interview skills, and communication essentials.
      </p>
      <a href="#courses" class="mt-8 inline-block bg-gradient-to-r from-accent-500 to-brand-500 px-6 py-3 rounded-xl font-semibold shadow-soft hover:opacity-90 transition">
        Explore Courses
      </a>
    </div>
  </section>

  <!-- Featured Courses -->
  <section id="courses" class="py-12 border-t border-white/10 bg-slate-950/40">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-2xl font-semibold mb-6">Featured Courses</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdG5k50CqWaZ5_QKX-2Z70HuVbQh2oQG2k6OqilyUYSH07E3hhfdQjqpJJLCnCXVH_YgrV-mdJWiQ34Bl3U9TQ7Rt1zbqY13qFNLbFFjvFqsir5USODBEkiktqyJ7hXuamZ2PgQEGN-nNNycTOEi2OO2PX-AFIeYeMYpBzDDRgDn0ZA2C1-bpyad8rd21ivvXQEQ4vubJnLq3NwmF_8n4bRvv_54AFfdp_Ju92Ntmvi5P2ThBsFtqxnzms17C9XdVuhSDS8cOBmYs" alt="AI Basics" class="rounded-lg mb-4 w-full h-48 object-cover">
          <h3 class="text-lg font-semibold">AI Basics Literacy</h3>
          <p class="text-white/70 text-sm mt-2">Learn the fundamentals of Artificial Intelligence and its applications across industries.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBeBBft9sJiYEm7rnYy8-iQB_q5slxP-HcuwCe73bOt8RHpz0Ui1BCmQXI2Jm4uTAxCTTXanHsKemyzsAiAdlxBF-3US-pLJfIM3SDSL4TucuA30FTxcwfMzrC2sB-9AXpjWSyt-h-lz_Uczpc5ZPiQffHbDydU-3UnrkDg07mWto6Q1whdfr8gwwO935oTuZxWCbD3J_J-5l7r2oKfxTmj-l-jB1IEsndDANABqNFddJllAgQfX43NluitY1cyOpcvVUFTz9N7lrQ" alt="Job Interview Tips" class="rounded-lg mb-4 w-full h-48 object-cover">
          <h3 class="text-lg font-semibold">Job Interview Tips</h3>
          <p class="text-white/70 text-sm mt-2">Master the art of job interviews with expert strategies and communication techniques.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAxhn38TT8eGj-QklDGTMNnLQTOSEiQMSUU5DvvPWcPGM3DeevEAhJmvDFRGgO-5yIx1vPT8YP7Y1hGCcHB1mxUXNfjH6021fDVmjqpuo9FslXGM2M6fzvkMzOnCNfdx_IbxxNzg-10vMSiYMiTGwcDysfNWuG4uqcYq3PjhY0tJO0DnAl9y7Dbvj4LDzeaenZmZ8ofXuZZ72dkhFYyUmsi7povXWCyBTUvscDfekcQ5_ChabMU4gOkvJ87Jzk82wLFr-oZ4APT_U8" alt="Public Relations" class="rounded-lg mb-4 w-full h-48 object-cover">
          <h3 class="text-lg font-semibold">Public Relations</h3>
          <p class="text-white/70 text-sm mt-2">Enhance your public relations and communication management for professional growth.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Quick Tips -->
  <section class="py-12 border-t border-white/10">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-2xl font-semibold mb-6">Quick Tips</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <div class="text-accent-500 text-3xl mb-3">🏢</div>
          <h3 class="font-semibold text-lg">AI in the Workplace</h3>
          <p class="text-white/70 text-sm mt-2">Discover how AI is reshaping industries and creating new opportunities.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <div class="text-brand-500 text-3xl mb-3">🚀</div>
          <h3 class="font-semibold text-lg">Interview Success</h3>
          <p class="text-white/70 text-sm mt-2">Get concise strategies for interview preparation and confidence building.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <div class="text-emerald-400 text-3xl mb-3">💬</div>
          <h3 class="font-semibold text-lg">Effective Communication</h3>
          <p class="text-white/70 text-sm mt-2">Improve your communication skills for personal and professional growth.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Resources -->
  <section class="py-12 border-t border-white/10 bg-slate-950/40">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-2xl font-semibold mb-6">Resources</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <div class="text-accent-500 text-3xl mb-3">📘</div>
          <h3 class="font-semibold text-lg">AI Literacy Guide</h3>
          <p class="text-white/70 text-sm mt-2">Download our AI guide to stay current and competitive in your field.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <div class="text-brand-500 text-3xl mb-3">🎥</div>
          <h3 class="font-semibold text-lg">Interview Practice Videos</h3>
          <p class="text-white/70 text-sm mt-2">Practice with expert-led interview scenarios to boost your performance.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition p-6 shadow-soft">
          <div class="text-emerald-400 text-3xl mb-3">🧾</div>
          <h3 class="font-semibold text-lg">PR Templates</h3>
          <p class="text-white/70 text-sm mt-2">Access ready-to-use templates for press releases and public campaigns.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="border-t border-white/10 bg-slate-950/60 py-6 text-center text-white/60 text-sm">
    © <?= date('Y') ?> JobSkills. All rights reserved.
  </footer>

</body>
</html>
