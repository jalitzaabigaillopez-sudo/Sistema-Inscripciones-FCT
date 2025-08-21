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

               <div class="col-md-6">
                    <div class="login-box">
                        <form action="{{route('cambiar.contraseñaVencida')}}" method="post">
                        @csrf

                        <input type="hidden" name="id_usuario" value="{{$usuario->id_usuario }}">

                            <h3 class=" form-label fw-bold">Su contraseña ha vencido. Por razones de seguridad debe actualizarla cada <span>{{config('ConfiguracionFCT._vencimiento_contraseña')}}</span> días.</h3>
                            <p for="info" class=" form-label fw-bold">Su nueva contraseña debe contener más de 8 caracteres y  maximo 16 y no puede ser igual que la anterior.</p>
                            <hr>
                            <div class="mb-3">
                                <label for="nuevaContraseña" class=" form-label fw-bold">Nueva Contraseña</label>
                                <input type="nuevaContraseña" class="form-control" name="nuevaContraseña" value="{{ old('nuevaContraseña') }}" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="confirmarNuevaContraseña" class=" form-label fw-bold">Confirmar Nueva Contraseña</label>
                                <input type="confirmarNuevaContraseña" class="form-control" name="confirmarNuevaContraseña" placeholder="">
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