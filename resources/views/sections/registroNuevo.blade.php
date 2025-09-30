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

    <style>
        .custom-card {
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            max-width: 630px;
            margin: 2rem auto;
            transition: transform 0.3s ease;
        }
        .custom-card:hover {
            transform: translateY(-5px);
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
            color: white;
            padding: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container py-5">
        <div class="custom-card p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('images/LogoFCT_transpa.png') }}" alt="Logo FCT" style="max-height: 60px;">
                <h2 class="text-2xl font-bold text-gray-800 mt-3">Cambiar Contraseña</h2>
                <p class="text-gray-600">Ingrese su contraseña temporal y cree una nueva contraseña segura.</p>
            </div>

            <form id="changePasswordForm" action="{{ route('cambiar.contraseña') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="id_usuario" value="{{ $usuario->id_usuario }}">

                <div class="mb-3">
                    <label for="temporaryPassword" class="form-label fw-bold">Contraseña Temporal</label>
                    <input type="password" class="form-control" name="temporaryPassword" id="temporaryPassword" required minlength="6" autocomplete="current-password">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Nueva Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required minlength="8"
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" autocomplete="new-password">
                    <small class="text-muted">Debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.</small>
                </div>

                <div class="mb-3">
                    <label for="confirmPassword" class="form-label fw-bold">Confirmar Contraseña</label>
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required autocomplete="new-password">
                </div>

                <div class="d-grid gap-2 d-md-block text-center">
                    <a href="{{ route('login') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        &copy; 2025 Plataforma FCT. Todos los derechos reservados.
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirmPassword').value;

            if (password !== confirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'Las contraseñas no coinciden',
                    text: 'Por favor, verifique que ambas contraseñas sean iguales.',
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Contraseña actualizada',
                text: 'Su contraseña ha sido cambiada exitosamente.',
            }).then(() => {
                this.submit();
            });
        });
    </script>
</body>
</html>
