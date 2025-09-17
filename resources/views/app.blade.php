<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('tituloArriba')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Federación Costarricense de Taekwondo')</title>
    <link rel="icon" type="image/x-icon" href="https://via.placeholder.com/32?text=FCT">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
  --color-primary: #222A59;
  --color-hover: #2e386e;
  --text-light: #ffffff;
  --sidebar-width: 250px;
}

/* Body */
body {
  overflow-x: hidden;
  font-family: 'Roboto', sans-serif;
}

/* Navbar */
.navbar {
  background-color: var(--color-primary);
  padding: 0.5rem 1rem;
  position: fixed;
  top: 0;
  left: var(--sidebar-width);
  width: calc(100% - var(--sidebar-width));
  z-index: 1030;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: left 0.3s ease-in-out, width 0.3s ease-in-out;
}

.navbar-full {
  left: 0;
  width: 100%;
}

.navbar-text,
.user-icon,
.hamburger {
  color: var(--text-light);
  font-size: 1.5rem;
  cursor: pointer;
}

/* Sidebar */
.sidebar {
  width: var(--sidebar-width);
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  background-color: var(--color-primary);
  z-index: 1000;
  transform: translateX(0);
  transition: transform 0.3s ease;
  will-change: transform;
}

.sidebar.sidebar-hidden {
  transform: translateX(-100%);
}

.sidebar .logo-container {
  text-align: center;
  padding: 1rem;
  background-color: var(--color-primary);
  z-index: 1041;
}

.sidebar .logo-container img {
  width: 150px;
  height: auto;
  object-fit: contain;
}

.sidebar a {
  display: block;
  padding: 0.75rem 1.5rem;
  color: var(--text-light);
  text-decoration: none;
  font-size: 1rem;
  transition: background 0.2s ease-in-out;
}

.sidebar a:hover {
  background-color: var(--color-hover);
}

/* Content */
.content-wrapper {
  margin-top: 60px;
  margin-left: var(--sidebar-width);
  padding: 1rem;
  transition: margin-left 0.3s ease-in-out;
}

.content-wrapper-full {
  margin-left: 0;
}

/* Submenu */
.submenu a.submenu-toggle {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 1rem;
  font-weight: 600;
  color: var(--text-light);
  cursor: pointer;
  transition: background 0.2s ease-in-out;
}

.submenu a.submenu-toggle:hover {
  background-color: var(--color-hover);
}

.submenu-items a {
  display: block;
  padding: 0.5rem 1.5rem;
  font-weight: 500;
  color: hsl(0, 0%, 100%);
  transition: background 0.2s ease-in-out
}

.submenu-items a:hover {
  background-color: var(--color-hover);
}

/* Close Button */
.sidebar-close {
  display: none;
  color: var(--text-light);
  font-size: 1.5rem;
  cursor: pointer;
  position: absolute;
  top: 1rem;
  right: 1rem;
  z-index: 1050;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
    z-index: 1040;
  }

  .sidebar.sidebar-open {
    transform: translateX(0);
    background-color: var(--color-primary);
    z-index: 1040;
  }

  .sidebar-close {
    display: block;
  }

  .navbar {
    left: 0;
    width: 100%;
  }

  .content-wrapper {
    margin-left: 0;
  }

  .sidebar.sidebar-hidden ~ .content-wrapper {
    margin-left: 0;
  }

  .sidebar .logo-container img {
    width: 120px;
  }
}

/* Breadcrumb */
.breadcrumb-item + .breadcrumb-item::before {
  color: var(--text-light);
}
</style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="logo-container">
           <img src="{{ asset('images/vectlogo.png') }}" alt="FCT Logo" width="120" height="auto" loading="eager" style="max-width:100%;">
        </div>
        <i class="bi bi-x sidebar-close" id="sidebarClose"></i>
        <a href="{{ route('adminDash') }}" onclick="handleSidebarClick(event)">
            <i class="bi bi-speedometer2 me-2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('perfil') }}" onclick="handleSidebarClick(event)"> <i class="bi bi-person-badge me-2"></i>Perfil Administrador</a>
        <a href="{{ route('atletas.index') }}" onclick="handleSidebarClick(event)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" class="bi bi-person-walking me-2" viewBox="0 0 16 16">
                <path
                    d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z" />
                <path
                    d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.531-.22Z" />
            </svg>
            <span>Atletas</span>
        </a>
        <a href="{{ route('academias.index') }}" onclick="handleSidebarClick(event)">
            <i class="bi bi-layers me-2"></i>
            <span>Academias</span>
        </a>
        <a href="{{ route('inscripciones.index') }}" onclick="handleSidebarClick(event)">
            <i class="bi bi-ui-checks me-2"></i>
            <span>Inscripciones</span>
        </a>
        <a href="{{ route('calendario') }}" onclick="handleSidebarClick(event)">
            <i class="bi bi-calendar me-2"></i>
            <span>Calendario</span>
        </a>

<!-- Submenú de Catálogos Generales -->
<div class="submenu">
    <a class="submenu-toggle text-white d-flex align-items-center justify-content-between" href="#"  id="catalogosToggle">
        <span><i class="bi bi-folder2-open me-2"></i> Catálogos Generales</span>
        <i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <div class="submenu-items d-none" id="catalogosItems">
        <a href="{{ route('usuarios.index') }}" onclick="handleSidebarClick(event)" class="text-white">
            <i class="bi bi-people me-2"></i> Usuarios
        </a>
        <a href="{{ route('modalidades.index') }}" onclick="handleSidebarClick(event)" class="text-white">
            <i class="bi bi-columns-gap me-2"></i> Modalidades
        </a>
        <a href="{{ route('grados.index') }}" onclick="handleSidebarClick(event)" class="text-white">
            <i class="bi bi-card-heading me-2"></i> Grados
        </a>
        <a href="{{ route('eventos.index') }}" onclick="handleSidebarClick(event)" class="text-white">
            <i class="bi bi-calendar3 me-2"></i> Eventos
        </a>
        <a href="{{ route('categorias.index') }}" onclick="handleSidebarClick(event)" class="text-white">
            <i class="bi bi-bookmarks me-2"></i> Categorías
        </a>
    </div>
</div>
 </nav>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <i class="bi bi-list hamburger" id="toggleSidebar"></i>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a class="fw-bold text-white" href="{{ route('adminDash') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">
                        @yield('breadcrumb-title', '')
                    </li>
                </ol>
            </nav>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-person-circle user-icon" style="color: #f1f1f3; font-size: 1.5rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <form action="{{ route('logout.process') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>



    <!-- Main Content -->
    <div class="content-wrapper" id="contentWrapper">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script>
    document.getElementById('catalogosToggle').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('catalogosItems').classList.toggle('d-none');
    });
    </script>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/ripple.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const contentWrapper = document.getElementById('contentWrapper');
    const navbar = document.querySelector('.navbar');

    function setContentMargin() {
        if (sidebar.classList.contains('sidebar-hidden')) {
            contentWrapper.classList.add('content-wrapper-full');
            navbar.classList.add('navbar-full');
        } else {
            contentWrapper.classList.remove('content-wrapper-full');
            navbar.classList.remove('navbar-full');
        }
    }

    // Sidebar SIEMPRE visible en escritorio al cargar
    function adjustSidebarOnLoad() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('sidebar-hidden');
            setContentMargin();
        }
    }

    toggleSidebar.addEventListener('click', function () {
        // En móvil, abre sidebar con clase sidebar-open
        if (window.innerWidth <= 768) {
            sidebar.classList.add('sidebar-open');
            sidebar.classList.remove('sidebar-hidden');
        } else {
            sidebar.classList.toggle('sidebar-hidden');
            setContentMargin();
        }
    });

    sidebarClose.addEventListener('click', function () {
        sidebar.classList.add('sidebar-hidden');
        sidebar.classList.remove('sidebar-open');
        setContentMargin();
    });

    window.addEventListener('resize', adjustSidebarOnLoad);
    adjustSidebarOnLoad();

    // Cerrar sidebar si se hace clic fuera (solo en móviles)
    document.addEventListener('click', function (e) {
        if (
            window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !toggleSidebar.contains(e.target)
        ) {
            sidebar.classList.remove('sidebar-open');
            sidebar.classList.add('sidebar-hidden');
        }
    });
});

        $(document).ready(function() {
            $('#tabla').DataTable({
                "ordering": false, // Desactiva el ordenamiento automático para todas las columnas
                "language": {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    }
                }
            });
        });
    </script>
</body>

</html>
