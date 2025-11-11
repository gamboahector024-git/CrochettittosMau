<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel de Administrador')</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=12">

    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>

        <nav> {{-- Esta etiqueta faltaba --}}
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                Gestionar Productos
            </a>

            <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                Gestionar Usuarios
            </a>

            <a href="{{ route('admin.pedidos.index') }}" class="{{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                Ver Pedidos
            </a>
            
            {{-- La etiqueta </li> que estaba aquí fue eliminada --}}
            <a href="{{ route('admin.peticiones.index') }}" class="{{ request()->routeIs('admin.peticiones.*') ? 'active' : '' }}">
                Buzón de Peticiones
            </a>
            {{-- La etiqueta </li> que estaba aquí fue eliminada --}}

            <a href="{{ route('admin.promociones.index') }}" class="{{ request()->routeIs('admin.promociones.*') ? 'active' : '' }}">
                Promociones
            </a>

            <a href="{{ route('admin.carrusel.index') }}" class="{{ request()->routeIs('admin.carrusel.*') ? 'active' : '' }}">
                Gestionar Carrusel
            </a>

            <a href="{{ url('/') }}">Ver Tienda</a>
            <a href="{{ url('/logout') }}">Cerrar Sesión</a>
            {{-- La etiqueta </li> que estaba aquí fue eliminada --}}
        </nav> {{-- Esta etiqueta faltaba --}}
    </div>

    <div class="main-content">
        {{-- 
          Se eliminó la línea:
          <h1>@yield('header', 'Panel de Administrador')</h1>
          Ahora, solo se mostrará el <h1> de tu vista hija (ej. "Buzón de Peticiones")
        --}}
        @yield('content')
    </div>
    
    @yield('scripts')
</body>
</html>