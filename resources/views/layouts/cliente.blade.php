<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Crochettitos')</title>
    <link rel="stylesheet" href="{{ asset('css/tienda.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Barra de navegación específica para clientes -->
    <nav class="navbar">
        <h1><a href="{{ url('/') }}">Crochettitos</a></h1>
        <div class="nav-links">
            @auth
                <span class="nav-greeting">Hola, {{ auth()->user()->nombre }}!</span>
                <a href="{{ route('perfil.index') }}">Mi Perfil</a>
                <a href="{{ route('logout') }}">Cerrar Sesión</a>
            @else
                <a href="{{ route('login.form') }}">Iniciar Sesión</a>
                <a href="{{ route('registro.form') }}">Registrarse</a>
            @endauth
        </div>
    </nav>

    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer opcional -->
    <footer class="footer">
        <p>© {{ date('Y') }} Crochettitos. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts comunes -->
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>