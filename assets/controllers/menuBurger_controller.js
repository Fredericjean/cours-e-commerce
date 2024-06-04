import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const burgerBtn = document.getElementById('burger-btn');
        const closeBtn = this.element.querySelector('.close-btn');
        const burgerMenu = this.element;

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
    }
}
