<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Carrito</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Consultas para móviles */
        @media only screen and (max-width: 480px) {
            .mobile-padding { padding: 20px !important; }
            .mobile-image { width: 80px !important; height: 80px !important; }
            .mobile-text { font-size: 14px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #F3EEF1; font-family: 'Poppins', Helvetica, Arial, sans-serif; color: #5D4A56; line-height: 1.6;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F3EEF1; padding: 40px 0;">
        <tr>
            <td align="center">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05); border: 1px solid rgba(255, 255, 255, 0.6);">
                    
                    <tr>
                        <td align="center" style="padding: 35px 20px; background-color: #ffffff; border-bottom: 2px solid #F3EEF1;">
                            <h1 style="color: #D56989; margin: 0; font-family: 'Playfair Display', serif; font-size: 32px; letter-spacing: 0.5px;">
                                Crochettittos
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 30px 10px 30px;">
                            <div style="background-color: #F1F6E6; border: 2px solid #C2DC80; border-radius: 16px; padding: 25px; text-align: center;">
                                
                                <h2 style="color: #D56989; margin-top: 0; font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 10px;">
                                    ¡Tus productos te esperan! 🧶
                                </h2>
                                
                                <p style="color: #5D4A56; font-size: 16px; line-height: 1.6; margin: 0;">
                                    Hola <strong>{{ $usuario->nombre }}</strong>,<br>
                                    Notamos que dejaste cositas hermosas en tu carrito. Recuerda que en <strong>Crochettittos</strong> cada pieza es única y hecha con mucho amor. ❤️
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 30px;">
                            <h3 style="color: #4A3A44; font-family: 'Poppins', sans-serif; font-size: 18px; margin-bottom: 15px; border-bottom: 1px solid #EA9CAF; padding-bottom: 10px; display: inline-block;">
                                Tu selección pendiente:
                            </h3>
                            
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                @foreach($productos as $detalle)
                                    @php
                                        $producto = $detalle->producto ?? null;
                                    @endphp

                                    @if(!$producto)
                                        @continue
                                    @endif

                                    <tr>
                                        <td style="padding: 15px 0; border-bottom: 1px dashed #EA9CAF;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    @if($producto->imagen_url)
                                                        <td width="80" style="padding-right: 15px;">
                                                            @php
                                                                $imagenPath = public_path($producto->imagen_url);
                                                            @endphp
                                                            <img src="{{ isset($message) ? $message->embed($imagenPath) : asset($producto->imagen_url) }}" 
                                                                 alt="Producto" width="80" height="80" 
                                                                 style="border-radius: 12px; display: block; object-fit: cover; border: 1px solid #F3EEF1;">
                                                        </td>
                                                    @endif
                                                    <td valign="middle">
                                                        <span style="display: block; font-size: 16px; color: #4A3A44; font-weight: 700; margin-bottom: 4px;">
                                                            {{ $producto->nombre }}
                                                        </span>
                                                        <span style="font-size: 14px; color: #7A6A74;">
                                                            Cantidad: {{ $detalle->cantidad }}
                                                        </span>
                                                    </td>
                                                    <td align="right" valign="middle">
                                                        <span style="color: #D56989; font-weight: 700; font-size: 18px;">
                                                            ${{ number_format($producto->precio, 2) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 20px 30px 40px 30px;">
                            <a href="{{ route('carrito.index') }}" 
                               style="background-color: #D56989; color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 30px; font-weight: 700; font-size: 16px; display: inline-block; box-shadow: 0 6px 15px rgba(213, 105, 137, 0.4); font-family: 'Poppins', sans-serif;">
                                🎀 Continuar con mi compra
                            </a>
                            <p style="margin-top: 25px; font-size: 13px; color: #7A6A74;">
                                🔒 Tu carrito se guarda automáticamente por 7 días.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #F8F6F8; padding: 30px; border-top: 1px solid #F3EEF1;">
                            <p style="margin: 0 0 15px 0; color: #7A6A74; font-size: 14px;">
                                Síguenos en nuestras redes 👇
                            </p>
                            
                            <div style="margin-bottom: 20px;">
                                <a href="https://www.instagram.com/crochettittos?igsh=MWZhZHp2dnV3bmlkbw==" target="_blank" style="color: #D56989; text-decoration: none; margin: 0 10px; font-weight: 700;">Instagram</a>
                                <span style="color: #EA9CAF;">|</span>
                                <a href="https://www.facebook.com/share/17dCteCFLo/?mibextid=wwXIfr" target="_blank" style="color: #D56989; text-decoration: none; margin: 0 10px; font-weight: 700;">Facebook</a>
                            </div>

                            <p style="margin: 0; font-size: 12px; color: #9A8A94; line-height: 1.5;">
                                © {{ date('Y') }} <strong>Crochettittos</strong>.<br>
                                Hecho a mano con amor en Chiapas.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>