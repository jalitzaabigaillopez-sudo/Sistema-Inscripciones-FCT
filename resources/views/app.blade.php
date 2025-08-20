<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Federación Costarricense de Taekwondo')</title>
    <link rel="icon" type="image/x-icon" href="https://via.placeholder.com/32?text=FCT">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* Navbar Styling */
        .navbar {
            background-color: #222A59;
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 0.5rem;
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
            top: 60px;
            bottom: 0;
            background-color: #222A59;
            padding-top: 1rem;
            color: white;
            overflow-y: auto;
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
            margin-left: 250px;
            margin-top: 60px;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/Logotipo.png') }}" alt="FCT Logo">
            </a>
            <span class="navbar-text">@yield('navbar-title', 'Inicio / Academias')</span>
            <div class="ms-auto">
                <i class="bi bi-person-circle user-icon"></i>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="#">Dashboard</a>
        <a href="#">Atletas</a>
        <a href="#">Academias</a>
        <a href="#">Usuarios</a>
        <a href="#">Modalidades</a>
        <a href="#">Inscripciones</a>
        <a href="#">Pesos</a>
        <a href="#">Eventos</a>
        <a href="#">Categorías</a>
    </nav>

    <!-- Main Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>