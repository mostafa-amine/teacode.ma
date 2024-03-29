require('particles.js');
import * as bs from 'bootstrap';

function drawBrandText() {
    let text = `
     /$$                                                   /$$
    | $$                                                  | $$
   /$$$$$$    /$$$$$$   /$$$$$$   /$$$$$$$  /$$$$$$   /$$$$$$$  /$$$$$$
  |_  $$_/   /$$__  $$ |____  $$ /$$_____/ /$$__  $$ /$$__  $$ /$$__  $$
    | $$    | $$$$$$$$  /$$$$$$$| $$      | $$  \\ $$| $$  | $$| $$$$$$$$
    | $$ /$$| $$_____/ /$$__  $$| $$      | $$  | $$| $$  | $$| $$_____/
    |  $$$$/|  $$$$$$$|  $$$$$$$|  $$$$$$$|  $$$$$$/|  $$$$$$$|  $$$$$$$
     \\___/   \\_______/ \\_______/ \\_______/ \\______/  \\_______/ \\_______/
    `;
    console.log(text);
}

function initParticlesJS() {
    if ($('#particles-js').length) {
        particlesJS.load('particles-js', '/plugins/particles/particles.min.json');
    }
}

function setCookie(name, value) {
    var d = new Date();
    d.setTime(d.getTime() + (365*24*60*60*1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

function toggleDarkMode(_body, isActive) {
    if (isActive) {
        _body.addClass('dark-mode').removeClass('light-mode');
        setCookie('mode', 'dark');
    } else {
        _body.removeClass('dark-mode').addClass('light-mode');
        setCookie('mode', 'light');
    }
}

function initDarkMode() {
    let _body = $(document.body);
    $(document).on('click', '.toggle-dark-mode', function () {
        let _this = $(this);
        let _isActive = !_body.hasClass('dark-mode');
        // let _isActive = localStorage.getItem('isDarkModeActive');
        _this.addClass('pushed');
        setTimeout(() => {
            _this.removeClass('pushed');
        }, 300);
        toggleDarkMode(_body, _isActive);
    });
}

function initGlobalActions() {
    $('.banner-close').on('click', function () {
        $(this).closest('.banner').remove();
    });
    $('.banner-text').on('mouseover', function () {
        $(this).find('.banner-tooltip').addClass('show')
    })
    $('.banner-text').on('mouseleave', function () {
        $(this).find('.banner-tooltip').removeClass('show')
    })
}

export { drawBrandText, initParticlesJS, initDarkMode, initGlobalActions }
