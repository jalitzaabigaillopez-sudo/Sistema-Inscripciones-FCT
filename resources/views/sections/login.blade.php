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
    <div class="row w-75 align-items-center">
        
        <div class="col-md-6 text-center">
            <img src="images/fct_logo.jpg" alt="FCT Logo" class="logo img-fluid">
        </div>
        
        <div class="col-md-6">
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
                    <button type="submit" class="btn-ingresar">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
  <div class="modal fade" id="correoModal" tabindex="-1" aria-labelledby="correoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <!-- Encabezado -->
        <div class="modal-header">
          <h5 class="modal-title" id="correoModalLabel">Ingresar correo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        
        <!-- Cuerpo -->
    <form action="{{route('correo.cambiarContraseña')}}" method="post">
        @csrf
        <div class="modal-body">
          
            <div class="mb-3">
              <label for="correoInput" class="form-label">Correo electrónico de su cuenta</label>
              <input type="email" class="form-control" id="correoInput" name="correoInput" placeholder="ejemplo@correo.com" required>
            </div>
        </div>
        
        <!-- Pie -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Enviar</button>
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