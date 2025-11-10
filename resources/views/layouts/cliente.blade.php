<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Crochettittos')</title>
    
    <link rel="stylesheet" href="{{ asset('css/tienda.css') }}?v=12">
</head>
<body>

    <header class="site-header">
        <nav class="navbar">
            <h1><a href="{{ url('/') }}">Crochettittos</a></h1>
            
            <div class="nav-auth">
                @auth
                    {{-- Si el usuario HA iniciado sesión --}}
                    <a href="{{ route('perfil.index') }}" class="welcome-user">
                        <i class="fas fa-user"></i> Mi Cuenta
                    </a>
                    <a href="{{ route('cliente.pedidos.index') }}" class="nav-button nav-button-pastel-secondary">
                        <i class="fas fa-box"></i> Mis Pedidos
                    </a>
                    <a href="{{ route('carrito.index') }}" class="nav-button nav-button-pastel-primary">
                        <i class="fas fa-shopping-cart"></i> Carrito
                    </a>
                    <a href="{{ route('logout') }}" class="nav-button nav-button-pastel-secondary">Cerrar Sesión</a>

                @else
                    {{-- Si el usuario NO ha iniciado sesión (es invitado) --}}
                    <a href="{{ route('login.form') }}" class="nav-button nav-button-pastel-secondary">Iniciar Sesión</a>
                    <a href="{{ route('registro.form') }}" class="nav-button nav-button-pastel-primary">Registrarse</a>
                
                @endauth
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <p>&copy; {{ date('Y') }} Crochettitos. Todos los derechos reservados.</p>
    </footer>

    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <img id="modalImage" src="" alt="Imagen del Producto" class="modal-image">
            <h2 id="modalName"></h2>
            
            <div class="modal-price-row">
                <span id="modalPrice" class="modal-price"></span>
                <span id="modalOriginalPrice" class="modal-price-original"></span>
                <span id="modalDiscountBadge" class="modal-discount-badge"></span>
            </div>

            <p id="modalCategory" class="modal-category"></p>
            <p id="modalDescription" class="modal-description"></p>

            <form action="{{ route('carrito.store') }}" method="POST" class="modal-form">
                @csrf
                <input type="hidden" name="id_producto" id="modalProductId">
                
                <div class="quantity-selector">
                    <label for="modalQuantity">Cantidad:</label>
                    <input type="number" id="modalQuantity" name="cantidad" value="1" min="1" class="quantity-input">
                </div>

                <button type="submit" class="add-to-cart-button">Agregar al Carrito</button>
            </form>
        </div>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="document.getElementById('loginModal').style.display='none'">&times;</span>
            <h3>Inicia Sesión</h3>
            <p>Para ver los detalles del producto y realizar compras, necesitas iniciar sesión.</p>
            <a href="{{ route('login.form') }}" class="nav-button nav-button-pastel-secondary">Iniciar Sesión</a>
            <a href="{{ route('registro.form') }}" class="nav-button nav-button-pastel-primary">Registrarse</a>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}?v=13"></script>
    
    @stack('scripts')
</body>
</html>