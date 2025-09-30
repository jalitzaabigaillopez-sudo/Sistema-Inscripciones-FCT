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
                <img src="{{ asset('images/LogoFCT_transpa.png') }}" alt="Logo FCT" style="max-height: 60px;">
                <h2 class="text-2xl font-bold text-gray-800 mt-3">Cambiar Contraseña</h2>
                <p class="text-gray-600">Ingrese su contraseña temporal y cree una nueva contraseña segura.</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="changePasswordForm" action="{{ route('cambiar.contraseña') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="id_usuario" value="{{ $usuario->id_usuario }}">

                <div class="mb-3">
                    <label for="temporaryPassword" class="form-label fw-bold">Contraseña Temporal</label>
                    <input type="password" class="form-control" name="temporaryPassword" id="temporaryPassword" required minlength="6" autocomplete="new-password">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Nueva Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required minlength="8" autocomplete="new-password">
                    <small class="text-muted">Mínimo 8 caracteres, incluye mayúsculas, minúsculas y números.</small>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required minlength="8" autocomplete="new-password">
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
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            if(password !== confirm) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Las contraseñas no coinciden',
                    text: 'Por favor, asegúrese de que ambas contraseñas sean iguales.'
                });
            }
        });
    </script>
</body>
</html>
