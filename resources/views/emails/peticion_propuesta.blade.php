<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propuesta para tu petición</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; background-color: #F3EEF1; font-family: 'Poppins', Helvetica, Arial, sans-serif; color: #5D4A56; line-height: 1.6;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F3EEF1; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05); border: 1px solid rgba(255, 255, 255, 0.6);">
                    <tr>
                        <td align="center" style="padding: 30px 20px; border-bottom: 2px solid #F3EEF1;">
                            <h1 style="color: #D56989; margin: 0; font-family: 'Playfair Display', serif; font-size: 28px; letter-spacing: 0.5px;">
                                Crochettittos
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 30px 10px 30px;">
                            <div style="background-color: #F1F6E6; border: 2px solid #C2DC80; border-radius: 16px; padding: 20px; text-align: left;">
                                <h2 style="color: #D56989; margin-top: 0; font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 10px;">
                                    ¡Tenemos una propuesta para ti! 🧶
                                </h2>
                                <p style="color: #5D4A56; font-size: 15px; line-height: 1.6; margin: 0;">
                                    Hola <strong>{{ $usuario->nombre }}</strong>,<br>
                                    ya revisamos tu petición personalizada y tenemos una propuesta lista para que la revises.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 30px;">
                            <h3 style="color: #4A3A44; font-family: 'Poppins', sans-serif; font-size: 17px; margin-bottom: 15px; border-bottom: 1px solid #EA9CAF; padding-bottom: 8px; display: inline-block;">
                                Detalles de tu petición
                            </h3>

                            <p style="margin: 0 0 8px 0; font-size: 14px; color: #7A6A74;">
                                <strong>ID:</strong> #{{ $peticion->id_peticion }}
                            </p>
                            <p style="margin: 0 0 8px 0; font-size: 14px; color: #7A6A74;">
                                <strong>Título:</strong> {{ $peticion->titulo }}
                            </p>
                            @if(!empty($peticion->categoria))
                                <p style="margin: 0 0 8px 0; font-size: 14px; color: #7A6A74;">
                                    <strong>Categoría:</strong> {{ $peticion->categoria->nombre }}
                                </p>
                            @endif
                            <p style="margin: 0 0 8px 0; font-size: 14px; color: #7A6A74;">
                                <strong>Cantidad:</strong> {{ $peticion->cantidad }} {{ $peticion->cantidad == 1 ? 'unidad' : 'unidades' }}
                            </p>

                            @if(!empty($peticion->respuesta_admin))
                                <div style="margin-top: 18px; padding: 16px; border-radius: 12px; background-color: #F8F6F8; border: 1px dashed #EA9CAF;">
                                    <p style="margin: 0 0 6px 0; font-size: 14px; color: #4A3A44; font-weight: 600;">
                                        Mensaje de la administradora:
                                    </p>
                                    <p style="margin: 0; font-size: 14px; color: #7A6A74; white-space: pre-line;">
                                        {{ $peticion->respuesta_admin }}
                                    </p>
                                </div>
                            @endif

                            @if(!empty($peticion->precio_propuesto))
                                <div style="margin-top: 18px; padding: 16px; border-radius: 12px; background-color: #FFF4F7; border: 1px solid #EA9CAF;">
                                    <p style="margin: 0 0 6px 0; font-size: 14px; color: #4A3A44; font-weight: 600;">
                                        Precio propuesto:
                                    </p>
                                    <p style="margin: 0; font-size: 20px; font-weight: 700; color: #D56989;">
                                        ${{ number_format($peticion->precio_propuesto, 2) }} MXN
                                    </p>
                                </div>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 20px 30px 30px 30px;">
                            <p style="margin: 0 0 15px 0; font-size: 14px; color: #7A6A74;">
                                Desde tu panel puedes <strong>aceptar y pagar</strong> la propuesta o <strong>rechazarla</strong> si no se ajusta a lo que necesitas.
                            </p>

                            <a href="{{ route('cliente.peticiones.show', $peticion->id_peticion) }}"
                               style="background-color: #D56989; color: #ffffff; text-decoration: none; padding: 14px 36px; border-radius: 30px; font-weight: 700; font-size: 15px; display: inline-block; box-shadow: 0 6px 15px rgba(213, 105, 137, 0.4); font-family: 'Poppins', sans-serif;">
                                Ver propuesta y elegir opción
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #F8F6F8; padding: 24px; border-top: 1px solid #F3EEF1;">
                            <p style="margin: 0 0 10px 0; color: #7A6A74; font-size: 13px;">
                                Si el botón no funciona, copia y pega este enlace en tu navegador:
                            </p>
                            <p style="margin: 0 0 15px 0; color: #9A8A94; font-size: 12px; word-break: break-all;">
                                {{ route('cliente.peticiones.show', $peticion->id_peticion) }}
                            </p>

                            <p style="margin: 0; font-size: 11px; color: #9A8A94; line-height: 1.5;">
                                © {{ date('Y') }} <strong>Crochettittos</strong> — Peticiones personalizadas.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
