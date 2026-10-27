// Practical 4 runs on PRACTICAL-02/index.html so the existing Home page gains interactions.
const homePage = document.body;
const themeButton = document.querySelector("#theme-toggle");
const menuButton = document.querySelector("#menu-toggle");
const navigation = document.querySelector("#primary-nav");

function readSavedTheme() {
  try {
    const savedTheme = localStorage.getItem("studenthub-theme");
    if (savedTheme === "light" || savedTheme === "dark") return savedTheme;
  } catch (error) {
    // The page remains usable if browser storage is unavailable.
  }
  return "light";
}

function applyTheme(theme, saveChoice = false) {
  homePage.dataset.theme = theme;
  themeButton.setAttribute("aria-pressed", String(theme === "dark"));
  themeButton.textContent = theme === "dark" ? "☀ Use light theme" : "🌙 Use dark theme";
  if (saveChoice) {
    try {
      localStorage.setItem("studenthub-theme", theme);
    } catch (error) {
      // The selected theme still applies for this visit.
    }
  }
}

applyTheme(readSavedTheme());
themeButton.addEventListener("click", () => {
  const nextTheme = homePage.dataset.theme === "dark" ? "light" : "dark";
  applyTheme(nextTheme, true);
});

function setMenuOpen(isOpen) {
  menuButton.setAttribute("aria-expanded", String(isOpen));
  navigation.classList.toggle("menu-open", isOpen);
  navigation.classList.toggle("menu-collapsed", !isOpen);
  menuButton.querySelector(".menu-icon").textContent = isOpen ? "×" : "☰";
  menuButton.querySelector(".sr-only").textContent = isOpen ? "Close navigation menu" : "Open navigation menu";
}

menuButton.addEventListener("click", () => {
  const isOpen = menuButton.getAttribute("aria-expanded") === "true";
  setMenuOpen(!isOpen);
});
navigation.querySelectorAll("a").forEach((link) => link.addEventListener("click", () => setMenuOpen(false)));

// Each FAQ button exposes its answer and updates aria-expanded.
document.querySelectorAll(".faq-toggle").forEach((button) => {
  button.addEventListener("click", () => {
    const isExpanded = button.getAttribute("aria-expanded") === "true";
    const answer = document.getElementById(button.getAttribute("aria-controls"));
    button.setAttribute("aria-expanded", String(!isExpanded));
    answer.hidden = isExpanded;
    button.querySelector("span").textContent = isExpanded ? "+" : "−";
  });
});

// Change the active StudentHub highlight without reloading the Home page.
const slides = Array.from(document.querySelectorAll(".spotlight-slide"));
const slideStatus = document.querySelector("#slide-status");
let currentSlide = 0;

function showSlide(index) {
  currentSlide = (index + slides.length) % slides.length;
  slides.forEach((slide, slideIndex) => {
    slide.hidden = slideIndex !== currentSlide;
  });
  slideStatus.textContent = `Highlight ${currentSlide + 1} of ${slides.length}`;
}

document.querySelector("#previous-slide").addEventListener("click", () => showSlide(currentSlide - 1));
document.querySelector("#next-slide").addEventListener("click", () => showSlide(currentSlide + 1));

document.querySelector("#dismiss-notice").addEventListener("click", () => {
  document.querySelector("#updates").hidden = true;
});

// The native dialog manages focus and can be closed with Escape.
const announcement = document.querySelector("#announcement-dialog");
document.querySelector("#open-announcement").addEventListener("click", () => announcement.showModal());
document.querySelectorAll("#close-announcement, #dialog-done").forEach((button) => {
  button.addEventListener("click", () => announcement.close());
});
