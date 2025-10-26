<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
</head>
<body>

    <nav class="navbar">
        <h1><a href="{{ url('/') }}">Mi Tienda</a></h1>
    </nav>

    <div class="container">
        <h2>Crear una Cuenta</h2>

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div style="color: #28a745; background: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Muestra errores de validación --}}
        @if ($errors->any())
            <div style="color: red; margin-bottom: 1rem;">
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
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
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

        <div class="register-link" style="text-align: center; margin-top: 1rem;">
            <p>¿Ya tienes una cuenta? <a href="{{ url('/login') }}">Inicia Sesión</a></p>
        </div>
    </div>

</body>
</html>
