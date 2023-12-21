import './styles/burger.css'


document.addEventListener('DOMContentLoaded', function () {
    let burgerIcon = document.getElementById('burger-icon');
    let mobileMenu = document.getElementById('web-menu');

    burgerIcon.addEventListener('click', function () {
        mobileMenu.style.display = mobileMenu.style.display === 'flex' ? 'none' : 'flex';
    });
});
