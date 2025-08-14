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
    </div>


    <div class="container-fluid">
        <div class="row mt-3 mb-3">
            <!-- <div class="col-md-2">
                <nav class="sidebar" style="pointer-events: none; opacity: 0.5;">
                    <a href="#">Inicio</a>
                    <hr>
                    <a href="#">Módulos</a>
                    <a href="#">Inscripción</a>
                    <a href="#">Revisión de resultados</a>
                    <a href="#">Eventos</a>
                    <hr>
                    <a href="#">Estadísticas</a>
                    <a href="#">Verificación de Peso</a>
                    <a href="#">Generación de llaves</a>
                    <a href="#">Ranking nacional</a>
                    <hr>
                    <small class="text-muted">Log in as: Administrador</small>
                </nav> 
            </div> -->

               <div class="col-md-6">
                    <div class="login-box">
                        <form action="#" method="post">
                        @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Correo</label>
                                <input type="email" class="form-control" name="email" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="password" class=" form-label fw-bold">Contraseña Temporal</label>
                                <input type="password" class="form-control" name="password" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="password" class=" form-label fw-bold">Nueva Contraseña</label>
                                <input type="password" class="form-control" name="password" placeholder="">
                            </div>
                            <button type="submit" class="btn-ingresar">completar</button>
                        </form>
                    </div>
               </div> 

               <div class="col-md-6">

               </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="footer text-white text-center p-3 fixed-bottom"">
        Copyright © FCT 2025
    </footer>

<!-- use bootstrap 5.3.7 --> 
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

</body>
</html>