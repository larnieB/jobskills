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
          }
        }
      }
    }
  </script>
  <style>
  html { scroll-behavior: smooth; }

  /* Dark overlay with smooth transition and mask for cutout */
  #tourOverlay {
    background: rgba(0, 0, 0, 0.75);
    position: fixed;
    inset: 0;
    z-index: 40;
    pointer-events: auto;
    transition: all 0.5s ease-out;
    mask-composite: exclude;
    -webkit-mask-composite: destination-out;
  }

  /* Glowing ring around focused area */
  #highlightRing {
    position: absolute;
    z-index: 45;
    border: 4px solid #7a5cff;
    border-radius: 1rem;
    pointer-events: none;
    box-shadow: 0 0 25px 8px rgba(122, 92, 255, 0.7);
    animation: pulseGlow 1.8s infinite ease-in-out;
    transition: all 0.4s ease-in-out;
  }

  @keyframes pulseGlow {
    0%, 100% {
      box-shadow: 0 0 25px 8px rgba(122, 92, 255, 0.6);
      transform: scale(1);
    }
    50% {
      box-shadow: 0 0 35px 12px rgba(122, 92, 255, 0.8);
      transform: scale(1.01);
    }
  }

  /* Tooltip fade-in animation */
  #tourPopup {
    animation: fadeIn 0.3s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }

  /* --- HELP Button Highlight Feature --- */

/* Glow pulse effect */
.help-glow {
  box-shadow: 0 0 20px 6px rgba(122, 92, 255, 0.8);
  animation: helpPulse 1.8s infinite ease-in-out;
}

@keyframes helpPulse {
  0%, 100% {
    box-shadow: 0 0 15px 5px rgba(122, 92, 255, 0.6);
    transform: scale(1);
  }
  50% {
    box-shadow: 0 0 25px 10px rgba(122, 92, 255, 0.9);
    transform: scale(1.05);
  }
}

/* Typing effect */
@keyframes typeHelp {
  from { width: 0; }
  to { width: 100%; }
}

.help-typing {
  display: inline-block;
  white-space: nowrap;
  overflow: hidden;
  border-right: 2px solid white;
  animation: typeHelp 1s steps(4) forwards;
}

/* --- HELP button animated border drawing --- */
@keyframes borderDraw {
  0% {
    box-shadow: inset 0 0 0 0 rgba(122, 92, 255, 1);
  }
  25% {
    box-shadow: inset 0 0 0 2px rgba(122, 92, 255, 1);
  }
  50% {
    box-shadow: inset 0 0 0 4px rgba(122, 92, 255, 1);
  }
  75% {
    box-shadow: inset 0 0 0 6px rgba(122, 92, 255, 1);
  }
  100% {
    box-shadow: inset 0 0 0 8px rgba(122, 92, 255, 1);
  }
}

@keyframes borderErase {
  0% {
    box-shadow: inset 0 0 0 8px rgba(122, 92, 255, 1);
  }
  25% {
    box-shadow: inset 0 0 0 6px rgba(122, 92, 255, 1);
  }
  50% {
    box-shadow: inset 0 0 0 4px rgba(122, 92, 255, 1);
  }
  75% {
    box-shadow: inset 0 0 0 2px rgba(122, 92, 255, 1);
  }
  100% {
    box-shadow: inset 0 0 0 0 rgba(122, 92, 255, 0);
  }
}

/* Classes to trigger the effect */
.help-border-draw {
  animation: borderDraw 0.8s ease forwards;
}

.help-border-erase {
  animation: borderErase 0.8s ease forwards;
}

/* --- SNAKE BORDER EFFECT --- */
.help-snake-border {
  position: relative;
  z-index: 1;
  overflow: hidden;
}

.help-snake-border::before {
  content: "";
  position: absolute;
  inset: -2px;
  background: conic-gradient(
    from 0deg,
    #7a5cff,
    #2b89f0,
    #7a5cff,
    #6b50eb,
    #7a5cff
  );
  border-radius: inherit;
  z-index: -1;
  animation: snakeBorderRotate 2s linear infinite;
  mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  padding: 2px;
}

@keyframes snakeBorderRotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Fade-in and fade-out for snake effect */
.help-snake-border.fade-out::before {
  opacity: 0;
  transition: opacity 0.6s ease-in-out;
}

.help-snake-border.fade-in::before {
  opacity: 1;
  transition: opacity 0.6s ease-in-out;
}



</style>

</head>

<body class="min-h-screen bg-dash text-white relative">

  <!-- Header -->
  <header class="sticky top-0 z-40 backdrop-blur bg-slate-900/60 border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent-500 to-brand-500 grid place-items-center">⚡</div>
        <span class="font-semibold tracking-tight">AI Job Matching</span>
      </div>
      <div class="flex items-center gap-3">
        <a href="dashboard.php" class="text-sm px-3 py-1.5 rounded-lg border border-white/15 hover:bg-white/5">← Home</a>
        <!-- HELP button -->
        <button id="helpBtn" class="text-sm px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-500 transition font-semibold">HELP</button>
      </div>
    </div>
  </header>

  <!-- Overlay for onboarding -->
   <!-- Masked overlay (cutout effect controlled via JS) -->
<div id="tourOverlay" class="hidden"></div>
<!-- Glowing highlight ring -->
<div id="highlightRing" class="hidden"></div>


<!-- Tooltip popup -->
<div id="tourPopup" class="hidden fixed z-50 bg-white text-gray-900 rounded-xl shadow-lg p-5 w-80">
  <p id="tourText" class="text-sm mb-4"></p>
  <div class="flex justify-between">
    <button id="prevStep" class="px-3 py-1 text-sm rounded-lg bg-gray-200 hover:bg-gray-300">Previous</button>
    <button id="nextStep" class="px-3 py-1 text-sm rounded-lg bg-brand-600 text-white hover:bg-brand-500">Next</button>
    <button id="skipTour" class="px-3 py-1 text-sm rounded-lg bg-gray-200 hover:bg-gray-300">Skip</button>
  </div>
</div>

 

  <!-- Main Content -->
  <section class="py-10 sm:py-14">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welcome, <?php echo htmlspecialchars($userName); ?> </h1>
      <p class="text-white/70 mb-8">This is the place to create your very own CV. Just fill out the form below and press generate CV at the end to generate.</p>

      <form id="cvForm" class="space-y-6 bg-white/5 p-6 rounded-2xl border border-white/10 shadow-soft">
  <h2 class="text-lg font-semibold mb-4 text-accent-500">Personal Information</h2>

  <div class="grid sm:grid-cols-3 gap-4">
    <!-- First Name -->
    <div>
      <label class="block text-sm mb-1 text-white/80">First Name</label>
      <input type="text" id="firstName" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" required>
    </div>

    <!-- Second Name (Optional) -->
    <div>
      <label class="block text-sm mb-1 text-white/80">Second Name (Optional)</label>
      <input type="text" id="secondName" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none">
    </div>

    <!-- Last Name -->
    <div>
      <label class="block text-sm mb-1 text-white/80">Last Name</label>
      <input type="text" id="lastName" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" required>
    </div>
  </div>

  <div class="grid sm:grid-cols-2 gap-4">
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

  <h2 class="text-lg font-semibold mt-6 text-accent-500">Education</h2>
  <textarea id="education" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" rows="3" placeholder="E.g., BSc in Computer Science, Chuka University (2020–2024)" required></textarea>

  <h2 class="text-lg font-semibold mt-6 text-accent-500">Work Experience</h2>
  <textarea id="experience" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" rows="3" placeholder="E.g., Software Developer Intern at Safaricom PLC (April–September 2023)" required></textarea>

  <h2 class="text-lg font-semibold mt-6 text-accent-500">Skills</h2>
  <textarea id="skills" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" rows="3" placeholder="E.g., HTML, CSS, JavaScript, PHP, Python, AI tools, etc." required></textarea>

  <h2 class="text-lg font-semibold mt-6 text-accent-500">Achievements & Certifications</h2>
  <textarea id="achievements" class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:ring-2 focus:ring-accent-500 focus:outline-none" rows="3" placeholder="E.g., Google Digital Skills Certificate, 2023"></textarea>

  <h2 class="text-lg font-semibold mt-6 text-accent-500">Upload Passport Photo</h2>
  <div class="flex flex-col sm:flex-row items-center gap-4">
    <input type="file" id="passportPhoto" accept="image/*"
      class="block w-full text-sm text-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-500 cursor-pointer" required>
    <div class="w-24 h-24 rounded-full overflow-hidden border border-white/10 bg-white/5 flex items-center justify-center">
      <img id="photoPreview" src="" alt="Passport Preview" class="w-full h-full object-cover hidden">
    </div>
  </div>
</form>



      <div class="mt-8 text-center">
        <button id="generateBtn" class="px-5 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 transition font-semibold">
          Generate a CV if you don't have one
        </button>
      </div>

      <div id="cvOutput" class="mt-10 hidden bg-white text-gray-900 rounded-lg overflow-hidden shadow-xl">
        <div class="grid grid-cols-3">
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
    // --- Onboarding Tour ---
    const steps = [
      { elementId: 'cvForm', text: 'Fill out this form with the correct information.' },
      { elementId: 'helpBtn', text: "If you don't understand anything or don't know what to fill in, feel free to ask AI by pressing here." }
    ];
    let currentStep = 0;

    const overlay = document.getElementById('tourOverlay');
    const popup = document.getElementById('tourPopup');
    const highlightBox = document.getElementById('highlightBox');
    const tourText = document.getElementById('tourText');
    const nextBtn = document.getElementById('nextStep');
    const prevBtn = document.getElementById('prevStep');
    const skipBtn = document.getElementById('skipTour');

   function showStep(stepIndex) {
  const step = steps[stepIndex];
  const target = document.getElementById(step.elementId);
  if (!target) return;

  const rect = target.getBoundingClientRect();
  const overlay = document.getElementById('tourOverlay');
  const ring = document.getElementById('highlightRing');

  // Position the glowing ring over the target
  ring.style.width = rect.width + 20 + 'px';
  ring.style.height = rect.height + 20 + 'px';
  ring.style.top = rect.top + window.scrollY - 10 + 'px';
  ring.style.left = rect.left + window.scrollX - 10 + 'px';

  // Create a transparent hole (cutout)
  const cx = rect.left + rect.width / 2 + window.scrollX;
  const cy = rect.top + rect.height / 2 + window.scrollY;
  const radius = Math.max(rect.width, rect.height) / 2 + 40;

  overlay.style.webkitMaskImage = `radial-gradient(circle ${radius}px at ${cx}px ${cy}px, transparent 0%, black 100%)`;
  overlay.style.maskImage = `radial-gradient(circle ${radius}px at ${cx}px ${cy}px, transparent 0%, black 100%)`;

  // 🧠 Smart popup positioning
  const popupWidth = 320; // same as w-80 (80 * 4)
  const popupHeight = 150;
  let popupX = rect.left + rect.width + 20; // default: right side
  let popupY = rect.top + window.scrollY;

  // If near right edge → shift left
  if (rect.right + popupWidth + 20 > window.innerWidth) {
    popupX = rect.left - popupWidth - 20;
  }

  // If near bottom edge → move above
  if (rect.bottom + popupHeight > window.innerHeight) {
    popupY = rect.top + window.scrollY - popupHeight - 20;
  }

  // Apply popup position
  popup.style.left = Math.max(popupX, 20) + 'px';
  popup.style.top = Math.max(popupY, 20) + 'px';

  // Update text & show
  tourText.textContent = step.text;
  overlay.classList.remove('hidden');
popup.classList.remove('hidden');
ring.classList.remove('hidden');

// Manage navigation
prevBtn.disabled = stepIndex === 0;
nextBtn.textContent = stepIndex === steps.length - 1 ? 'Finish' : 'Next';

// --- New Feature: HELP Button Glow + Typing Animation ---
const helpBtn = document.getElementById('helpBtn');
if (step.elementId === 'helpBtn') {
  // Add glow
  helpBtn.classList.add('help-glow');

  // Re-type "HELP" dynamically
  const originalText = 'HELP';
  helpBtn.textContent = '';
  let i = 0;
  const typeInterval = setInterval(() => {
    helpBtn.textContent += originalText[i];
    i++;
    if (i === originalText.length) {
      clearInterval(typeInterval);
    }
  }, 150);
} else {
  // Remove glow if we’re on other steps
  helpBtn.classList.remove('help-glow');
  helpBtn.textContent = 'HELP';
}

}



   function hideTour() {
  overlay.classList.add('hidden');
  popup.classList.add('hidden');
  document.getElementById('highlightRing').classList.add('hidden');

  const helpBtn = document.getElementById('helpBtn');

  // Keep the button glowing but activate the snake border
  helpBtn.classList.add('help-snake-border', 'fade-in', 'help-glow');

  let isAtTop = true;

  window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;

    // If user scrolls down - remove glow and fade out snake border
    if (scrollTop > 30 && isAtTop) {
      helpBtn.classList.remove('help-glow');
      helpBtn.classList.add('fade-out');
      helpBtn.classList.remove('fade-in');
      isAtTop = false;
    }

    // If user scrolls back up near the top - restore glow and border
    if (scrollTop <= 30 && !isAtTop) {
      helpBtn.classList.add('help-glow');
      helpBtn.classList.add('fade-in');
      helpBtn.classList.remove('fade-out');
      isAtTop = true;
    }
  });
}



    nextBtn.addEventListener('click', () => {
      if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
      } else {
        hideTour();
      }
    });

    prevBtn.addEventListener('click', () => {
      if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
      }
    });

    skipBtn.addEventListener('click', hideTour);

    window.addEventListener('load', () => {
      setTimeout(() => showStep(0), 700);
    });

    // --- Your Existing Logic ---
    const form = document.getElementById('cvForm');
    const generateBtn = document.getElementById('generateBtn');
    const cvOutput = document.getElementById('cvOutput');
    const passportInput = document.getElementById('passportPhoto');
    const photoPreview = document.getElementById('photoPreview');
    const cvPhoto = document.getElementById('cvPhoto');

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

    generateBtn.addEventListener('click', () => {
      const firstName = document.getElementById('firstName').value.trim();
const secondName = document.getElementById('secondName').value.trim();
const lastName = document.getElementById('lastName').value.trim();
const name = [firstName, secondName, lastName].filter(Boolean).join(' ');

      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const location = document.getElementById('location').value.trim();
      const education = document.getElementById('education').value.trim();
      const experience = document.getElementById('experience').value.trim();
      const skills = document.getElementById('skills').value.trim();
      const achievements = document.getElementById('achievements').value.trim();
      const file = passportInput.files[0];

      if (!name || !email || !phone) {
        alert('Please fill in your basic details first.');
        return;
      }

      document.getElementById('cvName').textContent = name;
      document.getElementById('cvEmail').textContent = email;
      document.getElementById('cvPhone').textContent = phone;
      document.getElementById('cvLocation').textContent = location || '';
      document.getElementById('cvSkills').textContent = skills || '';
      document.getElementById('cvExperience').textContent = experience || '';
      document.getElementById('cvEducation').textContent = education || '';
      document.getElementById('cvAchievements').textContent = achievements || '';

      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          cvPhoto.src = e.target.result;
          cvPhoto.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      } else {
        cvPhoto.classList.add('hidden');
      }

      cvOutput.classList.remove('hidden');
      window.scrollTo({ top: cvOutput.offsetTop, behavior: 'smooth' });
    });

    
  </script>
</body>

</html>
