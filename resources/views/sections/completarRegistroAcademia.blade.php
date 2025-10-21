<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Completar Registro - Federación Costarricense de Taekwondo')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5.3.7 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== Topbar ===== */
        .topbar {
            background-color: #222A59;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-weight: 600;
            font-size: 20px;
            letter-spacing: 0.5px;
        }

        /* ===== Login Box ===== */
        .login-box {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 40px 30px;
            max-width: 500px;
            margin: 60px auto;
        }

        .login-box h2 {
            color: #222A59;
            text-align: center;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #222A59;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            border-color: #222A59;
            box-shadow: 0 0 0 0.2rem rgba(34, 42, 89, 0.25);
        }

        /* ===== Botón ===== */
        .btn-ingresar {
            background-color: #222A59;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            width: 100%;
            font-weight: 600;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-ingresar:hover {
            background-color: #2f3f90;
        }

        /* ===== Footer ===== */
        .footer {
            background-color: #222A59;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
            font-size: 14px;
        }

        /* ===== Imagen lateral opcional ===== */
        .image-side {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-side img {
            max-width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .login-box {
                margin-top: 30px;
            }
        }
    </style>
</head>

<body>

    {{-- Top bar --}}
    <div class="topbar">
        Federación Costarricense de Taekwondo
    </div>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="login-box">
                    <h2>Completar Registro de Academia</h2>
                    <p class="text-center text-muted mb-4">
                        Ingrese la información solicitada para activar su cuenta de academia en el sistema FCT.
                    </p>

                    <form action="{{ route('cuentaAcademia.process') }}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id_usuario" value="{{ $usuario->id_usuario }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                placeholder="ejemplo@correo.com" required autocomplete="off" autocorrect="off"
                                autocapitalize="none" spellcheck="false">
                        </div>

                        <div class="mb-3 p-1">
                            <label for="temporaryPassword" class="form-label">Contraseña temporal</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" name="temporaryPassword"
                                    id="temporaryPassword" value="{{ old('temporaryPassword') }}"
                                    placeholder="Ingrese su contraseña temporal" required autocomplete="off"
                                    autocorrect="off" autocapitalize="none" spellcheck="false">
                                <button class="btn btn-outline-primary toggle-password" type="button"
                                    data-target="#temporaryPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 p-1">
                            <label for="password" class="form-label">Nueva contraseña</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Cree su nueva contraseña" required autocomplete="off" autocorrect="off"
                                    autocapitalize="none" spellcheck="false">
                                <button class="btn btn-outline-primary toggle-password" type="button"
                                    data-target="#password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                        </div>

                        <button type="submit" class="btn-ingresar">
                            <i class="bi bi-check-circle me-1"></i> Completar Registro
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-6 image-side d-none d-md-flex">
                <img src="{{ asset('images/login.webp') }}" alt="FCT Taekwondo">
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="footer">
        &copy; {{ date('Y') }} Federación Costarricense de Taekwondo. Todos los derechos reservados.
    </footer>

    <!-- Bootstrap 5.3.7 -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /* ============================================================
                 👁️ VISUALIZAR / OCULTAR CONTRASEÑA (OJO)
              ============================================================ */
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
</body>

</html>
