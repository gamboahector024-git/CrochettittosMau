<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear una Cuenta - Crochettittos</title>
    
    @vite(['resources/css/registro.css', 'resources/js/auth/password-toggle.js'])

    {{-- Font Awesome para los iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>

    <div class="container">
        <h2>Crear una Cuenta</h2>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <strong>¡Ups! Algo salió mal al crear tu cuenta.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('registro.guardar') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" required class="password-input">
                    <button type="button" class="toggle-password-btn" data-target="password" title="Mostrar/Ocultar contraseña">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña:</label>
                <div class="password-field">
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="password-input">
                    <button type="button" class="toggle-password-btn" data-target="password_confirmation" title="Mostrar/Ocultar contraseña">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <textarea id="direccion" name="direccion" rows="3">{{ old('direccion') }}</textarea>
            </div>

            <button type="submit" class="submit-btn">Registrarse</button>
        </form>

        <div class="register-link">
            <p>¿Ya tienes una cuenta? <a href="{{ url('/login') }}">Inicia Sesión</a></p>
        </div>
    </div>

    <footer class="site-footer">
        2025 Crochettittos. Todos los derechos reservados.
    </footer>

</body>
</html>