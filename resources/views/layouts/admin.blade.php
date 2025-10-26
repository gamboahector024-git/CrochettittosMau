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
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">Gestionar Productos</a>
        <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">Gestionar Usuarios</a>
        <a href="{{ route('admin.pedidos.index') }}" class="{{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">Ver Pedidos</a>
        <a href="{{ url('/') }}">Ver Tienda</a>
        <a href="{{ url('/logout') }}">Cerrar Sesión</a>
    </div>

    <div class="main-content">
        <h1>@yield('header', 'Panel de Administrador')</h1>
        @yield('content')
    </div>
</body>
</html>
