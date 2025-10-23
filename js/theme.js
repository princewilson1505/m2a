document.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme") || "light";
  document.documentElement.setAttribute("data-theme", savedTheme);

  const toggle = document.getElementById("theme-toggle");
  const icon = document.getElementById("theme-label").querySelector("i");

  if (savedTheme === "dark") {
    toggle.checked = true;
    icon.classList.replace("bi-moon-stars-fill", "bi-sun-fill");
  }
});

function toggleTheme() {
  const html = document.documentElement;
  const toggle = document.getElementById("theme-toggle");
  const icon = document.getElementById("theme-label").querySelector("i");

  const newTheme = toggle.checked ? "dark" : "light";
  html.setAttribute("data-theme", newTheme);
  localStorage.setItem("theme", newTheme);

  // Switch icon
  if (newTheme === "dark") {
    icon.classList.replace("bi-moon-stars-fill", "bi-sun-fill");
  } else {
    icon.classList.replace("bi-sun-fill", "bi-moon-stars-fill");
  }
}
