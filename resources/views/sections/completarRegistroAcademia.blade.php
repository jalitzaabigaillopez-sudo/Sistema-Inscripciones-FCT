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
        .header-image {
            background-color: #000080;
            height: 110px;
            background-image: url('{{ asset('images/login.webp') }}');
            background-size: cover;
            background-position: center;
            opacity: 0.7;
        }

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
            padding: 40px 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            /* sombra más elegante */
            transition: all 0.3s ease-in-out;
            /* transición suave */
            transform: translateY(0);
        }

        .login-box:hover {
            transform: translateY(-6px);
            /* sube ligeramente */
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.20);
            /* más sombra */
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

    <div class="header-image"></div>

    <div class="container py-5 flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5">

                <div class="login-box">

                    <!-- LOGO CENTRADO -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/LogoFCT_transpa.png') }}" alt="Logo FCT" class="img-fluid"
                            style="max-height: 70px;">
                    </div>

                    <h2 class="text-center">Completar Registro de Academia</h2>
                    <p class="text-center text-muted mb-4">
                        Ingrese la información solicitada para activar su cuenta de academia en el sistema FCT.
                    </p>

                    <form action="{{ route('cuentaAcademia.process') }}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id_usuario" value="{{ $usuario->id_usuario }}">

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                placeholder="ejemplo@correo.com" required autocomplete="off">
                        </div>

                        <!-- Contraseña temporal -->
                        <div class="mb-3">
                            <label for="temporaryPassword" class="form-label">Contraseña temporal</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" name="temporaryPassword"
                                    id="temporaryPassword" placeholder="Ingrese su contraseña temporal" required>
                                <button class="btn btn-outline-primary toggle-password" type="button"
                                    data-target="#temporaryPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contraseña nueva -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva contraseña</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Cree su nueva contraseña" required>
                                <button class="btn btn-outline-primary toggle-password" type="button"
                                    data-target="#password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirmar contraseña -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" name="password_confirmation"
                                    id="password_confirmation" placeholder="Confirme su contraseña" required>
                                <button class="btn btn-outline-primary toggle-password" type="button"
                                    data-target="#password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PANEL DE REQUISITOS -->
                        <div id="passwordRequirementsCrear" class="mt-2 p-3 rounded border"
                            style="display:none; background:#f8fafc;">
                            <small class="fw-bold d-block mb-2 text-primary">La contraseña debe cumplir con:</small>
                            <ul class="list-unstyled ms-2 text-sm">
                                <li id="reqLengthCrear"><i class="bi bi-x-circle text-danger"></i> Entre 8 y 11 caracteres</li>
                                <li id="reqUpperCrear"><i class="bi bi-x-circle text-danger"></i> Al menos una letra mayúscula</li>
                                <li id="reqLowerCrear"><i class="bi bi-x-circle text-danger"></i> Al menos una letra minúscula</li>
                                <li id="reqNumberCrear"><i class="bi bi-x-circle text-danger"></i> Al menos un número</li>
                                <li id="reqSpecialCrear"><i class="bi bi-x-circle text-danger"></i> Al menos un carácter especial</li>
                                <li id="reqMatchCrear"><i class="bi bi-x-circle text-danger"></i> Las contraseñas coinciden</li>
                            </ul>
                        </div>

                        <!-- Botón -->
                        <button type="submit" class="btn-ingresar mt-3">
                            <i class="bi bi-check-circle me-1"></i> Completar Registro
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <footer class="footer">
        &copy; {{ date('Y') }} Federación Costarricense de Taekwondo. Todos los derechos reservados.
    </footer>


    <!-- Bootstrap 5.3.7 -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ============================================================
                MOSTRAR / OCULTAR CONTRASEÑA
            ============================================================ */
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', function() {
                    const target = document.querySelector(this.dataset.target);
                    const icon = this.querySelector('i');

                    if (target.type === 'password') {
                        target.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        target.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            });


            /* ============================================================
                VALIDACIÓN DINÁMICA DEL PANEL
            ============================================================ */
            const password = document.getElementById('password');
            const confirm = document.getElementById('password_confirmation');
            const panel = document.getElementById('passwordRequirementsCrear');

            const req = {
                length: document.getElementById('reqLengthCrear'),
                upper: document.getElementById('reqUpperCrear'),
                lower: document.getElementById('reqLowerCrear'),
                number: document.getElementById('reqNumberCrear'),
                special: document.getElementById('reqSpecialCrear'),
                match: document.getElementById('reqMatchCrear')
            };

            function setIcon(li, ok) {
                const i = li.querySelector('i');
                i.classList.toggle('bi-check-circle', ok);
                i.classList.toggle('text-success', ok);
                i.classList.toggle('bi-x-circle', !ok);
                i.classList.toggle('text-danger', !ok);
            }

            function update() {
                const p = password.value || "";
                const c = confirm.value || "";

                panel.style.display = (p || c) ? "block" : "none";

                setIcon(req.length, p.length >= 8 && p.length <= 11);
                setIcon(req.upper, /[A-Z]/.test(p));
                setIcon(req.lower, /[a-z]/.test(p));
                setIcon(req.number, /\d/.test(p));
                setIcon(req.special, /[^A-Za-z0-9]/.test(p));

                // COINCIDENCIA
                setIcon(req.match, p && c && p === c);
            }

            password.addEventListener("input", update);
            confirm.addEventListener("input", update);


            /* ============================================================
                SUBMIT AJAX CON SWEETALERT
            ============================================================ */
            const form = document.querySelector("form");

            form.addEventListener("submit", function(e) {
                e.preventDefault();

                const p = password.value || "";
                const c = confirm.value || "";

                const regex =
                    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,11}$/;

                // CONTRASEÑA SEGURA
                if (!regex.test(p)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Contraseña no segura",
                        text: "Debe incluir mayúscula, minúscula, número, carácter especial y tener entre 8 y 11 caracteres.",
                        confirmButtonColor: "#222A59"
                    });
                    return;
                }

                // COINCIDENCIA
                if (p !== c) {
                    Swal.fire({
                        icon: "warning",
                        title: "Las contraseñas no coinciden",
                        text: "Asegúrese de escribir la misma contraseña en ambos campos.",
                        confirmButtonColor: "#222A59"
                    });
                    return;
                }


                /* ===============================================
                    SI TODO ESTÁ BIEN → ENVIAR VIA FETCH
                =============================================== */
                const formData = new FormData(form);

                fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(res => res.json().catch(() => null))
                    .then(data => {

                        // ❌ ERROR DEL BACKEND
                        if (data?.error) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: data.error,
                                confirmButtonColor: "#222A59"
                            });
                            return;
                        }

                        // ÉXITO TOTAL → Redirigir
                        Swal.fire({
                            icon: "success",
                            title: "Cuenta activada",
                            text: "Su cuenta ha sido activada correctamente.",
                            confirmButtonColor: "#10B981"
                        }).then(() => {
                            window.location.href = "{{ route('login') }}";
                        });

                    })
                    .catch(() => {
                        Swal.fire({
                            icon: "error",
                            title: "Error inesperado",
                            text: "Ocurrió un problema en el servidor.",
                            confirmButtonColor: "#222A59"
                        });
                    });

            });

        });
    </script>

</body>

</html>
