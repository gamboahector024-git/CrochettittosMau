<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administrador') - Crochettittos</title>

    {{-- Fuentes Google (Poppins y Playfair) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Estilos CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=15">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Loader CSS (si lo estás usando en admin también) --}}
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body>
    {{-- Loader --}}
    <div id="loading-overlay">
        <div class="spinner"></div>
    </div>

    {{-- Sidebar de Navegación --}}
    <div class="sidebar">
        <h2>Crochettittos</h2>

        <nav>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                Gestionar Productos
            </a>

            <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Gestionar Usuarios
            </a>

            <a href="{{ route('admin.pedidos.index') }}" class="{{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                Ver Pedidos
            </a>
            
            <a href="{{ route('admin.peticiones.index') }}" class="{{ request()->routeIs('admin.peticiones.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
                Buzón de Peticiones
            </a>

            <a href="{{ route('admin.promociones.index') }}" class="{{ request()->routeIs('admin.promociones.*') ? 'active' : '' }}">
                <i class="fas fa-tag"></i>
                Promociones
            </a>

            <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i>
                FAQs
            </a>

            <a href="{{ route('admin.carrusel.index') }}" class="{{ request()->routeIs('admin.carrusel.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                Gestionar Carrusel
            </a>

            <div style="margin-top: auto; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 10px;">
                <a href="{{ url('/') }}" target="_blank">
                    <i class="fas fa-store"></i>
                    Ver Tienda
                </a>
                
                <a href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </a>
            </div>
        </nav>
    </div>

    {{-- Contenido Principal --}}
    <div class="main-content">
        @yield('content')
    </div>
    
    {{-- Scripts --}}
    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('loading-overlay');
            if(loader) loader.style.display = 'none';
        });
    </script>

    @yield('scripts')
</body>
</html>