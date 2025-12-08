/**
 * Funcionalidad del carrusel en el panel de administración
 */
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.admin-carousel');
    if (!carousel) return;

    const track = carousel.querySelector('.admin-carousel-track');
    const cards = Array.from(track.children);
    if (!cards.length) return;

    const prevBtn = carousel.querySelector('.carousel-prev');
    const nextBtn = carousel.querySelector('.carousel-next');
    let index = 0;

    function updateButtons() {
        prevBtn.disabled = index === 0;
        nextBtn.disabled = index >= cards.length - 1;
    }

    function goToSlide(idx) {
        const width = carousel.querySelector('.admin-carousel-window').clientWidth;
        const gap = 20; // coincide con el gap del track
        track.style.transform = `translateX(-${idx * (width + gap)}px)`;
        index = idx;
        updateButtons();
    }

    prevBtn.addEventListener('click', () => {
        if (index > 0) goToSlide(index - 1);
    });

    nextBtn.addEventListener('click', () => {
        if (index < cards.length - 1) goToSlide(index + 1);
    });

    window.addEventListener('resize', () => goToSlide(index));

    goToSlide(0);
});
