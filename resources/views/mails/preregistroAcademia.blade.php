<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Registro de Academia - Federación Costarricense de Taekwondo</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #222A59;
            padding: 20px;
            text-align: center;
            color: white;
        }

        .email-header img {
            max-height: 70px;
            margin-bottom: 10px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .email-body {
            padding: 30px;
            text-align: left;
            background-color: #fff;
        }

        .email-body h2 {
            color: #222A59;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .email-body p {
            line-height: 1.6;
            font-size: 15px;
            color: #444;
            margin-bottom: 15px;
        }

        .email-body a.button {
            display: inline-block;
            background-color: #222A59;
            color: #ffffff !important;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .email-body a.button:hover {
            background-color: #2f3f90;
        }

        .highlight {
            font-weight: bold;
            color: #111;
        }

        .email-footer {
            background-color: #f0f0f0;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #666;
        }

        .email-footer a {
            color: #222A59;
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 600px) {
            .email-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Encabezado -->
        <div class="email-header">
            <img src="{{ $message->embed(public_path('images/LogoFCT_transpa.png')) }}" alt="Logo FCT">
            <h1>Federación Costarricense de Taekwondo</h1>
        </div>

        <!-- Cuerpo -->
        <div class="email-body">
            <h2>Estimado(a) {{ $usuario->nombre_completo }},</h2>

            <p>La <span class="highlight">Federación Costarricense de Taekwondo</span> ha registrado su academia en el
                sistema oficial de gestión de academias FCT.</p>

            <p>Para completar su registro y activar su cuenta como encargado, por favor haga clic en el siguiente botón:
            </p>

            <p style="text-align:center;">
                <a href="{{ $url }}" class="button">Completar Registro de Academia</a>
            </p>

            <p style="font-size: 13px; text-align:center; color: #666;">
                Si el botón no funciona, copie y pegue este enlace en su navegador:<br>
                <a href="{{ $url }}" style="color:#222A59;">{{ $url }}</a> *CAMBIAR URL DESPUÉS*
            </p>

            <p>Deberá usar las siguientes credenciales:</p>
            <p>
                Correo electrónico: <span class="highlight">{{ $usuario->email }}</span><br>
                Contraseña temporal: <span class="highlight">{{ $contraseñaTemporal->password_temporal }}</span>
            </p>

            <p>Este enlace y la contraseña temporal estarán disponibles hasta el:
                <span
                    class="highlight">{{ \Carbon\Carbon::parse($contraseñaTemporal->fecha_expiracion)->format('d/m/Y H:i') }}</span>.
            </p>

            <p>Una vez completado el registro, podrá acceder al panel administrativo de la academia y actualizar la
                información correspondiente.</p>

            <p>Si usted no reconoce esta solicitud, puede ignorar este mensaje sin problema.</p>

            <p>Atentamente,<br>
                <strong>Federación Costarricense de Taekwondo</strong>
            </p>
        </div>

        <!-- Pie -->
        <div class="email-footer">
            &copy; {{ date('Y') }} Federación Costarricense de Taekwondo. Todos los derechos reservados.<br>
            <a href="https://www.FCT.com" target="_blank">**www.FCT.com**</a>
        </div>
    </div>
</body>

</html>
