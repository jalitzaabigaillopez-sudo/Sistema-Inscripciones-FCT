<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Academia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            max-width: 650px;
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
            <div class="header">Registro de Academia</div>
            <div class="p-4">
                <form method="POST" action="#">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Academia</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="profesor_encargado" class="form-label">Profesor Encargado</label>
                        <input type="text" class="form-control" name="profesor_encargado" id="profesor_encargado" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" name="direccion" id="direccion" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo" id="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" id="telefono" maxlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" name="estado" id="estado" required>
                            <option value="" disabled selected>Seleccione una opción</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_usuario" class="form-label">Usuario Responsable</label>
                        <input type="number" class="form-control" name="id_usuario" id="id_usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_distrito" class="form-label">Distrito</label>
                        <select class="form-select" name="id_distrito" id="id_distrito" required>
                            <option value="" disabled selected>Seleccione un distrito</option>
                            <option value="1">Siquirres</option>
                            <option value="2">Matina</option>
                            <option value="3">Guápiles</option>
                            <!-- Agrega más según tu base de datos -->
                        </select>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-primary px-4">Registrar</button>
                        <button type="reset" class="btn btn-secondary px-4">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="footer">
                &copy; 2025 Federación Costarricense de Taekwondo
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
