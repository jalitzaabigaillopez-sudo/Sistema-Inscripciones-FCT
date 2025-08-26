<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro</title>
    <!-- Opcional: Bootstrap v3.x si ya lo usas en tu proyecto -->
    <!-- use bootstrap 5.3.7 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
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

    <!-- GUARDAR ACA EL ID DE ACADEMI -->
    <input type="hidden" value="1" id="id_academia">

    <!-- GUARDAR ACA EL ID DE EVENTO -->
    <input type="hidden" value="">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
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
                                    <option value="atleta">Atletas</option>
                                    <option value="asistente">Asistentes</option>
                                </select>
                            </div>

                            <div class="text-right">
                            </div>
                        </form>
                    </div>
                </div>
                <div id="mensaje" class="alert" style="display:none;"></div>
            </div>

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Inscripciones</h3>
                    </div>
                    <div class="panel-body">

                        <div id="form-entrenador" class="formulario d-none">
                            <h4>Formulario Entrenadores</h4>
                            <select id="select-entrenador" class="form-control" placeholde="buscar entrenador...">

                            </select>
                        </div>

                        <div id="form-asistente" class="formulario d-none">
                            <h4>Formulario Asistente</h4>
                            <select id="select-asistente" class="form-control">

                            </select>
                        </div>

                        <div id="form-atleta" class="formulario d-none">
                            <h4>Formulario Atletas</h4>
                            <select id="select-atleta" class="form-control">

                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <hr>
            <div class="col-md-6">
                <h4>Lista de matricula</h4>
                <ul id="listaSeleccionados" class="list-group"></ul>
            </div>
        </div>
    </div>


    <!-- use bootstrap 5.3.7 -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/academiaMatricula/entrenadores.js') }}"></script>
    <script src="{{ asset('js/academiaMatricula/listaMatricula.js') }}"></script>
</body>

</html>