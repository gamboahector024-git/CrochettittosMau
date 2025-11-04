<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de carrito</title>
    <style>
        .container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; color: #333; }
        .header { background: #A77BFF; color: #fff; padding: 16px; text-align: center; }
        .content { padding: 16px; }
        .product { display: flex; gap: 12px; border-bottom: 1px solid #eee; padding: 12px 0; }
        .product img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
        .product h4 { margin: 0 0 4px 0; }
        .footer { text-align: center; padding: 16px; color: #666; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 16px; background: #A77BFF; color: #fff !important; text-decoration: none; border-radius: 6px; margin-top: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>¡Tienes productos esperando en tu carrito!</h2>
    </div>
    <div class="content">
        <p>Hola {{ $usuario->nombre }},</p>
        <p>Notamos que dejaste algunos productos en tu carrito hace un rato. Aquí tienes un recordatorio por si deseas completar tu compra:</p>

        @foreach($detalles as $detalle)
            <div class="product">
                <img src="{{ $detalle->producto->imagen_url ?? 'https://via.placeholder.com/80' }}" alt="{{ $detalle->producto->nombre }}">
                <div>
                    <h4>{{ $detalle->producto->nombre }}</h4>
                    <div>
                        Cantidad: {{ $detalle->cantidad }}
                    </div>
                    <div>
                        @php
                            $precio = $detalle->producto->promocionActiva
                                ? $detalle->producto->precio * (1 - $detalle->producto->promocionActiva->descuento/100)
                                : $detalle->producto->precio;
                        @endphp
                        Precio: ${{ number_format($precio, 2) }}
                    </div>
                </div>
            </div>
        @endforeach

        <p style="text-align: center;">
            <a href="{{ route('carrito.index') }}" class="btn">Volver al carrito</a>
        </p>
    </div>

    <div class="footer">
        Crochettittos · Este es un mensaje automático. Si ya realizaste tu compra, ignora este correo.
    </div>
</div>
</body>
</html>
