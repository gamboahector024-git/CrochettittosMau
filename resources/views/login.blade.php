<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Crochettittos</title>
    
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v=11"> 
</head>
<body>

    <header class="site-header">
        <nav class="navbar">
            <h1><a href="{{ url('/') }}">Crochettittos</a></h1>
            
            <div class="nav-links">
                {{-- Aquí estaban "Llaveros" y "Flores" --}}
            </div>

            <div class="nav-auth">
                <a href="{{ route('registro.form') }}" class="nav-button nav-button-pastel-primary">Registrarse</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h2>Iniciar Sesión</h2>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
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
        © 2025 Crochettitos. Todos los derechos reservados.
    </footer>

</body>
</html>