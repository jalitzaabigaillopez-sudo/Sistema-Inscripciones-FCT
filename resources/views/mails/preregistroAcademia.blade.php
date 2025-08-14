<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Sistema de registro de Federación Costarricense de Taekwondo</h1>
    <h2>Hola, {{ $usuario->nombre }}</h2>
    <p>Para continuar con el proceso de registro por favor haga clic en el siguiente enlace:</p>
    <a href="{{ route('activar.cuenta', ['id' => $usuario->id_usuario]) }}">Click para registrarse en Federación Costarricense de Taekwondo</a>
    <p>Debera ingresar con su correo electronico: <span style="font-weight: bold;">{{ $usuario->email }}</span> </p>
    <p>y su contraseña temporal: <span style="font-weight: bold;">{{ $usuario->password }}</span></p>

</body>
</html>