/**
 * Carrusel de la página de tienda
 */
document.addEventListener('DOMContentLoaded', () => {
    const carouselTrack = document.querySelector('.carousel-track');
    
    if (carouselTrack) {
        const slides = Array.from(carouselTrack.children);
        const dotsContainer = document.querySelector('.carousel-dots');
        let slideIndex = 0;
        let intervalId;

        if (slides.length === 0) return;
        
        // Clonar slides para efecto infinito
        slides.forEach(slide => {
            carouselTrack.appendChild(slide.cloneNode(true));
        });

        function goToSlide(index, smooth = true) {
            const slides = Array.from(carouselTrack.children);
            if (slides.length === 0 || !slides[0]) return;
            
            const currentSlideWidth = slides[0].getBoundingClientRect().width;
            
            if (!smooth) carouselTrack.style.transition = 'none';
            carouselTrack.style.transform = `translateX(-${index * currentSlideWidth}px)`;
            
            if (!smooth) {
                carouselTrack.offsetHeight; 
                carouselTrack.style.transition = 'transform 0.8s ease-in-out';
            }
            
            // Actualizar dots
            let activeDotIndex = index % (slides.length / 2);
            dots.forEach(dot => dot.classList.remove('active'));
            if (dots[activeDotIndex]) {
                dots[activeDotIndex].classList.add('active');
            }
        }

        function autoSlide() {
            const slides = Array.from(carouselTrack.children);
            const totalSlides = slides.length / 2;
            
            slideIndex++;
            goToSlide(slideIndex);

            // Reset al llegar al final
            if (slideIndex >= totalSlides) {
                setTimeout(() => {
                    slideIndex = 0;
                    goToSlide(slideIndex, false);
                }, 800); 
            }
        }
        
        // Crear dots de navegación
        const dots = [];
        const originalSlidesCount = slides.length;
        
        for(let i = 0; i < originalSlidesCount; i++) {
            const dot = document.createElement('span');
            dot.classList.add('dot');
            if (i === 0) dot.classList.add('active');
            
            dot.addEventListener('click', () => {
                slideIndex = i;
                goToSlide(slideIndex);
                resetAutoSlide();
            });
            
            dotsContainer.appendChild(dot);
            dots.push(dot);
        }

        // Auto-slide cada 5 segundos
        intervalId = setInterval(autoSlide, 5000); 

        function resetAutoSlide() {
            clearInterval(intervalId);
            intervalId = setInterval(autoSlide, 5000);
        }

        // Ajustar en resize
        window.addEventListener('resize', () => {
            goToSlide(slideIndex, false);
        });
    }
});
