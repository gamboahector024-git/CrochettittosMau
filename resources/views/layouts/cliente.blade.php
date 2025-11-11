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
                    <a href="{{ route('cliente.peticiones.index') }}" class="nav-button nav-button-pastel-secondary">
                        <i class="fas fa-inbox"></i> Mis Peticiones
                    </a>
                    <a href="#" id="newPeticionButton" class="nav-button nav-button-pastel-primary">
                        <i class="fas fa-envelope-open-text"></i> Nueva Petición
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
        {{-- Mensajes flash simples para success/error --}}
        @if(session('success'))
            <div class="flash-message flash-success" role="alert" style="margin:10px;padding:10px;border:1px solid #c6f6d5;background:#e6ffed;color:#1a7f37;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-message flash-error" role="alert" style="margin:10px;padding:10px;border:1px solid #fed7d7;background:#fff5f5;color:#9b2c2c;">
                {{ session('error') }}
            </div>
        @endif

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

    {{-- Modal para crear Petición personalizada --}}
    <div id="peticionModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-button" id="closePeticionModal">&times;</span>
            <h3>Enviar Petición Personalizada</h3>

            <form action="{{ route('peticiones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input id="titulo" name="titulo" type="text" class="form-control" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen_referencia">Imagen de referencia (opcional)</label>
                    <input id="imagen_referencia" name="imagen_referencia" type="file" accept="image/*" class="form-control">
                </div>

                <div style="margin-top:12px;">
                    <button type="submit" class="nav-button nav-button-pastel-primary">Enviar Petición</button>
                    <button type="button" class="nav-button nav-button-pastel-secondary" id="cancelPeticion">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}?v=13"></script>
    
    <script>
        // JS mínimo para abrir/cerrar modal de peticiones
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('newPeticionButton');
            var modal = document.getElementById('peticionModal');
            var close = document.getElementById('closePeticionModal');
            var cancel = document.getElementById('cancelPeticion');

            if (btn && modal) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    modal.style.display = 'block';
                });
            }
            if (close) {
                close.addEventListener('click', function () { modal.style.display = 'none'; });
            }
            if (cancel) {
                cancel.addEventListener('click', function () { modal.style.display = 'none'; });
            }

            // Cerrar modal al click fuera del contenido
            window.addEventListener('click', function (e) {
                if (e.target === modal) modal.style.display = 'none';
            });
        });
    </script>

    @stack('scripts')
</body>
</html>