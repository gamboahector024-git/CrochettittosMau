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

    {{-- Estilos --}}
    {{-- Asegúrate de que admin.css tenga los estilos nuevos que agregamos hoy --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Estilos extra por vista --}}
    @stack('styles')
</head>
<body>
    
    {{-- Sidebar (Menú Lateral) --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Crochettitos</h2>
            <div class="sidebar-subtitle">Panel de Administración</div>
        </div>
        
        {{-- Navegación Principal --}}
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
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
            
            {{-- Enlace externo a la tienda --}}
            <a href="{{ url('/') }}" target="_blank" class="store-link">
                <i class="fas fa-store"></i>
                Ver Tienda
            </a>
        </nav>

        {{-- Footer del Sidebar: Aquí va el botón de Cerrar Sesión separado --}}
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenido Principal --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Botón Menú Móvil --}}
    <button class="mobile-menu-toggle" style="display: none;">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Scripts Globales --}}
    <script>
        // Toggle Menú Móvil
        const mobileBtn = document.querySelector('.mobile-menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        
        if(mobileBtn) {
            mobileBtn.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
            });
        }

        // Auto-ocultar alertas después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>