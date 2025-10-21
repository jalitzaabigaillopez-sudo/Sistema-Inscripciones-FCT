<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Federación Costarricense de Taekwondo')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/LogoFCT_transpa.png') }}">
    <link rel="stylesheet" href="{{ asset('css/alertasInscripciones.css') }}">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* Estilos existentes mejorados */
        :root {
            --color-primary: #222A59;
            --color-hover: #2e386e;
            --text-light: #ffffff;
            --sidebar-width: 270px;
        }

        body {
            overflow-x: hidden;
            font-family: 'Roboto', sans-serif;
        }

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

        /* Sidebar con scroll */
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

        .content-wrapper {
            margin-top: 60px;
            margin-left: var(--sidebar-width);
            padding: 1rem;
            transition: margin-left 0.3s ease-in-out;
        }

        .content-wrapper-full {
            margin-left: 0;
        }

        /* Submenu - Estilos mejorados con scroll interno si es necesario */
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
            /* font-weight: 600; */
            font-size: 15px;
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
            max-height: 600px;
            /* Altura máxima antes de mostrar scroll */
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
        }

        .submenu-items a:hover {
            background-color: rgba(70, 130, 210, 0.2);
            transform: translateX(3px);
        }

        .submenu-items a::before {
            content: "›";
            position: absolute;
            left: 1.5rem;
            color: rgba(150, 200, 255, 0.7);
            font-weight: bold;
            transition: transform 0.2s ease;
        }

        .submenu-items a:hover::before {
            transform: translateX(3px);
            color: rgba(180, 220, 255, 0.9);
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
            background: rgba(255, 255, 255, 0.5);
        }

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
                /* Menor altura máxima en móviles */
            }
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: var(--text-light);
        }

        .dataTables_scrollHeadInner,
        .dataTables_scrollHeadInner table {
            width: 100% !important;
        }

        .table-responsive {
            overflow-y: hidden;
        }

        #userDropdown::after {
            border-top-color: #fff !important;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <img src="{{ asset('images/vectlogo.png') }}" alt="FCT Logo" width="120" height="auto"
                    loading="eager" style="max-width:100%;">
            </div>
            <i class="bi bi-x sidebar-close" id="sidebarClose"></i>
        </div>

        <div class="sidebar-content">
            <a href="{{ route('adminDash') }}" onclick="handleSidebarClick(event)">
                <i class="bi bi-speedometer2 me-2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('perfil') }}" onclick="handleSidebarClick(event)"> <i
                    class="bi bi-person-circle me-2"></i> Administración de Perfil</a>
            <a href="{{ route('atletas.index') }}" onclick="handleSidebarClick(event)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-person-walking me-2" viewBox="0 0 16 16">
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


            <!-- Submenú de Catálogos Generales -->
            <div class="submenu">
                <a class="submenu-toggle text-white d-flex align-items-center justify-content-between" href="#"
                    id="catalogosToggle">
                    <span><i class="bi bi-folder2-open me-2"></i> Catálogos Generales</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="submenu-items d-none" id="catalogosItems">

                    <a href="{{ route('eventos.index') }}" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-calendar3 me-2"></i> Eventos
                    </a>
                    <a href="{{ route('modalidades.index') }}" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-columns-gap me-2"></i> Modalidades
                    </a>
                    <a href="{{ route('submodalidades.index') }}" onclick="handleSidebarClick(event)"
                        class="text-white">
                        <i class="bi bi-grid-3x2-gap me-2"></i> SubModalidades
                    </a>
                    <a href="{{ route('usuarios.index') }}" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-people me-2"></i> Usuarios
                    </a>


                    <a href="{{ route('grados.index') }}" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-card-heading me-2"></i> Grados
                    </a>

                    <a href="{{ route('categorias.index') }}" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-bookmarks me-2"></i> Categorías
                    </a>
                    <!-- Elementos adicionales para demostrar el scroll -->
                    {{-- <a href="#" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-gear me-2"></i> Configuración
                    </a>
                    <a href="#" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-graph-up me-2"></i> Reportes
                    </a>
                    <a href="#" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-shield-check me-2"></i> Permisos
                    </a> --}}
                </div>
            </div>

            <!-- Otros submenús de ejemplo -->
            {{-- <div class="submenu">
                <a class="submenu-toggle text-white d-flex align-items-center justify-content-between" href="#"
                    id="configuracionToggle">
                    <span><i class="bi bi-gear me-2"></i> Configuración</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="submenu-items d-none" id="configuracionItems">
                    <a href="#" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-sliders me-2"></i> Ajustes Generales
                    </a>
                    <a href="#" onclick="handleSidebarClick(event)" class="text-white">
                        <i class="bi bi-palette me-2"></i> Apariencia
                    </a>
                </div>
            </div> --}}
        </div>
    </nav>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex align-items-center">
            <i class="bi bi-list hamburger me-3" id="toggleSidebar"></i>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a class="text-white text-decoration-none" href="{{ route('adminDash') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page">
                        @yield('breadcrumb-title', '')
                    </li>
                </ol>
            </nav>
            @php
                $usuario = session('usuario') ? \App\Models\Usuario::find(session('usuario')) : null;
            @endphp

            <div class="ms-auto d-flex align-items-center gap-2">
                {{-- Nombre del usuario --}}
                @if ($usuario)
                    <span class="text-white fw-semibold">{{ $usuario->nombre_completo }}</span>
                @endif

                {{-- Dropdown usuario / logo --}}
                <div class="dropdown">
                    <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false"
                        style="border: none; background: transparent;">

                        @if ($usuario && $usuario->imagen)
                            <img src="{{ asset('storage/' . $usuario->imagen) }}" alt="Foto de perfil"
                                style="height: 35px; width: 35px; border-radius: 50%; object-fit: cover;">
                        @elseif ($usuario && $usuario->rol === 'administrador')
                            <img src="{{ asset('images/fct_logo.jpg') }}" alt="Logo Federación"
                                style="height: 35px; width: 35px; border-radius: 50%; object-fit: cover;">
                        @else
                            <i class="bi bi-person-circle user-icon" style="color: #f1f1f3; font-size: 1.7rem;"></i>
                        @endif
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

    <!-- Scripts -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/ripple.js') }}"></script>


    {{--
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css"> --}}
    {{--
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script> --}}
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función para guardar el estado del submenú
        function guardarEstadoSubmenu(abierto) {
            localStorage.setItem('submenuCatalogosAbierto', abierto ? 'true' : 'false');
        }

        // Función para cargar el estado del submenú
        function cargarEstadoSubmenu() {
            const estado = localStorage.getItem('submenuCatalogosAbierto');
            return estado === 'true';
        }

        // Configuración del submenú con persistencia
        document.addEventListener('DOMContentLoaded', function() {
            const catalogosToggle = document.getElementById('catalogosToggle');
            const catalogosItems = document.getElementById('catalogosItems');

            // Cargar estado guardado al iniciar
            const estadoInicial = cargarEstadoSubmenu();
            if (estadoInicial) {
                catalogosItems.classList.remove('d-none');
                catalogosToggle.classList.add('active');
            } else {
                catalogosItems.classList.add('d-none');
                catalogosToggle.classList.remove('active');
            }

            // Evento para abrir/cerrar el submenú
            catalogosToggle.addEventListener('click', function(e) {
                e.preventDefault();
                const estaAbierto = !catalogosItems.classList.contains('d-none');

                if (estaAbierto) {
                    catalogosItems.classList.add('d-none');
                    catalogosToggle.classList.remove('active');
                    guardarEstadoSubmenu(false);
                } else {
                    catalogosItems.classList.remove('d-none');
                    catalogosToggle.classList.add('active');
                    guardarEstadoSubmenu(true);
                }
            });

            // Código existente del sidebar
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

            toggleSidebar.addEventListener('click', function() {
                // En móvil, abre sidebar con clase sidebar-open
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('sidebar-open');
                    sidebar.classList.remove('sidebar-hidden');
                } else {
                    sidebar.classList.toggle('sidebar-hidden');
                    setContentMargin();
                }
            });

            sidebarClose.addEventListener('click', function() {
                sidebar.classList.add('sidebar-hidden');
                sidebar.classList.remove('sidebar-open');
                setContentMargin();
            });

            window.addEventListener('resize', adjustSidebarOnLoad);
            adjustSidebarOnLoad();

            // Cerrar sidebar si se hace clic fuera (solo en móviles)
            document.addEventListener('click', function(e) {
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

        // Función para manejar clics en enlaces del sidebar (opcional)
        function handleSidebarClick(event) {
            // Opcional: puedes agregar lógica adicional aquí si es necesario
            console.log('Navegando a: ', event.currentTarget.href);
        }


        // VISUALIZAR CONTRA CON OJO
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = document.querySelector(this.dataset.target);
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            });
        });
    </script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>


    {{-- Aquí se van a inyectar los scripts personalizados de cada vista --}}
    @yield('scripts')

    @stack('scripts')
    <!-- <script src="{{ asset('js/academiaMatricula/inscripcionesAcademiasOED.js') }}"></script> -->
    <script src="{{ asset('js/academiaMatricula/inscripcionesAdministradores.js') }}"></script>

</body>

</html>
