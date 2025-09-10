<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Federación Costarricense de Taekwondo')</title>
    <link rel="icon" type="image/x-icon" href="https://via.placeholder.com/32?text=FCT">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            left: 250px;
            /* Start after sidebar width on large screens */
            width: calc(100% - 250px);
            /* Span remaining width */
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
            padding-top: 0;
            /* Remove padding to ensure logo is at top */
            color: white;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 1040;
            /* Increased to ensure sidebar is above navbar */
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar .logo-container {
            text-align: center;
            padding: 1rem;
            background-color: #222A59;
            /* Matches sidebar */
            position: relative;
            z-index: 1041;
            /* Above sidebar and navbar */
        }

        .sidebar .logo-container img {
            width: 150px;
            /* Increased size for better legibility */
            height: auto;
            /* Maintain aspect ratio */
            object-fit: contain;
            /* Prevent distortion */
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
            margin-top: 60px;
            /* Match navbar height */
            margin-left: 250px;
            /* Align with navbar on large screens */
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
            z-index: 1050;
            /* Above sidebar and navbar */
        }

        /* Close Button in Sidebar for Small Screens */
        .sidebar-close {
            display: none;
            /* Hidden on large screens */
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 1050;
            /* Above sidebar */
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                z-index: 1040;
                /* Ensure sidebar is above content but below hamburger */
            }

            .sidebar-open {
                transform: translateX(0);
                /* Show when toggled */
            }

            .sidebar-close {
                display: block;
                /* Show close button on small screens */
            }

            .navbar {
                left: 0;
                width: 100%;
                /* Full width on small screens */
                z-index: 1030;
                /* Below sidebar when open */
            }

            .content-wrapper {
                margin-left: 0;
                /* No margin shift on small screens */
            }

            .sidebar .logo-container img {
                width: 120px;
                /* Slightly smaller for mobile but still legible */
                height: auto;
            }
                  .submenu a.submenu-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    font-weight: 600;
    color: #ffffff;
    cursor: pointer;
    transition: background 0.2s ease-in-out;
}
.submenu a.submenu-toggle:hover {
    background-color: #2e386e;
}

.submenu-items a {
    display: block;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    color: #f8fbff;
    transition: background 0.2s ease-in-out;
}
.submenu-items a:hover {
    background-color: #2e386e;
}
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
    <a href="{{ route('dashboard.academias') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>

    <!-- Submenú de Inscripciones a eventos -->
    <div class="submenu">
        <a class="submenu-toggle text-white d-flex align-items-center justify-content-between" href="#" id="inscripcionesToggle">
            <span><i class="bi bi-folder2-open me-2"></i>Inscripciones a eventos</span>
            <i class="bi bi-chevron-down ms-2"></i>
        </a>
        <div class="submenu-items d-none" id="inscripcionesItems">
            <a href="{{ route('academia.inscripcionEvento') }}"><i class="bi bi-calendar-plus me-2"></i>Nueva inscripción</a>
      <a href="{{ route('academia.misInscripciones') }}"><i class="bi bi-list-check me-2"></i> Mis Inscripciones</a>
        </div>
    </div>

    <a href="{{ route('academia.registrosAtletas') }}"><i class="bi bi-people"></i> Gestión de atletas</a>
    <a href="{{ route('academia.perfil-academia') }}"><i class="bi bi-person-badge"></i> Administración de Perfil</a>
    <a href="#"><i class="bi bi-bar-chart-line"></i> Avance de eventos</a>
    <a href="#"><i class="bi bi-graph-up"></i> Estadística atletas/eventos</a>
    <a href="#"><i class="bi bi-bar-chart-line me-2"></i><span>Reportes</span></a>
</nav>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <i class="bi bi-list hamburger" id="toggleSidebar"></i>
            <span class="navbar-text">@yield('navbar-title', 'Inicio / Academias')</span>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-person-circle user-icon" style="color: #f1f1f3; font-size: 1.5rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                          <a class="dropdown-item" href="{{ route('academia.perfil-academia') }}">
                            <i class="bi bi-person"></i> Perfil Academia
                          </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('academia.misInscripciones') }}">
                                <i class="bi bi-list-check"></i> Mis inscripciones
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout.process') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i>  Cerrar sesión</button>
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
    document.addEventListener('DOMContentLoaded', function () {
        // Submenu toggle logic for sidebar
        document.querySelectorAll('.submenu-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                if (submenu && submenu.classList.contains('submenu-items')) {
                    submenu.classList.toggle('d-none');
                }
            });
        });
    });
    
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-hidden');
                sidebar.classList.toggle('sidebar-open');
                if (!isSmallScreen) {
                    navbar.classList.toggle('navbar-full');
                    contentWrapper.classList.toggle('content-wrapper-full');
                }
            });

            // Close sidebar on close button click (small screens)
            sidebarClose.addEventListener('click', function() {
                sidebar.classList.add('sidebar-hidden');
                sidebar.classList.remove('sidebar-open');
            });

            // Handle window resize
            window.addEventListener('resize', function() {
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
    </script>
</body>

</html>
