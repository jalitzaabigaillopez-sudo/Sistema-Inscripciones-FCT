<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title >Preregistro de Academia - FCT</title>

    <!-- Bootstrap CSS (v5.3.3) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .header-image {
            background: linear-gradient(to bottom, rgba(0, 0, 128, 0.8), rgba(0, 0, 128, 0.5)),
                url('https://img.olympicchannel.com/images/image/private/t_16-9_1920/f_auto/primary/lo6iwcfrrjtw8kqcff1b');
            background-size: cover;
            background-position: center;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .footer {
            background-color: #222A59;
            color: white;
            padding: 1rem;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 0.9rem;
        }

        .custom-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.40);
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            max-width: 630px;
            margin: 2rem auto;
            transition: transform 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-8px);
        }

        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            padding: 0.75rem;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #222A59;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.2);
            outline: none;
        }

        .btn-primary {
            background-color: #222A59;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #2b4ba5;
        }

        .btn-secondary {
            background-color: #6b7280;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-secondary:hover {
            background-color: #5a606b;
        }

        @media (max-width: 576px) {
            .custom-card {
                margin: 1rem;
            }

            .header-image {
                height: 120px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
     <!-- Header -->
    <div class="header-image"></div>
    
    <div class="container py-5">
        <div class="custom-card">
             <div class="p-4 text-center">
                <img src="{{ asset('images/LogoFCT_transpa.png') }}" class="mx-auto mb-4" alt="Logo FCT"
                    style="max-height: 60px;">

    <h2 class="text-2xl font-bold text-gray-800 mb-3">Preregistro de Nueva Academia</h2>

    <div class="card p-4">
        <h4 class="text-lg font-bold mb-3 text-start">Datos de la Academia solicitante</h4>
        <form method="POST" action="#">
            @csrf
            <div class="mb-3 text-start">
                <label for="nombreAcademia" class="form-label">Nombre de la Academia</label>
                <input type="text" class="form-control" name="nombreAcademia" id="nombreAcademia" placeholder="Ej. Academia Taekwondo XYZ" required>
            </div>

            <div class="mb-3 text-start">
                <label for="encargado" class="form-label">Nombre del Encargado</label>
                <input type="text" class="form-control" name="encargado" id="encargado" placeholder="Ej. Guillermo Pérez" required>
            </div>

            <div class="mb-3 text-start">
                <label for="correo" class="form-label">Correo de Contacto</label>
                <input type="email" class="form-control" name="correo" id="correo" placeholder="Ej. contacto@academia.com" required>
            </div>

            <div class="mb-3 text-start">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Ej. 8888-8888" required>
            </div>

            <div class="mb-3 text-start">
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

            <div class="mb-3 text-start">
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
                                // Simulación: no enviar formulario, solo mostrar mensaje de éxito
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
                                // Mostrar mensaje de enviado antes de enviar el formulario
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

            <button type="button" class="btn btn-primary px-4" id="btnPreregistro">Enviar Solicitud</button>
            <a href="{{ route('registro.nuevo') }}" class="btn btn-secondary px-4">Nuevo Registro</a>
            <a href="{{ route('login') }}" class="btn btn-secondary px-4">Cancelar</a>
        </form>
    </div>
         </div>
        </div>
    </div>

     <div class="footer">
        &copy; 2025 Plataforma FCT. Todos los derechos reservados.
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
