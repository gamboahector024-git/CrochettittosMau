// Carousel functionality for tienda pages
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
        
        // Clonar slides para el efecto infinito
        slides.forEach(slide => {
            carouselTrack.appendChild(slide.cloneNode(true));
        });

        // Función para mover a un slide específico
        function goToSlide(index, smooth = true) {
            const slides = Array.from(carouselTrack.children);
            // Prevenir error si los slides aún no están cargados
            if (slides.length === 0 || !slides[0]) return;
            const currentSlideWidth = slides[0].getBoundingClientRect().width;
            if (!smooth) carouselTrack.style.transition = 'none';
            carouselTrack.style.transform = `translateX(-${index * currentSlideWidth}px)`;
            if (!smooth) {
                carouselTrack.offsetHeight; 
                carouselTrack.style.transition = 'transform 0.8s ease-in-out';
            }
            
            let activeDotIndex = index % (slides.length / 2); // Ajustado para clones
            dots.forEach(dot => dot.classList.remove('active'));
            if (dots[activeDotIndex]) {
                dots[activeDotIndex].classList.add('active');
            }
        }

        // Función para el siguiente slide
        function autoSlide() {
            const slides = Array.from(carouselTrack.children);
            const totalSlides = slides.length / 2; // Número de slides originales
            
            slideIndex++;
            goToSlide(slideIndex);

            // Resetear al inicio sin animación si llega al final de los clones
            if (slideIndex >= totalSlides) {
                setTimeout(() => {
                    slideIndex = 0;
                    goToSlide(slideIndex, false);
                }, 800); // 800ms = duración de la transición en el CSS
            }
        }
        
        // Crear los puntos de navegación
        const dots = [];
        const originalSlidesCount = slides.length;
        for(let i = 0; i < originalSlidesCount; i++) {
            const dot = document.createElement('span');
            dot.classList.add('dot');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => {
                slideIndex = i;
                goToSlide(slideIndex);
                resetAutoSlide(); // Reinicia el timer si se hace clic manual
            });
            dotsContainer.appendChild(dot);
            dots.push(dot);
        }

        // Iniciar el auto-deslizamiento
        intervalId = setInterval(autoSlide, 5000); // Cambia cada 5 segundos

        // Reiniciar el auto-deslizamiento
        function resetAutoSlide() {
            clearInterval(intervalId);
            intervalId = setInterval(autoSlide, 5000);
        }

        // Ajustar el carrusel si la ventana cambia de tamaño
        window.addEventListener('resize', () => {
            goToSlide(slideIndex, false); // Sin animación al reajustar
        });
    }

    // Mejorar la navegación por anclas (solo en visita/tienda.blade.php)
    const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');
    if (navLinks.length > 0) {
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    const headerHeight = document.querySelector('.site-header').offsetHeight;
                    const targetPosition = targetSection.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Actualizar URL sin recargar la página
                    history.pushState(null, null, this.getAttribute('href'));
                }
            });
        });

        // Resaltar sección activa en la navegación al hacer scroll
        const sections = document.querySelectorAll('.category-section');
        const navLinksObserver = document.querySelectorAll('.nav-links a[href^="#"]');
        
        function highlightActiveSection() {
            const scrollPosition = window.scrollY + 150;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    navLinksObserver.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + sectionId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }
        
        window.addEventListener('scroll', highlightActiveSection);
        highlightActiveSection(); // Llamar al cargar la página
    }
});
