import './bootstrap';
import $ from 'jquery';
// global alert dismiss
$(document).ready(function() {
    // Handle click event of close button
    $('.alert-close').click(function() {
        // Hide or remove the parent alert element
        $(this).parent('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    });
})

const themeStitcher = document.getElementById("themeStitcher");
const isSystemThemeSetToDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
const mainNavbar = document.getElementById('main-navbar');

// Retrieve theme preference from local storage
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark' || (isSystemThemeSetToDark && savedTheme === null)) {
    themeStitcher.checked = true;
    mainNavbar.style.background = '#333';
    document.documentElement.dataset.mdbTheme = 'dark';
} else {
    mainNavbar.style.background = '#fff';
}

// add listener to theme toggler
themeStitcher.addEventListener("change", (e) => {
    toggleTheme(e.target.checked);
});

const toggleTheme = (isChecked) => {
    const theme = isChecked ? "dark" : "light";
    document.documentElement.dataset.mdbTheme = theme; // Update data-mdb-theme attribute
    if (theme == 'dark') {
        mainNavbar.style.background = '#333';
    } else {
        mainNavbar.style.background = '#fff';
    }
    // Save theme preference to local storage
    localStorage.setItem('theme', theme);
}

// add listener to toggle theme with Shift + D
document.addEventListener("keydown", (e) => {
    if (e.shiftKey && e.key === "D") {
        themeStitcher.checked = !themeStitcher.checked;
        toggleTheme(themeStitcher.checked);
    }
});

