<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restablecer Contraseña</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            height: 40px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .button-primary {
            background-color: #222A59;
            color: #ffffff;
            border: none;
            padding: 10px;
        }

        .button-primary:hover {
            background-color: #2e386e;
            color: #ffffff;
        }

        .button-secondary {
            background-color: #6c757d;
            color: #ffffff;
            border: none;
            padding: 10px;
        }

        .button-secondary:hover {
            background-color: #5a6268;
            color: #ffffff;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background-color: #ffffff;
            margin: 0 auto;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-img-top {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            object-fit: contain;
            height: 120px;
            width: 100%;
            padding: 10px;
        }

        .card-header {
            background-color: #f8f9fa;
            /* padding: 10px; */
            border-bottom: 1px solid #e0e0e0;
            text-align: center;
        }

        .card-body {
            padding: 25px;
            text-align: center;
        }

        .card-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #222A59;
            margin-bottom: 15px;
        }

        .card-text {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #8d8c8c;
            padding: 12px;
            font-size: 1rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .text-body-secondary {
            color: #6c757d !important;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="header-image"></div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header" style="background-color: #2e386e1c">
                        <img src="{{ asset('images/LogoFCT_transpa.png') }}" class="card-img-top" alt="Logo FCT">
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-10">
                                <h5 class="card-title">Restablecer Contraseña</h5>
                                <p class="card-text">Ingrese su dirección de correo electrónico registrada en la
                                    plataforma.</p>
                                <form action="" method="POST" id="resetForm">
                                    @csrf
                                    <div class="mb-4">
                                        <input type="email" class="form-control" name="correo" id="correo"
                                            placeholder="ej: usuario@gmail.com" aria-describedby="emailHelp">
                                    </div>
                                    <div class="d-grid gap-2 d-md-block">
                                        <a href="{{ route('login') }}" type="button" class="btn button-secondary"><i
                                                class="bi bi-box-arrow-left"></i> Regresar</a>
                                        <button type="submit" class="btn button-primary">Enviar
                                            Solicitud <i class="bi bi-send"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer"></div>

    <!-- use bootstrap 5.3.7 -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css') }}">
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/sweetalert2@11') }}"></script>

    <script>
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const isSuccess = true;

            if (isSuccess) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Se ha enviado una contraseña temporal a tu correo.',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'btn button-primary'
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ha ocurrido un problema. Por favor, intenta de nuevo más tarde.',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'btn button-secondary'
                    }
                });
            }
        });
    </script>

</body>

</html>
