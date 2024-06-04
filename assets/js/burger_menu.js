document.addEventListener('DOMContentLoaded', function () {
    const burgerBtn = document.getElementById('burger-btn');
    const burgerMenu = document.getElementById('burger-menu');
    const closeBtn = burgerMenu.querySelector('.close-btn');

    burgerBtn.addEventListener('click', function () {
        burgerMenu.classList.add('open');
    });

    closeBtn.addEventListener('click', function () {
        burgerMenu.classList.remove('open');
    });

    document.addEventListener('click', function (event) {
        if (!burgerMenu.contains(event.target) && !burgerBtn.contains(event.target)) {
            burgerMenu.classList.remove('open');
        }
    });
});