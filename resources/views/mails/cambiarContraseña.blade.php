<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Sistema de cambio de contraseña de Federación Costarricense de Taekwondo</h1>
    <h2>Hola, {{ $usuario->nombre }}</h2>
    <p>Para continuar con el proceso de cambiar contraseña por favor haga clic en el siguiente enlace:</p>
    <a href="{{ $urlFirmada }}">Click para cambiar contraseña</a>
    <p>Debera ingresar con su correo electronico: <span style="font-weight: bold;">{{ $usuario->email }}</span> </p>
    <p>y su contraseña temporal: <span style="font-weight: bold;">{{ $usuario->password }}</span></p>

</body>
</html>