<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administración') - Crochettitos</title>

    {{-- Fuentes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- CSS Principal --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
    
    @stack('styles')
</head>
<body>
    
    {{-- Botón Menú Móvil (Ahora visible/invisible por CSS) --}}
    <button class="mobile-menu-toggle" id="btn-toggle">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Crochettitos</h2>
            <div class="sidebar-subtitle">Panel de Administración</div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Productos
            </a>
            <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Usuarios
            </a>
            <a href="{{ route('admin.pedidos.index') }}" class="{{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Pedidos
            </a>
            <a href="{{ route('admin.peticiones.index') }}" class="{{ request()->routeIs('admin.peticiones.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Buzón
            </a>
            <a href="{{ route('admin.promociones.index') }}" class="{{ request()->routeIs('admin.promociones.*') ? 'active' : '' }}">
                <i class="fas fa-tag"></i> Promociones
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i> FAQs
            </a>
            <a href="{{ route('admin.carrusel.index') }}" class="{{ request()->routeIs('admin.carrusel.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Carrusel
            </a>
            
            <a href="{{ url('/') }}" target="_blank" class="store-link">
                <i class="fas fa-store"></i> Ver Tienda
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenido Principal --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Scripts: Aquí conectamos el JS externo --}}
    <script src="{{ asset('js/admin/theme.js') }}"></script>
    
    @stack('scripts')
</body>
</html>