<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro</title>
    <!-- Opcional: Bootstrap v3.x si ya lo usas en tu proyecto -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background: #f7f7f9;
        }

        .panel {
            margin-top: 40px;
        }

        .help {
            font-size: 12px;
            color: #777;
        }

        .required::after {
            content: " *";
            color: #d9534f;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Inscripciones</h3>
                    </div>
                    <div class="panel-body">
                        <form id="consultaCedulaForm" onsubmit="return false;">

                            <div class="form-group">
                                <label for="">Tipo de atleta</label>
                                <select id="formSelect" class="form-control">
                                    <option value="">Tipo de matricula...</option>
                                    <option value="entrenador">Entrenador</option>
                                    <option value="asistente">Asistentes</option>
                                    <option value="atleta">Atletas</option>
                                </select>
                            </div>

                            <div class="text-right">
                            </div>
                        </form>
                    </div>
                </div>
                <div id="mensaje" class="alert" style="display:none;"></div>
            </div>

            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Inscripciones</h3>
                    </div>
                    <div class="panel-body">
                        <form id="consultaCedulaForm" onsubmit="return false;">

                            <div class="form-group">

                                <div id="form-entrenador" class="formulario d-none">
                                    <h4>Formulario Usuarios</h4>
                                    <select id="select-entrenador" class="form-control">
                                        <option value="">Seleccione un usuario...</option>
                                    </select>
                                </div>

                                <div id="form-asistente" class="formulario d-none">
                                    <h4>Formulario Departamentos</h4>
                                    <select id="select-asistente" class="form-control">
                                        <option value="">Seleccione un departamento...</option>
                                    </select>
                                </div>

                                <div id="form-atleta" class="formulario d-none">
                                    <h4>Formulario Productos</h4>
                                    <select id="select-atleta" class="form-control">
                                        <option value="">Seleccione un producto...</option>
                                    </select>
                                </div>

                            </div>

                            <div class="text-right">
                                <button type="button" id="btnGuardar" class="btn btn-primary">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/academiaMatriculaEntrendores/entrenadores.js') }}"></script>
</body>

</html>