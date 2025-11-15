<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Crochettittos')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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

    {{-- ======================================================== --}}
    {{-- ========= MODALES (PRODUCTO, LOGIN, PETICIÓN) ========= --}}
    {{-- ======================================================== --}}

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

    {{-- Modal para crear Petición personalizada (CÓDIGO CORREGIDO) --}}
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

    <script src="{{ asset('js/main.js') }}?v=13"></script>
    
    {{-- JS CORREGIDO para modal de peticiones --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Asignamos TODOS los botones con ese ID (el del header y el del perfil)
        var btns = document.querySelectorAll('#newPeticionButton'); 
        var modal = document.getElementById('peticionModal');
        var close = document.getElementById('closePeticionModal');
        var cancel = document.getElementById('cancelPeticion');

        function openModal(e) {
            e.preventDefault();
            if(modal) {
                // Usamos 'flex' para que el CSS pueda centrar el modal
                modal.style.display = 'flex'; 
            }
        }

        function closeModal() {
            if(modal) {
                modal.style.display = 'none';
            }
        }

        // Asignamos el evento a CADA botón encontrado
        if (btns.length > 0 && modal) {
            btns.forEach(function(btn) {
                btn.addEventListener('click', openModal);
            });
        }
        
        if (close) {
            close.addEventListener('click', closeModal);
        }
        if (cancel) {
            cancel.addEventListener('click', closeModal);
        }

        // Cerrar modal al click fuera del contenido
        window.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
            
            // Cierre para los otros modales (si usan la misma lógica)
            var loginModal = document.getElementById('loginModal');
            if (e.target === loginModal) {
                loginModal.style.display = 'none';
            }
            
            // (Añade aquí tu lógica para cerrar el productModal)
        });
    });
    </script>

    @stack('scripts')
</body>
</html>