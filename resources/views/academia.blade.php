<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Federación Costarricense de Taekwondo')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="https://via.placeholder.com/32?text=FCT">


    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{ asset('css/alertasInscripciones.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --color-primary: #222A59;
            --color-hover: #2e386e;
            --text-light: #ffffff;
            --sidebar-width: 270px;
        }

        /* Body */
        body {
            overflow-x: hidden;
            font-family: 'Roboto', sans-serif;
        }

        .submenu a.submenu-toggle span {
            font-size: 0.9rem;
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
            display: flex;
            flex-direction: column;
        }

        .sidebar.sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-header {
            flex-shrink: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background-color: var(--color-primary);
            z-index: 1041;
        }

        .sidebar .logo-container {
            text-align: center;
            padding: 1rem;
        }

        .sidebar .logo-container img {
            width: 150px;
            height: auto;
            object-fit: contain;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 1rem;
        }

        .sidebar a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--text-light);
            text-decoration: none;
            font-size: 1rem;
            transition: background 0.2s ease-in-out;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover {
            background-color: rgba(80, 150, 220, 0.25);
            border-left-color: rgba(120, 180, 255, 0.8);
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

        /* Submenu - Estilos mejorados */
        .submenu {
            margin-top: 1rem;
            position: relative;
        }

        .submenu::before {
            content: "";
            display: block;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.15) 50%, transparent 100%);
            margin: 0 1rem 0.75rem 1rem;
        }

        .submenu a.submenu-toggle {
            display: flex;
            align-items: center;
            padding: 0.85rem 1rem;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            background-color: rgba(80, 130, 200, 0.15);
            border-left: 3px solid rgba(100, 160, 240, 0.6);
            margin: 0 0.5rem;
            border-radius: 4px 0 0 4px;
            font-weight: 600;
            position: relative;
        }

        .submenu a.submenu-toggle:hover {
            background-color: rgba(80, 150, 220, 0.25);
            border-left-color: rgba(120, 180, 255, 0.8);
        }

        .submenu a.submenu-toggle i:last-child {
            transition: transform 0.3s ease;
            margin-left: auto;
        }

        .submenu a.submenu-toggle.active i:last-child {
            transform: rotate(180deg);
        }

        .submenu-items {
            background-color: rgba(40, 70, 120, 0.1);
            border-left: 3px solid rgba(70, 120, 200, 0.4);
            margin: 0.25rem 0.5rem 0.5rem 1.5rem;
            border-radius: 0 0 0 4px;
            overflow: hidden;
            transition: all 0.3s ease;
            max-height: 300px;
            overflow-y: auto;
        }

        /* Scrollbar personalizado para el submenú */
        .submenu-items::-webkit-scrollbar {
            width: 6px;
        }

        .submenu-items::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .submenu-items::-webkit-scrollbar-thumb {
            background: rgba(100, 160, 240, 0.5);
            border-radius: 3px;
        }

        .submenu-items::-webkit-scrollbar-thumb:hover {
            background: rgba(120, 180, 255, 0.7);
        }

        .submenu-items a {
            display: block;
            padding: 0.65rem 1.5rem;
            font-weight: 500;
            color: hsl(0, 0%, 90%);
            transition: all 0.2s ease-in-out;
            border-left: none;
            position: relative;
            padding-left: 2.5rem;
            margin-left: 0.5rem;
            border-radius: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            padding-left: 2rem;
            /* antes 2.5rem */
            margin-left: 0.25rem;
            /* antes 0.5rem */
        }

        .submenu-items a:hover {
            background-color: rgba(70, 130, 210, 0.2);
            transform: translateX(3px);
        }

        .submenu-items a::before {
            content: "›";
            position: absolute;
            left: 1rem;
            color: rgba(150, 200, 255, 0.7);
            font-weight: bold;
            transition: transform 0.2s ease;
        }

        .submenu-items a:hover::before {
            transform: translateX(3px);
            color: rgba(180, 255, 205, 0.9);
        }

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

        /* Scrollbar personalizado para el sidebar */
        .sidebar-content::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: rgba(201, 56, 56, 0.984);
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

            .sidebar.sidebar-hidden~.content-wrapper {
                margin-left: 0;
            }

            .sidebar .logo-container img {
                width: 120px;
            }

            .submenu-items {
                max-height: 200px;
            }
        }

        /* Breadcrumb */
        .breadcrumb-item+.breadcrumb-item::before {
            color: var(--text-light);
        }
    </style>
</head>

<body>
    <input type="hidden" id="idAcademia" value="{{ $academia['id_academia'] }}">

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="logo-container">
       <img src="{{ asset('images/vectlogo.png') }}" alt="FCT Logo" width="120" height="auto" loading="eager" style="max-width:100%;">
    </div>
    <i class="bi bi-x sidebar-close" id="sidebarClose"></i>
    <a href="{{ route('dashboard.academias') }}" onclick="handleSidebarClick(event)"><i class="bi bi-speedometer2"></i> Dashboard</a>

    <!-- Submenú de Inscripciones a eventos -->
    <div class="submenu">
    <a class="submenu-toggle text-white d-flex align-items-center justify-content-between" href="#" id="inscripcionesToggle">
        <span><i class="bi bi-folder2-open me-2"></i>Inscripciones a eventos</span>
        <i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <div class="submenu-items d-none" id="inscripcionesItems">
        <a href="{{ route('academia.inscripcionEvento') }}"  onclick="handleSidebarClick(event)"><i class="bi bi-calendar-plus me-2"></i>Nueva inscripción</a>
        <a href="{{ route('academia.misInscripciones') }}" onclick="handleSidebarClick(event)"><i class="bi bi-list-check me-2"></i> Mis Inscripciones</a>
    </div>
</div>
    <!-- Fin del Submenú -->
    <a href="{{ route('registro-atletas.index') }}" onclick="handleSidebarClick(event)"><i class="bi bi-people"></i> Gestión de atletas</a>
    <a href="{{ route('academia.perfil-academia') }}" onclick="handleSidebarClick(event)"><i class="bi bi-person-badge"></i> Administración de Perfil</a>
    <a href="#"><i class="bi bi-bar-chart-line"></i> Avance de eventos</a>
    <a href="#"><i class="bi bi-graph-up"></i> Estadística atletas/eventos</a>
    <a href="#" ><i class="bi bi-bar-chart-line me-2"></i><span>Reportes</span></a>
</nav>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex align-items-center">
            <i class="bi bi-list hamburger me-3" id="toggleSidebar" style="cursor:pointer;"></i>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="flex-grow-1">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a class=" text-white text-decoration-none" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page">
                        @yield('breadcrumb-title', '')
                    </li>
                </ol>
            </nav>
            <div class="ms-auto"></div>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-person-circle user-icon" style="color: #f1f1f3; font-size: 1.5rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('perfil') }}">Perfil</a></li>
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


    <!-- Modal de alerta -->
    <div id="customAlertOverlay" class="overlay">
        <div class="custom-alert">
            <div class="custom-alert-header">
                <span class="custom-alert-icon">⚠️</span>
                <h3 id="customAlertTitle">Alerta</h3>
            </div>
            <div class="custom-alert-body">
                <p id="customAlertMessage"></p>
            </div>
            <div class="custom-alert-footer">
                <button id="btnCerrarAlerta">Entendido</button>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/ripple.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    <script>
        // Función para guardar el estado del submenú
        function guardarEstadoSubmenu(abierto) {
            localStorage.setItem('submenuInscripcionesAbierto', abierto ? 'true' : 'false');
        }

        // Función para cargar el estado del submenú
        function cargarEstadoSubmenu() {
            const estado = localStorage.getItem('submenuInscripcionesAbierto');
            return estado === 'true';
        }

        // Función para guardar el estado del submenú de análisis
        function guardarEstadoAnalisis(abierto) {
            localStorage.setItem('submenuAnalisisAbierto', abierto ? 'true' : 'false');
        }

        // Función para cargar el estado del submenú de análisis
        function cargarEstadoAnalisis() {
            const estado = localStorage.getItem('submenuAnalisisAbierto');
            return estado === 'true';
        }

        document.addEventListener('DOMContentLoaded', function () {

            // GUARDAR ESTADO DE SUBMENÚ SIN IMPORTAR SI SE REFRESCA

            // Configuración del submenú de Inscripciones
            const inscripcionesToggle = document.getElementById('inscripcionesToggle');
            const inscripcionesItems = document.getElementById('inscripcionesItems');

            // Cargar estado guardado al iniciar
            const estadoInicial = cargarEstadoSubmenu();
            if (estadoInicial) {
                inscripcionesItems.classList.remove('d-none');
                inscripcionesToggle.classList.add('active');
            }

            inscripcionesToggle.addEventListener('click', function (e) {
                e.preventDefault();
                const estaAbierto = !inscripcionesItems.classList.contains('d-none');

                if (estaAbierto) {
                    inscripcionesItems.classList.add('d-none');
                    inscripcionesToggle.classList.remove('active');
                    guardarEstadoSubmenu(false);
                } else {
                    inscripcionesItems.classList.remove('d-none');
                    inscripcionesToggle.classList.add('active');
                    guardarEstadoSubmenu(true);
                }
            });

            // Configuración del submenú de Análisis y Reportes
            const analisisToggle = document.getElementById('analisisToggle');
            const analisisItems = document.getElementById('analisisItems');

            // Cargar estado guardado al iniciar
            const estadoAnalisis = cargarEstadoAnalisis();
            if (estadoAnalisis) {
                analisisItems.classList.remove('d-none');
                analisisToggle.classList.add('active');
            }

            analisisToggle.addEventListener('click', function (e) {
                e.preventDefault();
                const estaAbierto = !analisisItems.classList.contains('d-none');

                if (estaAbierto) {
                    analisisItems.classList.add('d-none');
                    analisisToggle.classList.remove('active');
                    guardarEstadoAnalisis(false);
                } else {
                    analisisItems.classList.remove('d-none');
                    analisisToggle.classList.add('active');
                    guardarEstadoAnalisis(true);
                }
            });


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
    </script>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

    <!-- Bootstrap-Select -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
    <!-- <script src="{{ asset('js/academiaMatricula/inscripcionesAcademiasOED.js') }}"></script> -->
    <script src="{{ asset('js/academiaMatricula/inscripcionesAcademias.js') }}"></script>
</body>

</html>