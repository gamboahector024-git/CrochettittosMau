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
            <ul class="nav-links">
                <li><a href="#llaveros">Llaveros</a></li>
                <li><a href="#flores">Flores</a></li>
                <li><a href="#personalizados">Personalizados</a></li>
                <li><a href="#novedades">Novedades</a></li>
                <li><a href="#colecciones">Colecciones</a></li>
            </ul>

            <div class="nav-auth">
                @auth
                    {{-- Si el usuario HA iniciado sesión --}}

                    <a href="{{ route('perfil.index') }}" class="welcome-user">
                        Hola, {{ Auth::user()->nombre }}
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
            <p id="modalPrice" class="modal-price"></p>
            <p id="modalDescription" class="modal-description"></p>
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

    <script src="{{ asset('js/main.js') }}"></script>
    
    @stack('scripts')
</body>
</html>