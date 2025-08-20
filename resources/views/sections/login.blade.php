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
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="header-image"></div>

    <div class="container p-5">
        <div class="row centered-row">
            <div class="col-md-6 login-container me-3">
                <div class="card" style="height: 530px;">
                    <div class="card-body shadow-lg" style="background-color: #656b8c23">
                        <h2 class="text-center">Iniciar Sesión</h2>
                        <p class="text-center">Bienvenido al Panel Administrativo de FCT</p>
                        <form>
                            <div class="mb-3 p-1">
                                <label for="email" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="email"
                                    placeholder="ej: usuario@gmail.com" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3 p-1">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password">
                            </div>
                            <div class="mb-4">
                                <a href="#" class="link-primary">¿Olvidaste tu contraseña?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 button"><i
                                    class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión</button>
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
</body>

</html>
