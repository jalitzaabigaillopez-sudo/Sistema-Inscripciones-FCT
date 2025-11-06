@extends('app')

@section('tituloArriba')
    Administrar Atletas
@endsection

@section('breadcrumb-title', 'Lista de Atletas')

@section('content')


    <div class="container py-4">

        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de Atletas</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal" name="nuevoAtleta"
                data-bs-target="#modalAtleta">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Atleta
            </button>
        </div>
        <hr>

        <div class="d-flex justify-content-start align-items-center mb-2 flex-wrap gap-2">
            <a id="btnExportarPdf" class="btn btn-outline-danger btn-sm rounded-pill px-3 d-flex align-items-center">
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>
            <a id="btnExportarExcel" class="btn btn-outline-success btn-sm rounded-pill px-3 d-flex align-items-center">
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>
        </div>

        {{-- FILTROS --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold text-primary"> <i class="bi bi-funnel-fill me-2"></i>Filtros <span
                            id="contadorFiltros" class="badge bg-success ms-2">0</span> </h5> <button id="btnLimpiarFiltros"
                        class="btn btn-outline-danger btn-sm rounded-pill"> <i class="bi bi-arrow-counterclockwise"></i>
                        Limpiar filtros </button>
                </div>

                <div class="row g-3 justify-content-center text-center">
                    <!-- Tipo de identificación -->
                    <div class="col-12 col-sm-6 col-md-2">
                        <label for="filtroTipoIdentificacion" class="form-label fw-semibold">Tipo ID</label>
                        <select id="filtroTipoIdentificacion" class="form-select form-select-sm text-center">
                            <option value="">Todos</option>
                            <option value="Nacional">Nacional</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <!-- Sexo -->
                    <div class="col-12 col-sm-6 col-md-2">
                        <label for="filtroSexo" class="form-label fw-semibold">Sexo</label>
                        <select id="filtroSexo" class="form-select form-select-sm text-center">
                            <option value="">Todos</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Masculino">Masculino</option>
                        </select>
                    </div>

                    <!-- Grado -->
                    <div class="col-12 col-sm-6 col-md-2">
                        <label for="filtroGrado" class="form-label fw-semibold">Grado</label>
                        <select id="filtroGrado" class="form-select form-select-sm text-center">
                            <option value="">Todos</option>
                            @foreach ($grados as $grado)
                                <option value="{{ $grado->id_grado }}">{{ $grado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Academia (solo para administrador) -->
                    @if (isset($usuario) && $usuario->rol === 'administrador')
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="filtroAcademia" class="form-label fw-semibold">Academia</label>
                            <select id="filtroAcademia" class="form-select form-select-sm text-center">
                                <option value="">Todas</option>
                                @foreach ($academias as $aca)
                                    <option value="{{ $aca->id_academia }}">{{ $aca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Estado -->
                    <div class="col-12 col-sm-6 col-md-2">
                        <label for="filtroEstado" class="form-label fw-semibold">Estado</label>
                        <select id="filtroEstado" class="form-select form-select-sm text-center">
                            <option value="">Todos</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


        {{-- Tabla --}}
        <div class="card table-card shadow">
            <div class="card-body p-3">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="tabla" class="table table-striped table-hover table-bordered text-center border">

                        <thead class="table-light">
                            <tr id="tabla-headers"></tr>

                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal NUEVO ATLETA --}}
        <div class="modal fade" id="modalAtleta" tabindex="-1" aria-labelledby="modalAtletaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalAtletaLabel">Registrar
                            Nuevo Atleta</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" name="CerrarM"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div id="errorMessages" class="alert alert-danger d-none"></div>
                        <form method="POST" action="{{ route('atletas.store') }}" id="formRegistrarAtleta">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información Personal</h6>
                                    <div class="mb-3">
                                        <label for="tipo_identificacion" class="form-label">Tipo de Identificación <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="tipo_identificacion"
                                            name="tipo_identificacion" required>
                                            <option value="" disabled selected>Seleccione...</option>
                                            <option value="Nacional">Nacional</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="identificacion" class="form-label">Identificación <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="identificacion"
                                            name="identificacion" required placeholder="Ej. 123456789" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombres <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="nombre"
                                            name="nombre" required placeholder="Ej. Juan">
                                    </div>

                                    <div class="mb-3">
                                        <label for="primer_apellido" class="form-label">Primer Apellido <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="primer_apellido"
                                            name="primer_apellido" required placeholder="Ej. Pérez">
                                    </div>
                                    <div class="mb-3">
                                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                        <input type="text" class="form-control form-control-sm" id="segundo_apellido"
                                            name="segundo_apellido" placeholder="Ej. Gómez">
                                    </div>

                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-sm" id="fecha_nacimiento"
                                            name="fecha_nacimiento" required>
                                    </div>

                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Información Deportiva</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- <div class="mb-3">
                                                <label for="rol" class="form-label">Rol <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" id="rol" name="rol"
                                                    required>
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    <option value="atleta">Atleta</option>
                                                    <option value="entrenador">Entrenador</option>
                                                    <option value="asistente">Asistente</option>
                                                </select>
                                            </div> --}}
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="sexo" class="form-label">Sexo <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" id="sexo" name="sexo"
                                                    required>
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    <option value="Masculino">Masculino</option>
                                                    <option value="Femenino">Femenino</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="id_grado" class="form-label">Grado (Cinturón) <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" id="id_grado" name="id_grado"
                                                    required>
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    @foreach ($grados as $grado)
                                                        <option value="{{ $grado->id_grado }}">{{ $grado->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="division" class="form-label">División</label>
                                                <input type="text" class="form-control form-control-sm" id="division"
                                                    name="division" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="id_academia" class="form-label">Academia <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" id="id_academia"
                                                    name="id_academia" required>
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    @foreach ($academias as $academia)
                                                        <option value="{{ $academia->id_academia }}">
                                                            {{ $academia->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <label for="fotoAtletaCrear" class="form-label">Foto de Perfil</label>
                                        <input class="form-control form-control-sm fotoAtletaInput" type="file"
                                            id="fotoAtletaCrear" name="imagen" accept="image/*">
                                    </div>
                                    <div class="mb-3 d-flex flex-column align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                            style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                            <span class="previewText text-muted">Sin foto</span>
                                            <img class="previewImage img-thumbnail rounded-circle" src=""
                                                alt="Vista previa"
                                                style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger removeImageBtn"
                                            name="eliminarFoto" style="display: none;"><i class="bi bi-trash"></i>
                                            Eliminar Foto</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2" name="cancelar"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill" form="formRegistrarAtleta"
                            id="btnGuardarAtleta">Guardar
                            Atleta</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDITAR ATLETA --}}
        <div class="modal fade" id="modalEditarAtleta" tabindex="-1" aria-labelledby="modalEditarAtletaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarAtletaLabel">
                            Actualizar Datos del Atleta</h5>

                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            name="cerrar" aria-label="Cerrar"></button>
                    </div>
                    <form method="POST" action="" id="formEditarAtleta" data-id="">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-0">
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información Personal</h6>
                                    <div class="mb-3">
                                        <label for="e_tipo_identificacion" class="form-label">Tipo de Identificación <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="e_tipo_identificacion"
                                            name="tipo_identificacion" required>
                                            <option value="" disabled selected>Seleccione...</option>
                                            <option value="Nacional">Nacional</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="e_identificacion" class="form-label">Identificación <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="e_identificacion"
                                            name="identificacion" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="e_nombre" class="form-label">Nombres <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="e_nombre"
                                            name="nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="e_apellido1" class="form-label">Primer Apellido <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="e_apellido1"
                                            name="primer_apellido" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="e_apellido2" class="form-label">Segundo Apellido</label>
                                        <input type="text" class="form-control form-control-sm" id="e_apellido2"
                                            name="segundo_apellido">
                                    </div>

                                    <div class="mb-3">
                                        <label for="e_fecha_nacimiento" class="form-label">Fecha de Nacimiento <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-sm"
                                            id="e_fecha_nacimiento" name="fecha_nacimiento" required>
                                    </div>


                                </div>

                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Información Deportiva</h6>

                                    <div class="row">
                                        {{-- <div class="col-md-6 mb-3">
                                            <label for="e_rol" class="form-label">Rol <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="e_rol" name="rol"
                                                required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                <option value="atleta">Atleta</option>
                                                <option value="entrenador">Entrenador</option>
                                                <option value="asistente">Asistente</option>
                                            </select>
                                        </div> --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="e_sexo" class="form-label">Sexo <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="e_sexo" name="sexo"
                                                required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">


                                        <div class="col-md-6 mb-3">
                                            <label for="e_grado" class="form-label">Grado (Cinturón) <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="e_grado" name="id_grado"
                                                required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($grados as $grado)
                                                    <option value="{{ $grado->id_grado }}">{{ $grado->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="division" class="form-label">División</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="e_division" name="division" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label d-block">Estado <span
                                                    class="text-danger">*</span></label>
                                            <div class="d-flex gap-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="estado"
                                                        id="e_estado_activo" value="activo" required>
                                                    <label class="form-check-label" for="e_estado_activo">Activo</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="estado"
                                                        id="e_estado_inactivo" value="inactivo" required>
                                                    <label class="form-check-label"
                                                        for="e_estado_inactivo">Inactivo</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12 mb-3">
                                            <label for="e_academia" class="form-label">Academia <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="e_academia" name="id_academia"
                                                required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($academias as $academia)
                                                    <option value="{{ $academia->id_academia }}">{{ $academia->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="mb-2">
                                                <label for="e_fotoAtletaEditar" class="form-label">Foto de Perfil</label>
                                                <input class="form-control form-control-sm fotoAtletaInput" type="file"
                                                    id="e_fotoAtletaEditar" name="imagen" accept="image/*">
                                            </div>
                                            <div class="mb-3 d-flex flex-column align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                                    style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                                    <span class="previewText text-muted">Sin foto</span>
                                                    <img class="previewImage img-thumbnail rounded-circle" src=""
                                                        alt="Vista previa"
                                                        style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger removeImageBtn"
                                                    name="eliminarFoto" style="display: none;"><i
                                                        class="bi bi-trash"></i> Eliminar
                                                    Foto</button>
                                                <input type="hidden" name="remove_imagen" id="removeImagen"
                                                    value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" name="cancelar"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success rounded-pill">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="{{ asset('js/gestion_atleta.js') }}"></script>
        <script src="{{ asset('js/datatable.js') }}"></script>

        {{-- Aquí va el JS específico de esta tabla --}}
    @section('scripts')
        <script>
            $(document).ready(function() {

                // 1) Definir globalmente la función que el datatable.js espera
                //    (datatable.js usaba: if (typeof extraAjaxData === 'function') extraAjaxData(d); )
                window.extraAjaxData = function(d) {
                    // agregar los parámetros de filtros al objeto d (DataTables)
                    d.tipo_identificacion = $('#filtro_tipo_identificacion').val();
                    d.sexo = $('#filtro_sexo').val();
                    d.id_grado = $('#filtro_grado').val();
                    // si el select de academia no existe en la vista, esto devolverá undefined (OK)
                    d.id_academia = $('#filtro_academia').length ? $('#filtro_academia').val() : null;
                };

                // 2) Columnas: usar exactamente los nombres que tu controlador devuelve en $formattedData
                let columnsConfig = [{
                        data: "imagen",
                        title: "Foto",
                        orderable: false,
                        render: function(data) {
                            if (data) {
                                return `<img src="${data}" alt="Foto" class="rounded-circle" width="45" height="45" style="object-fit:cover;">`;
                            } else {
                                return `<i class="bi bi-person-circle text-secondary" style="font-size: 2rem;"></i>`;
                            }
                        }
                    },
                    {
                        data: "tipo_identificacion",
                        title: "Tipo ID"
                    },
                    {
                        data: "identificacion",
                        title: "ID"
                    },
                    {
                        data: "nombre",
                        title: "Nombre"
                    },
                    {
                        data: "sexo",
                        title: "Sexo"
                    },
                    {
                        data: "fecha_nacimiento",
                        title: "Fecha N."
                    },
                    {
                        data: "grado",
                        title: "Grado",
                        orderable: false
                    },
                    {
                        data: "academia",
                        title: "Academia",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "estado",
                        title: "Estado",
                        render: function(data) {
                            let badgeClass = data === 'activo' ? 'success' : (data === 'inactivo' ? 'danger' :
                                'secondary');
                            return `<span class="badge bg-${badgeClass} rounded-pill text-capitalize">${data}</span>`;
                        }
                    },
                    {
                        data: "acciones",
                        title: "Acciones",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-warning rounded-circle btn-edit" data-id="${data}" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger rounded-circle" onclick="confirmarEliminacion(${data})" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>`;
                        }
                    }
                ];

                // pintar headers dinámicos (si lo quieres mantener)
                let headersRow = $('#tabla-headers');
                headersRow.empty();
                columnsConfig.forEach(col => headersRow.append(`<th class="text-center">${col.title}</th>`));

                // 3) Inicializar DataTable con tu initDataTable (no cambiamos datatable.js)
                //    Asegurate que initDataTable utiliza la lógica AJAX que invoca la función global extraAjaxData
                const tabla = initDataTable({
                    ajaxUrl: "{{ route('atletas.index') }}",
                    columns: columnsConfig,
                });

                let filtros = {
                    tipo_identificacion: '',
                    sexo: '',
                    id_grado: '',
                    id_academia: '',
                    estado: ''
                };

                // función para contar filtros activos
                function actualizarContadorFiltros() {
                    const activos = Object.values(filtros).filter(v => v !== '').length;
                    $('#contadorFiltros').text(activos);
                }

                // manejar cambios en filtros
                $('#filtroTipoIdentificacion, #filtroSexo, #filtroGrado, #filtroAcademia, #filtroEstado').on('change',
                    function() {
                        filtros.tipo_identificacion = $('#filtroTipoIdentificacion').val();
                        filtros.sexo = $('#filtroSexo').val();
                        filtros.id_grado = $('#filtroGrado').val();
                        filtros.id_academia = $('#filtroAcademia').val();
                        filtros.estado = $('#filtroEstado').val();

                        actualizarContadorFiltros();
                        $('#tabla').DataTable().ajax.reload();
                    });

                // botón para limpiar filtros
                $('#btnLimpiarFiltros').on('click', function() {
                    $('#filtroTipoIdentificacion, #filtroSexo, #filtroGrado, #filtroAcademia, #filtroEstado')
                        .val('');
                    filtros = {
                        tipo_identificacion: '',
                        sexo: '',
                        id_grado: '',
                        id_academia: '',
                        estado: ''
                    };
                    actualizarContadorFiltros();
                    $('#tabla').DataTable().ajax.reload();
                });

                // pasar los filtros al DataTable (sin tocar el archivo datatable.js)
                $.fn.dataTable.ext.errMode = 'none';
                $(document).on('preXhr.dt', function(e, settings, data) {
                    Object.assign(data, filtros);
                });


                // 4) Listeners: al cambiar cualquiera de los selects, recarga la tabla (con los filtros actuales)
                $(document).on('change', '#filtro_tipo_identificacion, #filtro_sexo, #filtro_grado, #filtro_academia',
                    function() {
                        // recargar (si la datatable ya está inicializada)
                        if ($.fn.DataTable.isDataTable('#tabla')) {
                            $('#tabla').DataTable().ajax.reload();
                        }
                    });

            });

            function getFiltrosUrl() {
                const params = new URLSearchParams();

                const tipoIdentificacion = $('#filtroTipoIdentificacion').val();
                const sexo = $('#filtroSexo').val();
                const idGrado = $('#filtroGrado').val();
                const idAcademia = $('#filtroAcademia').val();
                const estado = $('#filtroEstado').val();

                if (tipoIdentificacion) params.append('tipo_identificacion', tipoIdentificacion);
                if (sexo) params.append('sexo', sexo);
                if (idGrado) params.append('id_grado', idGrado);
                if (idAcademia) params.append('id_academia', idAcademia);
                if (estado) params.append('estado', estado);

                return params.toString();
            }

            document.getElementById('btnExportarPdf').addEventListener('click', () => {
                const query = getFiltrosUrl();
                window.location.href = `/reportes/atletas/pdf?${query}`;
            });

            document.getElementById('btnExportarExcel').addEventListener('click', () => {
                const query = getFiltrosUrl();
                window.location.href = `/reportes/atletas/excel?${query}`;
            });
        </script>
    @endsection


@endsection
