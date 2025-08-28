<!DOCTYPE html>
<html lang="en">

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
            margin-top: 20px;
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

    <!-- {{ $atletas }}
    {{ $evento }} -->
    <div class="container-fluid">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ $evento->nombre }}</h3>
            </div>
            <div class="panel-body">
                <form id="consultaCedulaForm" onsubmit="return false;">

                    <div class="form-group">
                        <label for="">Modalidades</label>
                        <select id="modalidades-select" class="form-control">
                            <option value="">Seleccionar modalidad</option>
                            @foreach($evento->modalidades as $modalidad)
                                <option value="{{ $modalidad->id_modalidad }}">{{ $modalidad->nombre }}</option>
                            @endforeach
                        </select>
                        <hr>
                        <label for="">SubModalidades</label>
                        <select id="subModalidades-select" class="form-control">
                            <option value="">Seleccionar submodalidad</option>

                        </select>
                    </div>

                    <div class="text-right">
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="">Lista de atletas disponibles</label>
                            <ul id="listaSeleccionados" class="list-group"></ul>
                        </div>
                        @foreach($atletas as $atleta)
                            @if ($atleta->rol === "atleta")
                                <li class="mb-3" data-id="{{ $atleta['id'] }}">
                                    {{ $atleta['nombre'] }} {{ $atleta['primer_apellido'] }}
                                    {{ $atleta['segundo_apellido'] }} - {{ $atleta['identificacion'] }}
                                    <button class="#">Agregar</button>
                                </li>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label id="labelSubModalidad" for="">Atletas por modalidad</label>
                            <ul id="listaSeleccionados" class="list-group"></ul>
                        </div>

                        <div class="text-right">
                            <form action="{{ route('inscripciones-parte2') }}" method="POST">
                                @csrf


                                <input type="hidden" name="ids" id="idsInput">
                                <button type="submit" id="btnContinuar" class="btn btn-primary">
                                    Guardar
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
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
    <script src="{{ asset('js/academiaMatricula/atletas.js') }}"></script>
    <script src="{{ asset('js/academiaMatricula/matriculaP2.js') }}"></script>
</body>

</html>