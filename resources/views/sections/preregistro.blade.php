<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Preregistro de Academia - FCT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
        }
        .center-card {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
        }
        .btn-primary {
            background-color: #222A59;
            border: none;
        }
        .btn-primary:hover {
            background-color: #2b4ba5;
        }
        .header {
            background-color: #000080;
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: bold;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
        .footer {
            background-color: #222A59;
            color: white;
            text-align: center;
            padding: 0.5rem;
            font-size: 0.9rem;
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }
    </style>
</head>

<body>
    <div class="center-card">
        <div class="card">
            <div class="header">
                Preregistro de Nueva Academia
            </div>
            <div class="p-4">
                <h4 class="text-center mb-3">Solicitud de Registro de Academias</h4>
                <form method="POST" action="#">
                    @csrf
                    <div class="mb-3">
                        <label for="nombreAcademia" class="form-label">Nombre de la Academia</label>
                        <input type="text" class="form-control" name="nombreAcademia" id="nombreAcademia" placeholder="Ej. Academia Taekwondo XYZ" required>
                    </div>
                    <div class="mb-3">
                        <label for="encargado" class="form-label">Nombre del Encargado</label>
                        <input type="text" class="form-control" name="encargado" id="encargado" placeholder="Ej. Guillermo Pérez" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo de Contacto</label>
                        <input type="email" class="form-control" name="correo" id="correo" placeholder="Ej. contacto@academia.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Ej. 8888-8888" required>
                    </div>
                    <div class="mb-3">
                        <label for="provincia" class="form-label">Provincia</label>
                        <select class="form-select" name="provincia" id="provincia" required>
                            <option value="" disabled selected>Seleccione una opción</option>
                            <option>San José</option>
                            <option>Alajuela</option>
                            <option>Cartago</option>
                            <option>Heredia</option>
                            <option>Guanacaste</option>
                            <option>Puntarenas</option>
                            <option>Limón</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentario (opcional)</label>
                        <textarea class="form-control" name="comentario" id="comentario" rows="3" placeholder="Motivo de la solicitud, trayectoria, etc."></textarea>
                    </div>
                    <!-- SweetAlert2 -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            document.getElementById('btnPreregistro').addEventListener('click', function (e) {
                                e.preventDefault();
                                Swal.fire({
                                    title: '¿Enviar solicitud?',
                                    text: "¿Está seguro de enviar la solicitud de preregistro?",
                                    icon: 'question',
                                    showCancelButton: true,
                                    preConfirm: () => {
                                        return new Promise((resolve) => {
                                            setTimeout(() => {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: '¡Solicitud enviada!',
                                                    text: 'La solicitud ha sido simulada correctamente.',
                                                    confirmButtonColor: '#222A59',
                                                    allowOutsideClick: false
                                                });
                                                resolve();
                                            }, 800);
                                        });
                                    },
                                    confirmButtonColor: '#222A59',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Sí, enviar',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: '¡Solicitud enviada!',
                                            text: 'La solicitud ha sido enviada por correo al administrador.',
                                            confirmButtonColor: '#222A59',
                                            allowOutsideClick: false
                                        }).then(() => {
                                            this.closest('form').submit();
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-primary px-4" id="btnPreregistro">Enviar Solicitud</button>
                        <a href="{{ route('login') }}" class="btn btn-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
            <div class="footer">
                &copy; 2025 Federación Costarricense de Taekwondo
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
