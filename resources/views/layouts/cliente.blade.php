<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Crochettittos')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="{{ asset('css/tienda.css') }}?v=15">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body>
    <div id="loading-overlay">
        <div class="loading-logo">Crochettittos</div>
        <div class="crochet-spinner">
            <div class="yarn-ball"></div>
            <div class="crochet-hook">
                <div class="hook-handle"></div>
            </div>
            <div class="stitch"></div>
            <div class="stitch"></div>
            <div class="stitch"></div>
        </div>
        <div class="loading-text">
            Tejiendo momentos especiales<span class="loading-dots"></span>
        </div>
        <div class="craft-message">
            Cada producto está hecho a mano con amor y dedicación
        </div>
        <!-- Mantener el spinner original como respaldo -->
        <div class="spinner"></div>
    </div>

    <header class="site-header">
        <nav class="navbar">
            <h1><a href="{{ url('/') }}">Crochettittos</a></h1>
            
            <div class="nav-auth">
                <a href="{{ route('faq') }}" class="nav-button nav-button-pastel-secondary">
                    <i class="fas fa-question-circle"></i> Preguntas Frecuentes
                </a>
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
        <p>&copy; {{ date('Y') }} Crochettittos. Todos los derechos reservados.</p>
        <div class="social-links">
            <a href="https://www.facebook.com/Crochettittos" target="_blank" rel="noopener noreferrer" aria-label="Visita nuestra página de Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com/Crochettittos" target="_blank" rel="noopener noreferrer" aria-label="Visita nuestra página de Instagram">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
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
        <div class="modal-content" style="display: block; max-width: 700px; max-height: 90vh; overflow-y: auto;">
            <button class="close-modal" id="closePeticionModal">&times;</button>
            <div class="modal-details" style="padding: 2.5rem;">
                <h2 id="modalName">Enviar Petición Personalizada</h2>
                <form action="{{ route('peticiones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h3>Detalles del Producto</h3>
                    
                    <div class="form-group">
                        <label for="titulo">Título *</label>
                        <input id="titulo" name="titulo" type="text" class="form-input" required maxlength="255" placeholder="Ej: Amigurumi personalizado de mi mascota">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_categoria">Categoría *</label>
                            <select id="id_categoria" name="id_categoria" class="form-input" required>
                                <option value="">Selecciona una categoría</option>
                                @php
                                    $categorias = \App\Models\Categoria::orderBy('nombre')->get();
                                @endphp
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad *</label>
                            <input id="cantidad" name="cantidad" type="number" class="form-input" required min="1" max="100" value="1" placeholder="1">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion">Descripción *</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="form-input" required placeholder="Describe lo que necesitas: colores, tamaño, detalles especiales..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="imagen_referencia">Imagen de referencia (opcional)</label>
                        <input id="imagen_referencia" name="imagen_referencia" type="file" accept="image/*" class="form-input-file">
                    </div>

                    <h3>Dirección de Entrega</h3>
                    
                    <div class="form-group">
                        <label for="calle">Calle y número *</label>
                        <input id="calle" name="calle" type="text" class="form-input" required maxlength="255" placeholder="Ej: Av. Juárez 123">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="colonia">Colonia *</label>
                            <input id="colonia" name="colonia" type="text" class="form-input" required maxlength="255" placeholder="Ej: Centro">
                        </div>
                        
                        <div class="form-group">
                            <label for="codigo_postal">Código Postal *</label>
                            <input id="codigo_postal" name="codigo_postal" type="text" class="form-input" required maxlength="10" placeholder="Ej: 44100">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="municipio_ciudad">Municipio/Ciudad *</label>
                            <input id="municipio_ciudad" name="municipio_ciudad" type="text" class="form-input" required maxlength="255" placeholder="Ej: Guadalajara">
                        </div>
                        
                        <div class="form-group">
                            <label for="estado_direccion">Estado *</label>
                            <input id="estado_direccion" name="estado_direccion" type="text" class="form-input" required maxlength="100" placeholder="Ej: Jalisco">
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="submit" class="primary-button">Enviar Petición</button>
                        <button type="button" class="tertiary-button" id="cancelPeticion">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}?v=15"></script>
    <script src="{{ asset('js/cliente/peticion-modal.js') }}"></script>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('loading-overlay').style.display = 'none';
        });
    </script>

    @stack('scripts')
</body>
</html>