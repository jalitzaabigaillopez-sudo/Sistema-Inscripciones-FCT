<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Sistema de cambio de contraseña de Federación Costarricense de Taekwondo</h1>
    <h2>Hola, {{ $usuario->nombre_completo }}</h2>
    <p>Para continuar con el proceso de cambiar su contraseña por favor haga clic en el siguiente enlace:</p>
    <a href="{{ $url }}">Click para cambiar contraseña</a>
    <p>Debera ingresar con su correo electronico: <span style="font-weight: bold;">{{ $usuario->email }}</span> </p>
    <p>y su contraseña temporal: <span style="font-weight: bold;">{{ $contraseñaTemporal->password_temporal }}</span></p>
    <p>Debera realizar este proceso antes de <span style="font-weight: bold;"> {{ $contraseñaTemporal->fecha_expiracion }} </span>o su contraseña temporal caducara</p> 

</body>
</html>