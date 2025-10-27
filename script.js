// ---------- Helper selectors ----------
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

// ---------- Modal elements ----------
const backdrop = $("#modal-backdrop");
const signin = $("#modal-signin");
const signup = $("#modal-signup");

// ---------- Open modal ----------
function openModal(which) {
  const modal = which === "signin" ? signin : signup;

  [signin, signup].forEach(m => m.classList.add("hidden", "opacity-0", "pointer-events-none"));

  backdrop.classList.remove("hidden");
  requestAnimationFrame(() => backdrop.classList.remove("opacity-0"));

  modal.classList.remove("hidden");
  requestAnimationFrame(() => modal.classList.remove("opacity-0", "pointer-events-none"));

  document.documentElement.style.overflow = "hidden";
}

// ---------- Close modal ----------
function closeModal() {
  [signin, signup].forEach(m => m.classList.add("opacity-0", "pointer-events-none"));
  backdrop.classList.add("opacity-0");

  setTimeout(() => {
    [signin, signup].forEach(m => m.classList.add("hidden"));
    backdrop.classList.add("hidden");
    document.documentElement.style.overflow = "";
  }, 250);
}

// ---------- Event listeners ----------
$$("[data-open]").forEach(btn =>
  btn.addEventListener("click", () => openModal(btn.getAttribute("data-open")))
);

$$("[data-swap]").forEach(btn =>
  btn.addEventListener("click", () => openModal(btn.getAttribute("data-swap")))
);

$$("[data-dismiss], #closeModal").forEach(btn =>
  btn.addEventListener("click", closeModal)
);

backdrop?.addEventListener("click", e => {
  if (e.target === backdrop) closeModal();
});

document.addEventListener("keydown", e => {
  if (e.key === "Escape") closeModal();
});

// ---------- Sign Up (AJAX) ----------
const signupForm = $("#signupForm");
if (signupForm) {
  signupForm.addEventListener("submit", async e => {
    e.preventDefault();

    // Reset error messages
    ["nameError", "emailError", "passwordError"].forEach(id => $("#" + id)?.classList.add("hidden"));

    const formData = new FormData(signupForm);
    const payload = Object.fromEntries(formData.entries());

    try {
      const res = await fetch("backend.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const data = await res.json();

      if (data.ok) {
        $("#signupSubmit").textContent = "Redirecting...";
        setTimeout(() => (window.location.href = data.redirect || "dashboard.php"), 700);
      } else if (data.errors) {
        Object.entries(data.errors).forEach(([key, msg]) => {
          const el = $("#" + key + "Error");
          if (el) {
            el.textContent = msg;
            el.classList.remove("hidden");
          }
        });
      } else {
        alert(data.message || "Unexpected error.");
      }
    } catch (err) {
      console.error(err);
      alert("Network error. Please try again.");
    }
  });
}

// ---------- Sign In (AJAX to backend.php) ----------
const signinForm = $("#signinForm");
if (signinForm) {
  signinForm.addEventListener("submit", async e => {
    e.preventDefault();

    const msg = $("#signinMsg");
    msg.textContent = "Signing in...";
    msg.classList.remove("text-red-400", "text-green-400");

    const formData = new FormData(signinForm);
    formData.append("action", "signin");

    try {
      const res = await fetch("backend.php", {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      if (data.status === "success") {
        msg.textContent = "Login successful! Redirecting...";
        msg.classList.add("text-green-400");
        setTimeout(() => (window.location.href = data.redirect || "dashboard.php"), 1000);
      } else {
        msg.textContent = data.message || "Invalid email or password.";
        msg.classList.add("text-red-400");
      }
    } catch (error) {
      console.error("Sign-in error:", error);
      msg.textContent = "Network error. Please try again.";
      msg.classList.add("text-red-400");
    }
  });
}

// ---------- Google placeholders ----------
$$("[data-google]").forEach(btn =>
  btn.addEventListener("click", () => {
    alert("Google Sign-In placeholder.\nIntegrate real Google OAuth later.");
  })
);
