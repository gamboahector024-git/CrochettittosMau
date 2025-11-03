@extends('layouts.cliente')

@section('title', 'Tienda - Crochettittos')

@section('content')
<div class="container">
    <div class="welcome-message">
        <h2>¡Bienvenido a Crochettittos!</h2>
        <p>
            En nuestra tienda encontrarás un universo de creaciones tejidas con amor y dedicación.
            Desde llaveros adorables y flores únicas, hasta piezas personalizadas para ti o tus seres queridos.  
            Aquí hay un poquito de todo... pero siempre hecho con mucho corazón.
        </p>
    </div>

    <section class="image-carousel">
        <div class="carousel-track">
            
            <div class="carousel-slide">
                {{-- 
                    INSTRUCCIONES PARA TU IMAGEN:
                    1. Mueve 'image_a2e101.jpg' a la carpeta 'public/img' (si no existe, créala).
                    2. Descomenta la línea de abajo y borra la línea de 'via.placeholder.com'.
                --}}
                {{-- <img src="{{ asset('img/image_a2e101.jpg') }}" alt="Amigurumi Especial"> --}}
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

        <main class="products-grid">
            @if($productos->count())
                @foreach($productos as $producto)
                    <div class="product-card">
                        <div class="card-image-wrapper">
                            <img src="{{ $producto->imagen_url ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto->nombre }}">
                        </div>
                        <div class="card-content">
                            <h3>{{ $producto->nombre }}</h3>
                            <p class="price">${{ number_format($producto->precio, 2) }}</p>
                            <button class="buy-button" onclick="openModal(
                                '{{ addslashes($producto->nombre) }}',
                                '{{ number_format($producto->precio, 2) }}',
                                '{{ addslashes($producto->descripcion) }}',
                                '{{ addslashes($producto->imagen_url ?? 'https://via.placeholder.com/250') }}'
                            )">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="no-products">No se encontraron productos con los filtros seleccionados.</p>
            @endif
        </main>
    </div>
</div>
@endsection

{{-- JAVASCRIPT EXCLUSIVO PARA ESTA PÁGINA (EL CARRUSEL) --}}
@push('scripts')
<script>
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
</script>
@endpush