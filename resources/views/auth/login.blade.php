<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Crochettittos</title>
    
    @vite('resources/css/login.css')
    
</head>
<body>

    <header class="site-header">
        <nav class="navbar">
            <h1><a href="{{ url('/') }}">Crochettittos</a></h1>
        </nav>
    </header>

    <div class="container">
        <h2>Iniciar Sesión</h2>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
                <div class="mt-2">
                    ¿No tienes cuenta? <a href="{{ route('registro.form') }}" class="font-bold">Regístrate aquí</a>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <strong>¡Ups! Algo salió mal.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('login.procesar') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="submit-btn">Iniciar Sesión</button>
        </form>

        <div class="register-link">
            <p>¿No tienes una cuenta? <a href="{{ route('registro.form') }}">Regístrate aquí</a></p>
        </div>
    </div>

    <footer class="site-footer">
        © 2025 Crochettittos. Todos los derechos reservados.
    </footer>

</body>
</html>