let currentIndex = 0;

function moveCarousel(step) {
    const carousel = document.querySelector('.carousel');
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;

    currentIndex = (currentIndex + step + totalItems) % totalItems;
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
}

window.addEventListener('scroll', function() {
    const fixedNavbar = document.querySelector('.fixed-navbar');
    if (window.scrollY > 100) {
        fixedNavbar.style.top = '0';
    } else {
        fixedNavbar.style.top = '-100px';
    }
});
