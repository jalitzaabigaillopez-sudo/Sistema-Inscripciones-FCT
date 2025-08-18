<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Federación Costarricense de Taekwondo</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <!-- use bootstrap 5.3.7 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
      <div class="row w-90 w-md-75 align-items-center">

        <div class="col-md-6 text-center order-1 order-md-1">
  <!-- Logo -->
      <img src="images/fct_logo.jpg" alt="FCT Logo" class="logo img-fluid mb-4 mb-md-0 ">
</div>    
   <!-- Formulario de login -->
    <div class="col-md-4 order-2 order-md-2">
            <div class="login-box">
                <form action="{{route('login.process')}}" method="post">
                @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Correo</label>
                        <input type="email" class="form-control" name="email" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="password" class=" form-label fw-bold">Contraseña</label>
                        <input type="password" class="form-control" name="password" placeholder="">
                    </div>
                    <div class="mb-3">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#correoModal">Olvidó su contraseña</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="correoModal" tabindex="-1" aria-labelledby="correoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Encabezado -->
      <div class="modal-header">
        <h5 class="modal-title" id="correoModalLabel">Recuperar contraseña</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Formulario -->
      <form action="{{ route('correo.cambiarContraseña') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="correoInput" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control @error('correoInput') is-invalid @enderror" id="correoInput" name="correoInput" placeholder="ejemplo@correo.com" required aria-describedby="correoHelp">
            <div id="correoHelp" class="form-text">Ingrese el correo asociado a su cuenta.</div>
            @error('correoInput')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Pie -->
        <div class="modal-footer d-flex flex-column flex-sm-row justify-content-between">
          <button type="button" class="btn btn-outline-secondary w-100 w-sm-auto mb-2 mb-sm-0" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary w-100 w-sm-auto">Enviar</button>
        </div>
      </form>

    </div>
  </div>
</div>

    
<!-- use bootstrap 5.3.7 --> 
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>