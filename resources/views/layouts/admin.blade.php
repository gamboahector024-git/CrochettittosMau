<!DOCTYPE html>
<html lang="es" data-theme="{{ Cookie::get('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel de Administrador')</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=12">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('styles')
</head>
<body>
    <!-- Toggle del modo oscuro -->
    <div class="theme-toggle">
        <form action="{{ route('admin.theme.toggle') }}" method="POST" class="toggle-form">
            @csrf
            <button type="submit" class="toggle-btn">
                <i class="fas fa-sun toggle-icon sun-icon"></i>
                <i class="fas fa-moon toggle-icon moon-icon"></i>
                <span class="theme-text">
                    {{ Cookie::get('theme', 'light') === 'dark' ? 'Modo Claro' : 'Modo Oscuro' }}
                </span>
            </button>
        </form>
    </div>

    <div class="sidebar">
        <h2>Admin Panel</h2>

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

            <a href="{{ route('admin.carrusel.index') }}" class="{{ request()->routeIs('admin.carrusel.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                Gestionar Carrusel
            </a>

            <a href="{{ url('/') }}">
                <i class="fas fa-store"></i>
                Ver Tienda
            </a>
            
            <a href="{{ url('/logout') }}">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </nav>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
    
    <script src="{{ asset('js/admin/theme.js') }}"></script>

    @yield('scripts')
</body>
</html>