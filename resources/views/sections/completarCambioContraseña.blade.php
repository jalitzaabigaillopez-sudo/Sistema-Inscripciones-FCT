<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>

    <!-- Bootstrap & Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <style>
        .custom-card {
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            max-width: 430px;
            margin: 2rem auto;
        }

        .btn-primary {
            background-color: #222A59;
            border-radius: 0.5rem;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background-color: #2b4ba5;
        }

        .footer {
            background-color: #222A59;
        }

        .form-label {
            color: #222A59;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col justify-between">

    <div class="container py-5 flex-grow">
        <div class="custom-card p-4">
            <div class="text-center mb-4">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('images/LogoFCT_transpa.png') }}" alt="Logo FCT" class="img-fluid"
                        style="max-height: 60px;">
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mt-3">Cambiar Contraseña</h2>
                <p class="text-gray-600">Ingrese su contraseña temporal y cree una nueva contraseña segura.</p>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="changePasswordForm" action="{{ route('cambiar.contraseña') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="id_usuario" value="{{ $usuario->id_usuario }}">

                <div class="mb-3">
                    <label for="temporaryPassword" class="form-label fw-bold">Contraseña Temporal</label>
                    <div class="input-group input-group-sm">
                        <input type="password" class="form-control" name="temporaryPassword" id="temporaryPassword"
                            required minlength="6" autocomplete="new-password">
                        <button class="btn btn-outline-primary toggle-password" type="button"
                            data-target="#temporaryPassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Nueva Contraseña</label>
                    <div class="input-group input-group-sm">
                        <input type="password" class="form-control" name="password" id="password" required
                            minlength="8" autocomplete="new-password">
                        <button class="btn btn-outline-primary toggle-password" type="button" data-target="#password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    {{-- <small class="text-muted">Mínimo 8 caracteres, incluye mayúsculas, minúsculas y números.</small> --}}
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                    <div class="input-group input-group-sm">
                        <input type="password" class="form-control" name="password_confirmation"
                            id="password_confirmation" required minlength="8" autocomplete="new-password">
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
                        <li id="reqUpperCrear"><i class="bi bi-x-circle text-danger"></i> Al menos una letra mayúscula
                        </li>
                        <li id="reqLowerCrear"><i class="bi bi-x-circle text-danger"></i> Al menos una letra minúscula
                        </li>
                        <li id="reqNumberCrear"><i class="bi bi-x-circle text-danger"></i> Al menos un número</li>
                        <li id="reqSpecialCrear"><i class="bi bi-x-circle text-danger"></i> Al menos un carácter
                            especial</li>
                        <li id="reqMatchCrear"><i class="bi bi-x-circle text-danger"></i> Las contraseñas coinciden</li>
                    </ul>
                </div>

                <div class="d-grid gap-2 d-md-block text-center">
                    <a href="{{ route('login') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer text-white text-center p-3">
        &copy; 2025 Plataforma FCT. Todos los derechos reservados.
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // ==========================
            // Mostrar / ocultar contraseña
            // ==========================
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.closest('.input-group').querySelector('input');
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

            // ==========================
            // VALIDACIÓN DINÁMICA
            // ==========================
            const password = document.getElementById('password');
            const confirm = document.getElementById('password_confirmation');
            const panel = document.getElementById('passwordRequirementsCrear');

            if (password && confirm && panel) {

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
                    setIcon(req.match, p && c && p === c);
                }

                password.addEventListener("input", update);
                confirm.addEventListener("input", update);
            }

            // ==========================
            // SUBMIT UNIFICADO (validación + AJAX)
            // ==========================
            const form = document.getElementById("changePasswordForm");

            form.addEventListener("submit", function(e) {
                e.preventDefault();

                const p = password.value || "";
                const c = confirm.value || "";
                const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,11}$/;

                // VALIDACIÓN CLIENTE
                if (!regex.test(p)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Contraseña no segura",
                        text: "Debe incluir mayúsculas, minúsculas, número y carácter especial (8 a 11 caracteres).",
                        confirmButtonColor: "#222A59"
                    });
                    return;
                }

                if (p !== c) {
                    Swal.fire({
                        icon: "warning",
                        title: "Las contraseñas no coinciden",
                        text: "Ambas contraseñas deben ser iguales.",
                        confirmButtonColor: "#222A59"
                    });
                    return;
                }

                // SI PASA VALIDACIÓN → ENVIAR VIA AJAX
                const formData = new FormData(form);

                fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(res => res.json().catch(() => {}))
                    .then(data => {

                        // Error del backend
                        if (data?.status === "error" || data?.error) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: data.message || data.error,
                                confirmButtonColor: "#222A59"
                            });
                            return;
                        }

                        // Éxito en backend
                        if (data?.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Éxito",
                                text: data.message,
                                confirmButtonColor: "#10B981"
                            }).then(() => {
                                window.location.href = "{{ route('login') }}";
                            });
                            return;
                        }

                        // Si no retorna JSON → redirección normal
                        window.location.href = "{{ route('login') }}";
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: "error",
                            title: "Error inesperado",
                            text: "Ocurrió un problema. Inténtelo de nuevo.",
                        });
                    });

            });

        });
    </script>


</body>

</html>
