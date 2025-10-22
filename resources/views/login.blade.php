<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<nav class="navbar">
    <h1><a href="{{ url('/') }}">Crochettitos</a></h1>
</nav>

<div class="container">
    <h2>Iniciar Sesión</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
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
        <button type="submit">Entrar</button>
    </form>

    <div class="register-link">
        <p>¿No tienes cuenta? <a href="{{ route('registro.form') }}">Regístrate aquí</a></p>
    </div>
</div>

</body>
</html>
