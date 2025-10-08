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
            background-color: #000080;
            height: 110px;
            background-image: url('https://img.olympicchannel.com/images/image/private/t_16-9_1920/f_auto/primary/lo6iwcfrrjtw8kqcff1b');
            background-size: cover;
            background-position: center;
            opacity: 0.7;
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
            e.preventDefault(); // Evita recargar la página

            const form = e.target;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor espere unos segundos.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message,
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#222A59'
                        }).then(() => {
                            form.reset();
                        });
                    } else if (data.status === 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#6b7280'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'Ha ocurrido un problema en el servidor. Por favor, intente nuevamente.',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#6b7280'
                    });
                });
        });
    </script>


</body>

</html>
