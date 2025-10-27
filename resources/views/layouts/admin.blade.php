<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel de Administrador')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>

        <!-- Navegación principal -->
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <!-- Productos -->
        <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
            Gestionar Productos
        </a>

        <!-- Usuarios -->
        <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
            Gestionar Usuarios
        </a>

        <!-- Pedidos -->
        <a href="{{ route('admin.pedidos.index') }}" class="{{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
            Ver Pedidos
        </a>

        {{-- En la sección de navegación --}}
        <li class="nav-item">
            <a href="{{ route('admin.peticiones.index') }}" 
                class="nav-link {{ request()->routeIs('admin.peticiones.*') ? 'active' : '' }}">
                <i class="fas fa-inbox"></i> Buzón de Peticiones
                @if($countPendientes = App\Models\Peticion::where('estado', 'pendiente')->count())
                    <span class="badge bg-danger float-end">{{ $countPendientes }}</span>
                @endif
            </a>
        </li>

        <!-- 🔹 NUEVO: Gestión de Promociones -->
        <a href="{{ route('admin.promociones.index') }}" class="{{ request()->routeIs('admin.promociones.*') ? 'active' : '' }}">
            Promociones
        </a>

        <!-- Enlaces generales -->
        <a href="{{ url('/') }}">Ver Tienda</a>
        <a href="{{ url('/logout') }}">Cerrar Sesión</a>
    </div>

    <div class="main-content">
        <h1>@yield('header', 'Panel de Administrador')</h1>
        @yield('content')
    </div>
    
    @yield('scripts')

</body>
</html>
