function updateThemeUI(theme) {
  const toggle = document.getElementById("theme-toggle");
  const iconWrapper = document.getElementById("theme-label");
  const themeChip = document.getElementById("themeLabel");

  if (toggle) toggle.checked = theme === "dark";
  if (iconWrapper) {
    const icon = iconWrapper.querySelector("i");
    if (icon) {
      if (theme === "dark") {
        icon.classList.replace("bi-moon-stars-fill", "bi-sun-fill");
      } else {
        icon.classList.replace("bi-sun-fill", "bi-moon-stars-fill");
      }
    }
  }
  if (themeChip) {
    themeChip.textContent = theme.charAt(0).toUpperCase() + theme.slice(1);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme") || "light";
  document.documentElement.setAttribute("data-theme", savedTheme);
  updateThemeUI(savedTheme);
});

function toggleTheme() {
  const html = document.documentElement;
  const toggle = document.getElementById("theme-toggle");
  const newTheme = toggle && toggle.checked ? "dark" : "light";
  html.setAttribute("data-theme", newTheme);
  localStorage.setItem("theme", newTheme);
  updateThemeUI(newTheme);
}
