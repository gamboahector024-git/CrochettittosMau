@extends('layouts.cliente')

@section('title', 'Tienda - Crochettittos')

@section('content')
<div class="container">
    <div class="welcome-message">
        <h2>¡Bienvenido a Crochettittos! 🧶</h2>
        <p>
            En nuestra tienda encontrarás un universo de creaciones tejidas con amor y dedicación.
            Desde llaveros adorables y flores únicas, hasta piezas personalizadas para ti o tus seres queridos.  
            Aquí hay un poquito de todo... pero siempre hecho con mucho corazón 💖.
        </p>
    </div>

    <section class="image-carousel">
        <div class="carousel-track">
            
            <div class="carousel-slide">
                <img src="https://via.placeholder.com/1200x400/A77BFF/FFFFFF?text=Tu+Imagen+Principal+Aqu%C3%AD" alt="Imagen de Bienvenida 1">
            </div>
            
            <div class="carousel-slide">
                <img src="https://via.placeholder.com/1200x400/5B8CFF/FFFFFF?text=Novedades" alt="Novedades">
            </div>
            
            <div class="carousel-slide">
                <img src="https://via.placeholder.com/1200x400/AEE6ED/FFFFFF?text=Hecho+a+Mano" alt="Hecho a Mano">
            </div>

        </div>
        <div class="carousel-dots"></div>
    </section>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="search-container">
        <form action="{{ route('tienda') }}" method="GET">
            <input type="text" name="busqueda" placeholder="Buscar productos..." value="{{ request('busqueda') }}">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <div class="shop-layout">
        <aside class="filters-sidebar">
            <h3>Filtrar por</h3>

            <form action="{{ route('tienda') }}" method="GET">
                <div class="filter-group">
                    <h4>Categorías</h4>
                    @foreach($categorias as $categoria)
                        <label>
                            <input type="checkbox" name="categoria" value="{{ $categoria->nombre }}"
                                {{ request('categoria') == $categoria->nombre ? 'checked' : '' }}>
                            {{ $categoria->nombre }}
                        </label>
                    @endforeach
                </div>

                <div class="filter-group">
                    <h4>Rango de precios</h4>
                    <div class="price-range">
                        <input type="number" name="precio_min" placeholder="Mínimo" value="{{ request('precio_min') }}">
                        <span>a</span>
                        <input type="number" name="precio_max" placeholder="Máximo" value="{{ request('precio_max') }}">
                    </div>
                </div>

                <button type="submit" class="apply-filters">Aplicar Filtros</button>
            </form>
        </aside>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const carouselTrack = document.querySelector('.carousel-track');
        
        if (carouselTrack) {
            const slides = Array.from(carouselTrack.children);
            const dotsContainer = document.querySelector('.carousel-dots');
            let slideIndex = 0;
            let intervalId;

            if (slides.length === 0) return;
            
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

                if (slideIndex >= totalSlides) {
                    setTimeout(() => {
                        slideIndex = 0;
                        goToSlide(slideIndex, false);
                    }, 800);
                }
            }
            
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

            let intervalId = setInterval(autoSlide, 5000);

            function resetAutoSlide() {
                clearInterval(intervalId);
                intervalId = setInterval(autoSlide, 5000);
            }

            window.addEventListener('resize', () => {
                goToSlide(slideIndex, false);
            });
        }
    });
</script>
@endpush
