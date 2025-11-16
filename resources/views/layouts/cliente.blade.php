<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Crochettittos')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="{{ asset('css/tienda.css') }}?v=15">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="site-header">
        <nav class="navbar">
            <h1><a href="{{ url('/') }}">Crochettittos</a></h1>
            
            <div class="nav-auth">
                @auth
                    {{-- Si el usuario HA iniciado sesión --}}
                    <a href="{{ route('perfil.index') }}" class="welcome-user">
                        <i class="fas fa-user"></i> {{ Auth::user()->nombre }}
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
                        @php
                            $carritoCount = 0;
                            if (Auth::check()) {
                                $carrito = \App\Models\Carrito::where('id_usuario', Auth::user()->id_usuario)->first();
                                $carritoCount = $carrito ? $carrito->detalles->sum('cantidad') : 0;
                            }
                        @endphp
                        @if($carritoCount > 0)
                            <span class="cart-counter">{{ $carritoCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('logout') }}" class="nav-button nav-button-pastel-secondary">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>

                @else
                    {{-- Si el usuario NO ha iniciado sesión (es invitado) --}}
                    <a href="{{ route('login.form') }}" class="nav-button nav-button-pastel-secondary">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                    <a href="{{ route('registro.form') }}" class="nav-button nav-button-pastel-primary">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </a>
                
                @endauth
            </div>
        </nav>
    </header>

    <main>
        {{-- Contenedor para mensajes flash --}}
        <div class="flash-container">
            @if(session('success'))
                <div class="flash-message flash-success" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flash-message flash-error" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="site-footer">
        <p>&copy; {{ date('Y') }} Crochettitos. Todos los derechos reservados.</p>
    </footer>

    {{-- MODALES --}}
    
    {{-- Modal de Producto --}}
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            
            <div class="modal-image">
                <img id="modalImage" src="" alt="Imagen del Producto">
            </div>
            
            <div class="modal-details">
                <h2 id="modalName"></h2>
                
                <div class="modal-price-row">
                    <span id="modalPrice" class="modal-price"></span>
                    <span id="modalOriginalPrice" class="modal-price-original"></span>
                    <span id="modalDiscountBadge" class="modal-discount-badge"></span>
                </div>
                
                <p id="modalDescription" class="modal-description"></p>
                
                <div class="modal-info-section">
                    <span id="modalCategory" class="modal-category"></span>
                    
                    <div class="quantity-selector-modal">
                        <label for="modalQuantity">Cantidad:</label>
                        <input type="number" id="modalQuantity" name="cantidad" value="1" min="1" class="quantity-input-modal">
                    </div>
                </div>
                
                <form action="{{ route('carrito.store') }}" method="POST" class="modal-form">
                    @csrf
                    <input type="hidden" name="id_producto" id="modalProductId">
                    <button type="submit" class="add-to-cart-button">
                        <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal de Login --}}
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="document.getElementById('loginModal').style.display='none'">&times;</span>
            <h3>Inicia Sesión</h3>
            <p>Para ver los detalles del producto y realizar compras, necesitas iniciar sesión.</p>
            <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1.5rem;">
                <a href="{{ route('login.form') }}" class="nav-button nav-button-pastel-secondary">Iniciar Sesión</a>
                <a href="{{ route('registro.form') }}" class="nav-button nav-button-pastel-primary">Registrarse</a>
            </div>
        </div>
    </div>

    {{-- Modal de Petición --}}
    <div id="peticionModal" class="modal" style="display:none;">
        <div class="modal-content" style="display: block; max-width: 600px;">
            <button class="close-modal" id="closePeticionModal">&times;</button>
            <div class="modal-details" style="padding: 2.5rem;">
                <h2 id="modalName">Enviar Petición Personalizada</h2>
                <form action="{{ route('peticiones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input id="titulo" name="titulo" type="text" class="form-input" required maxlength="255">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="form-input" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="imagen_referencia">Imagen de referencia (opcional)</label>
                        <input id="imagen_referencia" name="imagen_referencia" type="file" accept="image/*" class="form-input-file">
                    </div>
                    <div class="modal-actions" style="flex-direction: row; gap: 1rem; margin-top: 1.5rem;">
                        <button type="submit" class="primary-button" style="flex: 1;">Enviar Petición</button>
                        <button type="button" class="tertiary-button" id="cancelPeticion" style="flex: 1;">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}?v=15"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var btns = document.querySelectorAll('#newPeticionButton'); 
        var modal = document.getElementById('peticionModal');
        var close = document.getElementById('closePeticionModal');
        var cancel = document.getElementById('cancelPeticion');

        function openModal(e) {
            e.preventDefault();
            if(modal) { modal.style.display = 'flex'; }
        }
        function closeModal() {
            if(modal) { modal.style.display = 'none'; }
        }

        if (btns.length > 0 && modal) {
            btns.forEach(function(btn) {
                btn.addEventListener('click', openModal);
            });
        }
        if (close) { close.addEventListener('click', closeModal); }
        if (cancel) { cancel.addEventListener('click', closeModal); }
        window.addEventListener('click', function (e) {
            if (e.target === modal) { closeModal(); }
            var loginModal = document.getElementById('loginModal');
            if (e.target === loginModal) { loginModal.style.display = 'none'; }
        });
    });
    </script>

    @stack('scripts')
</body>
</html>