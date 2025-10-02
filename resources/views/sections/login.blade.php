@if(request()->has('session_expired'))
    <div class="alert alert-warning">
        Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.
    </div>
@endif

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Federación Costarricense de Taekwondo</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- use bootstrap 5.3.7 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
            height: 20px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .login-container {
            max-width: 450px;
            margin-top: 20px;
            border-radius: 1rem;
        }

        .logo-container {
            text-align: center;
            margin-top: 20px;
        }

        .button {
            background-color: #222A59;
        }

        .button:hover {
            background-color: #2e386e;
        }

        .centered-row {
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 40px;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            border-radius: 1rem;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="header-image"></div>

    <div class="container p-5">
        <div class="row centered-row">
            <div class="col-md-6 login-container me-3">
                <div class="card" style="height: 530px;">
                    <div class="card-body shadow-lg">
                        <h2 class="text-center">Iniciar Sesión</h2>
                        <p class="text-center">Bienvenido al Panel Administrativo de FCT</p>
                        <form action="{{ route('login.process') }}" method="POST">
                            @csrf
                            <div class="mb-3 p-1">
                                <label for="email" class="form-label">Correo</label>
                                <input type="email" class="form-control" name="email" id="correo"
                                    placeholder="ej: usuario@gmail.com" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3 p-1">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group input-group-sm">
                                    <input type="password" class="form-control" name="password" id="password">
                                    <button class="btn btn-outline-primary toggle-password" type="button"
                                        data-target="#password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <a href="{{ route('restablecerContraseña') }}" class="link-primary">¿Olvidaste tu
                                    contraseña?</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 button"><i
                                    class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión</button>
                            <!-- <a href="{{ route('academia.preregistro.form') }}" class="btn btn-outline-primary  w-100 mt-3">¿Eres una nueva academia? Solicita acceso</a> -->



                            <div class="mt-3 text-center">
                                <span>¿No tienes una cuenta? </span>
                                <a href="{{ route('academia.preregistro.form') }}" class="link-primary">Regístrate</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 logo-container">
                <img src="{{ asset('images/fct_logo.jpg') }}" alt="FCT Logo" class="img-fluid">
            </div>
        </div>
    </div>

    <div class="footer"></div>

    <!-- use bootstrap 5.3.7 -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script>
        // VISUALIZAR CONTRA CON OJO
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', function () {
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
</body>

</html>