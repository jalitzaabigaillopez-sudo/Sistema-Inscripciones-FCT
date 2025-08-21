<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Dashboard Administrativo')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .sidebar { background-color: #0d6efd; min-height: 100vh; color: white; }
    .sidebar a { color: white; text-decoration: none; display: block; padding: 10px; }
    .sidebar a:hover { background-color: #0b5ed7; }
    .header { background-color: white; border-bottom: 1px solid #dee2e6; }
  </style>
</head>
<body>
  <div class="d-flex">
    {{-- Sidebar --}}
    <div class="sidebar p-3">
      {{-- Logo institucional --}}
      <div class="text-center mb-3">
        <img src="/images/vectlogo.png" alt="Logo Federación" class="img-fluid" style="max-height: 80px;">
      </div>

      <h4 class="mb-4">Catálogos Generales</h4>
      <a class="nav-link" href="{{ route('academias.index') }}">🏫 Academias</a>
      <a class="nav-link" href="{{ route('atletas.index') }}">🤸 Atletas</a>
      <a class="nav-link" href="{{ route('categorias.index') }}">📊 Categorías</a>
      <a class="nav-link" href="{{ route('torneos.index') }}">🏆 Torneos/Eventos</a>
      <a class="nav-link" href="{{ route('usuarios.index') }}">👤 Usuarios</a>
      <a class="nav-link" href="{{ route('pesos.index') }}">⚖️ Peso</a>
      <a class="nav-link" href="{{ route('seguridad.index') }}">🔐 Seguridad</a>
</ul>

</div>

    {{-- Main Content --}}
    <div class="flex-grow-1">
      {{-- Header --}}
      <div class="header d-flex justify-content-between align-items-center p-3">
        <h2 class="text-primary">Dashboard Administrativo</h2>
        <div class="dropdown">
          <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
            Perfil <i class="bi bi-person-circle ms-1"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('perfil') }}">Ajustes</a></li>
            <li><a class="dropdown-item text-danger" href="#">Cerrar sesión</a></li>
          </ul>
        </div>
      </div>

      {{-- Content --}}
      <main class="p-4">
        @yield('content')
      </main>

      {{-- Footer --}}
      <footer class="container-fluid bg-white border-top py-3">
        <div class="row justify-content-center text-center">
          <div class="col-12 col-md-auto">
            <small class="text-muted d-block">
              Creado por <strong>Federación Costarricense de Taekwondo</strong> © 2025
            </small>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
