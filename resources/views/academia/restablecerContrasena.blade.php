<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restablecer Contraseña</title>

    <!-- Bootstrap CSS (v5.3.3) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .header-image {
            background: linear-gradient(to bottom, rgba(0, 0, 128, 0.8), rgba(0, 0, 128, 0.5)),
                url('https://img.olympicchannel.com/images/image/private/t_16-9_1920/f_auto/primary/lo6iwcfrrjtw8kqcff1b');
            background-size: cover;
            background-position: center;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .footer {
            background-color: #222A59;
            color: white;
            padding: 1rem;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 0.9rem;
        }

        .custom-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.40);
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            max-width: 630px;
            margin: 2rem auto;
            transition: transform 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-8px);
        }

        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            padding: 0.75rem;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #222A59;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.2);
            outline: none;
        }

        .btn-primary {
            background-color: #222A59;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #2b4ba5;
        }

        .btn-secondary {
            background-color: #6b7280;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-secondary:hover {
            background-color: #5a606b;
        }

        @media (max-width: 576px) {
            .custom-card {
                margin: 1rem;
            }

            .header-image {
                height: 120px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <div class="header-image"></div>

    <div class="container py-5">
        <div class="custom-card">


            <div class="p-4 text-center">
                <img src="{{ asset('images/LogoFCT_transpa.png') }}" class="mx-auto mb-4" alt="Logo FCT"
                    style="max-height: 60px;">
                <h2 class="text-2xl font-bold text-gray-800 mb-3">Restablecer Contraseña</h2>
                <p class="text-gray-600 mb-4">Ingrese su dirección de correo electrónico registrada para recibir una
                    contraseña temporal.</p>

                <div class="px-4">
                    <form id="resetForm" action="{{ route('correo.cambiarContraseña') }}" method="POST"
                        class="space-y-4">
                        @csrf
                        <div class="mb-4">
                            <input type="email" class="form-control w-full" name="correo" id="correo"
                                placeholder="ej: usuario@gmail.com" aria-describedby="emailHelp" required
                                aria-required="true">
                            <div id="emailHelp" class="text-gray-500 text-sm mt-1 hidden">Por favor, ingrese un
                                correo válido.</div>
                        </div>
                        <div class="d-grid gap-2 d-md-block">
                            <a href="{{ route('login') }}" class="btn btn-secondary">
                                Regresar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Enviar Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2025 Plataforma FCT. Todos los derechos reservados.
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('resetForm').addEventListener('submit', function(e) {

            const email = document.getElementById('correo').value;

            const isSuccess = true;

            if (isSuccess) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    html: `Su contraseña temporal ha sido enviada a <b>${email}</b>.`,
                    confirmButtonText: 'Aceptar',
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ha ocurrido un problema. Por favor, intenta de nuevo más tarde.',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'btn btn-secondary'
                    }
                });
            }
        });
    </script>

</body>

</html>
