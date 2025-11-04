// Espera a que el DOM esté cargado
document.addEventListener('DOMContentLoaded', () => {
    const carouselTrack = document.querySelector('.carousel-track');
    
    // Asegurarse de que el carrusel existe en esta página
    if (carouselTrack) {
        const slides = Array.from(carouselTrack.children);
        const dotsContainer = document.querySelector('.carousel-dots');
        let slideIndex = 0;
        let intervalId;

        // Salir si no hay slides
        if (slides.length === 0) return;

        const slideWidth = slides[0].getBoundingClientRect().width;

        // Crear los puntos de navegación
        slides.forEach((_, index) => {
            const dot = document.createElement('span');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => {
                goToSlide(index);
                resetAutoSlide(); // Reinicia el timer si se hace clic manual
            });
            dotsContainer.appendChild(dot);
        });
        const dots = Array.from(dotsContainer.children);

        // Función para mover a un slide específico
        function goToSlide(index) {
            // Asegurarse de que el ancho es correcto (por si cambia el tamaño de la ventana)
            const currentSlideWidth = slides[0].getBoundingClientRect().width;
            carouselTrack.style.transform = `translateX(-${index * currentSlideWidth}px)`;
            
            // Actualizar el punto activo
            dots.forEach(dot => dot.classList.remove('active'));
            dots[index].classList.add('active');
            
            slideIndex = index;
        }

        // Función para el siguiente slide
        function autoSlide() {
            slideIndex = (slideIndex + 1) % slides.length;
            goToSlide(slideIndex);
        }

        // Iniciar el auto-deslizamiento
        function startAutoSlide() {
            intervalId = setInterval(autoSlide, 5000); // Cambia cada 5 segundos
        }

        // Reiniciar el auto-deslizamiento
        function resetAutoSlide() {
            clearInterval(intervalId);
            startAutoSlide();
        }

        // Ajustar el carrusel si la ventana cambia de tamaño
        window.addEventListener('resize', () => {
            goToSlide(slideIndex);
        });

        // Iniciar todo
        startAutoSlide();
    }
});