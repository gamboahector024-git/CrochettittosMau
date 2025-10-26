<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crochettitos - Tienda</title>
    <link rel="stylesheet" href="{{ asset('css/tienda.css') }}">
</head>
<body>

    <nav class="navbar">
        <h1><a href="{{ url('/') }}">Crochettitos</a></h1>
        <div class="nav-links">
            <a href="#llaveros">Llaveros</a>
            <a href="#flores">Flores</a>
            <a href="#personalizados">Personalizados</a>
            
            @if(session('id_usuario'))
                <span class="nav-greeting">Hola, {{ session('nombre_usuario') }}!</span>
                <a href="{{ route('logout') }}">Cerrar Sesión</a>
            @else
                <a href="{{ route('login.form') }}">Iniciar Sesión</a>
                <a href="{{ route('registro.form') }}">Registrarse</a>
            @endif
        </div>
    </nav>

    <div class="container">
        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="welcome-message">
            <h2>Bienvenido a nuestra tienda de crochet</h2>
            <p>Explora nuestros productos hechos a mano con amor</p>
        </div>

        <section id="llaveros" class="category-section">
            <h2>Llaveros</h2>
            <div class="product-grid">
                @if(isset($productos_llaveros) && count($productos_llaveros) > 0)
                    @foreach($productos_llaveros as $producto)
                        <div class="product-card">
                            <img src="{{ $producto['imagen_url'] ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto['nombre'] }}">
                            <h3>{{ $producto['nombre'] }}</h3>
                            <p class="price">${{ number_format($producto['precio'], 2) }}</p>
                            <button class="buy-button" onclick="openModal(
                                '{{ addslashes($producto['nombre']) }}',
                                '{{ number_format($producto['precio'], 2) }}',
                                '{{ addslashes($producto['descripcion']) }}',
                                '{{ addslashes($producto['imagen_url'] ?? 'https://via.placeholder.com/250') }}'
                            )">Ver Detalles</button>
                        </div>
                    @endforeach
                @else
                    <p class="no-products">Próximamente tendremos llaveros disponibles</p>
                @endif
            </div>
        </section>

        <section id="flores" class="category-section">
            <h2>Flores</h2>
            <div class="product-grid">
                @if(isset($productos_flores) && count($productos_flores) > 0)
                    @foreach($productos_flores as $producto)
                        <div class="product-card">
                            <img src="{{ $producto['imagen_url'] ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto['nombre'] }}">
                            <h3>{{ $producto['nombre'] }}</h3>
                            <p class="price">${{ number_format($producto['precio'], 2) }}</p>
                            <button class="buy-button" onclick="openModal(
                                '{{ addslashes($producto['nombre']) }}',
                                '{{ number_format($producto['precio'], 2) }}',
                                '{{ addslashes($producto['descripcion']) }}',
                                '{{ addslashes($producto['imagen_url'] ?? 'https://via.placeholder.com/250') }}'
                            )">Ver Detalles</button>
                        </div>
                    @endforeach
                @else
                    <p class="no-products">Próximamente tendremos flores disponibles</p>
                @endif
            </div>
        </section>

        <section id="personalizados" class="category-section">
            <h2>Personalizados</h2>
            <div class="product-grid">
                @if(isset($productos_personalizados) && count($productos_personalizados) > 0)
                    @foreach($productos_personalizados as $producto)
                        <div class="product-card">
                            <img src="{{ $producto['imagen_url'] ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto['nombre'] }}">
                            <h3>{{ $producto['nombre'] }}</h3>
                            <p class="price">${{ number_format($producto['precio'], 2) }}</p>
                            <button class="buy-button" onclick="openModal(
                                '{{ addslashes($producto['nombre']) }}',
                                '{{ number_format($producto['precio'], 2) }}',
                                '{{ addslashes($producto['descripcion']) }}',
                                '{{ addslashes($producto['imagen_url'] ?? 'https://via.placeholder.com/250') }}'
                            )">Ver Detalles</button>
                        </div>
                    @endforeach
                @else
                    <p class="no-products">Próximamente tendremos productos personalizados</p>
                @endif
            </div>
        </section>

        
    </div>

    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <img id="modalImage" src="" alt="Imagen del Producto" class="modal-image">
            <h2 id="modalName"></h2>
            <p id="modalPrice" class="modal-price"></p>
            <p id="modalDescription" class="modal-description"></p>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>