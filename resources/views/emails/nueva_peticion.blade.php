<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nueva Petición</title>
</head>
<body>
    <h2>Nueva petición recibida</h2>

    <p>Se ha recibido una nueva petición desde el sitio.</p>

    <p><strong>ID:</strong> #{{ $peticion->id_peticion }}</p>
    <p><strong>Usuario:</strong> {{ $usuario->nombre }} ({{ $usuario->email }})</p>
    <p><strong>Título:</strong> {{ $peticion->titulo }}</p>
    <p><strong>Descripción:</strong></p>
    <p>{{ $peticion->descripcion }}</p>

    @if(!empty($peticion->imagen_referencia))
        <p><strong>Imagen de referencia:</strong></p>
        <p><a href="{{ asset($peticion->imagen_referencia) }}">Ver imagen</a></p>
    @endif

    <p>Puedes ver y gestionar la petición desde el panel de administración.</p>

    <p>Saludos,<br>Tu sitio Crochettittos</p>
</body>
</html>