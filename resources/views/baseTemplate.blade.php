<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Federación Costarricense de Taekwondo<')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- use bootstrap 5.3.7 -->
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>

    {{-- Top bar --}}
    <div class="topbar">
        <div class="topbar-logo">Federación Costarricense de Taekwondo</div>
        <div class="d-flex gap-2">
            <form action="{{ route('logout.process') }}" method="POST">
                @csrf
            <button type="submit" class="dropdown-item">Cerrar Sesión</button>
            </form>
            <h5>|</h5>
            <a href="" class="dropdown-item">Ver perfil</a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <nav class="col-md-2 sidebar">
                <a href="#">Inicio</a>
                <a href="#">Usuarios</a>
                <a href="#">Administradores</a>
                <a href="#">Academias</a>
                <hr>
                <a href="#">Módulos</a>
                <a href="#">Inscripción</a>
                <a href="#">Revisión de resultados</a>
                <a href="#">Eventos</a>
                <hr>
                <a href="#">Estadísticas</a>
                <a href="#">Verificación de Peso</a>
                <a href="#">Seguridad</a>
                <a href="#">Generación de llaves</a>
                <a href="#">Ranking nacional</a>
                <hr>
                <small class="text-muted">Log in as: Administrador</small>
            </nav>

            {{-- Main content --}}
            <main class="col-md-10 content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="footer">
        Copyright © FCT 2025
    </footer>

<!-- use bootstrap 5.3.7 --> 
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

</body>
</html>