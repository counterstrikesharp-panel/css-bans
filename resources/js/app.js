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
    mainNavbar.style.background = 'rgb(28 28 28)';
    document.documentElement.dataset.mdbTheme = 'dark';
    $("body").addClass('body-dark-mode')
    $(".card").addClass('card-dark-mode')
    $(".card-body").addClass('card-dark-mode')
    $(".table").addClass('card-dark-mode')
    $("#sidebarMenu").addClass('body-dark-mode');
    $("html").show();
} else {
    mainNavbar.style.background = '#fff';
    $("body").removeClass('body-dark-mode')
    $(".card").removeClass('card-dark-mode')
    $(".card-body").removeClass('card-dark-mode')
    $(".table").removeClass('card-dark-mode')
    $("#sidebarMenu").removeClass('body-dark-mode');
    $("html").show();
}

// add listener to theme toggler
themeStitcher.addEventListener("change", (e) => {
    toggleTheme(e.target.checked);
});

const toggleTheme = (isChecked) => {
    const theme = isChecked ? "dark" : "light";
    document.documentElement.dataset.mdbTheme = theme; // Update data-mdb-theme attribute
    if (theme == 'dark') {
        mainNavbar.style.background = 'rgb(28 28 28)';
        $("body").addClass('body-dark-mode')
        $(".card").addClass('card-dark-mode')
        $(".card-body").addClass('card-dark-mode')
        $(".table").addClass('card-dark-mode')
        $("#sidebarMenu").addClass('body-dark-mode');
        $("html").show();
    } else {
        mainNavbar.style.background = '#fff';
        $("body").removeClass('body-dark-mode')
        $(".card").removeClass('card-dark-mode')
        $(".card-body").removeClass('card-dark-mode')
        $(".table").removeClass('card-dark-mode')
        $("#sidebarMenu").removeClass('body-dark-mode');
        $("html").show(); // flickering fix
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

