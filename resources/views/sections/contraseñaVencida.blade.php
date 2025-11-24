<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña Vencida</title>

    <!-- Bootstrap + Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

            <div class="text-center mb-3">
                <img src="{{ asset('images/LogoFCT_transpa.png') }}" alt="Logo FCT" style="max-height: 60px;">
                <h2 class="text-2xl font-bold text-gray-800 mt-3">Contraseña Vencida</h2>
            </div>

            <p class="text-gray-700 fw-semibold mb-2">
                Su contraseña ha vencido. Por seguridad debe actualizarla cada
                <span class="text-primary">{{ config('ConfiguracionFCT._vencimiento_contraseña') }}</span> días.
            </p>

            <p class="text-gray-600">Debe ingresar una nueva contraseña segura (8 a 11 caracteres) y no puede ser igual
                a la anterior.</p>

            @if (session('error'))
                <div class="alert alert-danger mt-2">{{ session('error') }}</div>
            @endif

            <form id="formVencida" action="{{ route('cambiar.contraseñaVencida') }}" method="POST">
                @csrf

                <input type="hidden" name="id_usuario" value="{{ $usuario->id_usuario }}">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nueva Contraseña</label>
                    <div class="input-group input-group-sm">
                        <input type="password" name="nuevaContraseña" id="nuevaContraseña" class="form-control" required
                            minlength="8" maxlength="11">
                        <button class="btn btn-outline-primary toggle-password" type="button"
                            data-target="#nuevaContraseña"><i class="bi bi-eye"></i></button>
                    </div>
                </div>

                <div id="passwordReqVencida" class="mt-2 p-3 rounded border" style="display:none; background:#f8fafc;">
                    <small class="fw-bold d-block mb-2 text-primary">Requisitos de la contraseña:</small>
                    <ul class="list-unstyled text-sm ms-2">
                        <li id="reqLengthV"><i class="bi bi-x-circle text-danger"></i> Entre 8 y 11 caracteres</li>
                        <li id="reqUpperV"><i class="bi bi-x-circle text-danger"></i> Al menos una mayúscula</li>
                        <li id="reqLowerV"><i class="bi bi-x-circle text-danger"></i> Al menos una minúscula</li>
                        <li id="reqNumberV"><i class="bi bi-x-circle text-danger"></i> Al menos un número</li>
                        <li id="reqSpecialV"><i class="bi bi-x-circle text-danger"></i> Un carácter especial</li>
                        <li id="reqMatchV"><i class="bi bi-x-circle text-danger"></i> Las contraseñas coinciden</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                    <div class="input-group input-group-sm">
                        <input type="password" name="confirmarNuevaContraseña" id="confirmarNuevaContraseña"
                            class="form-control" required minlength="8" maxlength="11">
                        <button class="btn btn-outline-primary toggle-password" type="button"
                            data-target="#confirmarNuevaContraseña"><i class="bi bi-eye"></i></button>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4">Actualizar Contraseña</button>
                </div>
            </form>

        </div>
    </div>

    <footer class="footer text-white text-center p-3">
        © 2025 Plataforma FCT. Todos los derechos reservados.
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // ==========================
            // Mostrar / ocultar contraseña
            // ==========================
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener("click", function() {
                    const input = document.querySelector(this.dataset.target);
                    const icon = this.querySelector("i");

                    input.type = input.type === "password" ? "text" : "password";
                    icon.classList.toggle("bi-eye");
                    icon.classList.toggle("bi-eye-slash");
                });
            });

            // ==========================
            // VALIDACIÓN DINÁMICA
            // ==========================
            const p = document.getElementById("nuevaContraseña");
            const c = document.getElementById("confirmarNuevaContraseña");
            const panel = document.getElementById("passwordReqVencida");

            const req = {
                length: document.getElementById('reqLengthV'),
                upper: document.getElementById('reqUpperV'),
                lower: document.getElementById('reqLowerV'),
                number: document.getElementById('reqNumberV'),
                special: document.getElementById('reqSpecialV'),
                match: document.getElementById('reqMatchV')
            };

            function setIcon(li, ok) {
                const i = li.querySelector('i');
                i.classList.toggle('bi-check-circle', ok);
                i.classList.toggle('bi-x-circle', !ok);
                i.classList.toggle('text-success', ok);
                i.classList.toggle('text-danger', !ok);
            }

            function update() {
                const value = p.value || "";
                const conf = c.value || "";

                panel.style.display = (value || conf) ? "block" : "none";

                setIcon(req.length, value.length >= 8 && value.length <= 11);
                setIcon(req.upper, /[A-Z]/.test(value));
                setIcon(req.lower, /[a-z]/.test(value));
                setIcon(req.number, /\d/.test(value));
                setIcon(req.special, /[^A-Za-z0-9]/.test(value));
                setIcon(req.match, value && conf && value === conf);
            }

            p.addEventListener("input", update);
            c.addEventListener("input", update);

            // ==========================
            // SUBMIT — AJAX + SweetAlert2
            // ==========================
            document.getElementById("formVencida").addEventListener("submit", function(e) {
                e.preventDefault();

                const newPass = p.value;
                const confPass = c.value;

                // REGEX 8–11 caracteres + mayúscula, minúscula, número y especial
                const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,11}$/;

                if (!regex.test(newPass)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Contraseña no válida",
                        text: "La contraseña debe cumplir todos los requisitos.",
                        confirmButtonColor: "#222A59"
                    });
                    return;
                }

                if (newPass !== confPass) {
                    Swal.fire({
                        icon: "warning",
                        title: "Contraseñas no coinciden",
                        text: "Debe confirmarla correctamente.",
                        confirmButtonColor: "#222A59"
                    });
                    return;
                }

                // Crear FormData
                const form = document.getElementById("formVencida");
                const formData = new FormData(form);

                // ================
                // AJAX FETCH
                // ================
                fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(res => res.json().catch(() => null))
                    .then(data => {

                        // Error del backend (JSON)
                        if (data?.status === "error" || data?.error) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: data.message || data.error,
                                confirmButtonColor: "#222A59"
                            });
                            return;
                        }

                        // Éxito del backend (JSON)
                        if (data?.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Contraseña actualizada",
                                text: data.message,
                                confirmButtonColor: "#10B981"
                            }).then(() => {
                                window.location.href = "{{ route('login') }}";
                            });
                            return;
                        }

                        // Si el backend NO devuelve JSON (redirect normal)
                        window.location.href = "{{ route('login') }}";

                    })
                    .catch(() => {
                        Swal.fire({
                            icon: "error",
                            title: "Error inesperado",
                            text: "Ocurrió un problema. Inténtelo de nuevo.",
                            confirmButtonColor: "#d33"
                        });
                    });
            });

        });
    </script>


</body>

</html>
