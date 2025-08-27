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
        /* Body adjustment to prevent overflow issues */
        body {
            overflow-x: hidden;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #222A59;
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            left: 250px; /* Start after sidebar width on large screens */
            width: calc(100% - 250px); /* Span remaining width */
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        .navbar-full {
            left: 0;
            width: 100%;
        }

        .navbar-text {
            color: white;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .user-icon {
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            bottom: 0;
            background-color: #222A59;
            padding-top: 0; /* Remove padding to ensure logo is at top */
            color: white;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 1020; /* Below navbar on large screens */
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar .logo-container {
            text-align: center;
            padding: 1rem;
            background-color: #222A59; /* Ensure logo background matches sidebar */
            position: relative;
            z-index: 1021; /* Above sidebar to prevent clipping */
        }

        .sidebar .logo-container img {
            height: 40px;
        }

        .sidebar a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        .sidebar a:hover {
            background-color: #2e386e;
        }

        /* Content Wrapper */
        .content-wrapper {
            margin-top: 60px; /* Match navbar height */
            margin-left: 250px; /* Align with navbar on large screens */
            padding: 1rem;
            transition: margin-left 0.3s ease-in-out;
        }

        .content-wrapper-full {
            margin-left: 0;
        }

        /* Hamburger Menu */
        .hamburger {
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 1rem;
            z-index: 1040; /* Above sidebar and navbar */
        }

        /* Close Button in Sidebar for Small Screens */
        .sidebar-close {
            display: none; /* Hidden on large screens */
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 1050; /* Above sidebar */
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                z-index: 1020; /* Below navbar to keep hamburger accessible */
            }

            .sidebar-open {
                transform: translateX(0); /* Show when toggled */
            }

            .sidebar-close {
                display: block; /* Show close button on small screens */
            }

            .navbar {
                left: 0;
                width: 100%; /* Full width on small screens */
                z-index: 1030; /* Above sidebar */
            }

            .content-wrapper {
                margin-left: 0; /* No margin shift on small screens */
            }
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar sidebar-hidden" id="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/vectlogo.png') }}" alt="FCT Logo">
        </div>
        <i class="bi bi-x sidebar-close" id="sidebarClose"></i>
        <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('atletas.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" class="bi bi-person-walking" viewBox="0 0 16 16">
                <path
                    d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z" />
                <path
                    d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.531-.22Z" />
            </svg> Atletas</a>
        <a href="{{ route('academias.index') }}"><i class="bi bi-layers"></i> Academias</a>
        <a href="{{ route('usuarios.index') }}"><i class="bi bi-people"></i> Usuarios</a>
        <a href="{{ route('modalidades.index') }}"><i class="bi bi-columns-gap"></i> Modalidades</a>
        <a href="{{ route('inscripciones.index') }}"><i class="bi bi-ui-checks"></i> Inscripciones</a>
        <a href="{{ route('grados.index') }}"><i class="bi bi-card-heading"></i> Grados</a>
        <a href="{{ route('eventos.index') }}"><i class="bi bi-calendar3"></i> Eventos</a>
        <a href="{{ route('categorias.index') }}"><i class="bi bi-bookmarks"></i> Categorías</a>
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
                <i class="bi bi-person-circle user-icon"></i>
            </div>
    </nav>
 



    <!-- Main Content -->
    <div class="content-wrapper" id="contentWrapper">
        @yield('content')
    </div>

    <!-- Scripts -->
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
            const navbar = document.querySelector('.navbar');
            const contentWrapper = document.getElementById('contentWrapper');
            const toggleSidebar = document.getElementById('toggleSidebar');
            const sidebarClose = document.getElementById('sidebarClose');

            // Check screen size to set initial state
            const isSmallScreen = window.matchMedia('(max-width: 768px)').matches;
            if (isSmallScreen) {
                sidebar.classList.add('sidebar-hidden');
                navbar.classList.add('navbar-full');
                contentWrapper.classList.add('content-wrapper-full');
            } else {
                sidebar.classList.remove('sidebar-hidden');
                navbar.classList.remove('navbar-full');
                contentWrapper.classList.remove('content-wrapper-full');
            }

            // Toggle sidebar on hamburger click
            toggleSidebar.addEventListener('click', function () {
                sidebar.classList.toggle('sidebar-hidden');
                sidebar.classList.toggle('sidebar-open');
                if (!isSmallScreen) {
                    navbar.classList.toggle('navbar-full');
                    contentWrapper.classList.toggle('content-wrapper-full');
                }
            });

            // Close sidebar on close button click (small screens)
            sidebarClose.addEventListener('click', function () {
                sidebar.classList.add('sidebar-hidden');
                sidebar.classList.remove('sidebar-open');
            });

            // Handle window resize
            window.addEventListener('resize', function () {
                const isNowSmallScreen = window.matchMedia('(max-width: 768px)').matches;
                if (isNowSmallScreen) {
                    sidebar.classList.add('sidebar-hidden');
                    sidebar.classList.remove('sidebar-open');
                    navbar.classList.add('navbar-full');
                    contentWrapper.classList.add('content-wrapper-full');
                } else {
                    sidebar.classList.remove('sidebar-hidden');
                    sidebar.classList.add('sidebar-open');
                    navbar.classList.remove('navbar-full');
                    contentWrapper.classList.remove('content-wrapper-full');
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