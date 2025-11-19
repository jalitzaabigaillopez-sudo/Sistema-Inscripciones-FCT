@extends('app')

@section('tituloArriba')
    Administrar Academias
@endsection

@section('breadcrumb-title', 'Lista de Academias')

@if (session('alerta'))
    <script>
        mostrarAlerta("{{ session('alerta') }}", "Aviso", "⚠️");
    </script>
@endif

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de Academias</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal"
                data-bs-target="#modalAcademia">
                <i class="bi bi-plus-circle me-1"></i> Nueva Academia
            </button>
        </div>
        <hr>

        <div class="d-flex justify-content-start align-items-center mb-2 flex-wrap gap-2">
            <a href="{{ route('academias.pdf') }}"
                class="btn btn-outline-danger btn-sm rounded-pill px-3 d-flex align-items-center">
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>
            <a href="{{ route('academias.excel') }}"
                class="btn btn-outline-success btn-sm rounded-pill px-3 d-flex align-items-center">
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>
        </div>

        {{-- Tabla --}}
        <div class="card table-card shadow">
            <div class="card-body p-3">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="tabla" class="table table-striped table-hover table-bordered text-center border">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Imagen</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Profesor a cargo</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Correo</th>
                                <th class="text-center">Teléfono</th>
                                <th class="text-center">Ubicación</th>
                                <th class="text-center">Dirección</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        {{-- <tbody>

                            @foreach ($data as $item)
                                <tr class="text-center">
                                    <td class="small">{{ $item->nombre }}</td>
                                    <td class="small">{{ $item->profesor_encargado }}</td>
                                    <td class="small">{{ $item->correo }}</td>
                                    <td class="small">{{ $item->telefono }}</td>
                                    <td class="small">{{ $item->usuario->nombre_completo }}</td>
                                    <td class="small">
                                        {{ $item->distrito->canton->provincia->nombre ?? 'Sin provincia' }},
                                        {{ $item->distrito->canton->nombre ?? 'Sin cantón' }},
                                        {{ $item->distrito->nombre ?? 'Sin distrito' }}
                                    </td>
                                    <td class="small">{{ $item->direccion }}</td>
                                    <td>
                                        @if ($item->estado === 'activo')
                                            <span class="badge rounded-pill bg-success">
                                                {{ ucfirst($item->estado) }}
                                            </span>
                                        @elseif($item->estado === 'inactivo')
                                            <span class="badge rounded-pill bg-danger">
                                                {{ ucfirst($item->estado) }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary">
                                                {{ ucfirst($item->estado) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item btn-edit" href="#"
                                                        data-id="{{ $item->id_academia }}" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarAcademia">
                                                        <i class="bi bi-pencil-square"></i> Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('academias.destroy', $item) }}" method="POST"
                                                        id="form-eliminar-{{ $item->id_academia }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item text-danger"
                                                            data-bs-toggle="tooltip" title="Eliminar Academia"
                                                            onclick="confirmarEliminacion({{ $item->id_academia }})">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal CREAR ACADEMIA --}}
        <div class="modal fade" id="modalAcademia" tabindex="-1" aria-labelledby="modalAcademiaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered ">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalAcademiaLabel">
                            Registrar Nueva
                            Academia</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="{{ route('pre.registro.academia') }}" enctype="multipart/form-data"
                            id="formCrearAcademia">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-7 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información General</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombreAcademia" class="form-label">Nombre <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" id="nombreAcademia"
                                                name="nombre" placeholder="Ej. Academia Central" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="telefonoAcademia" class="form-label">Teléfono <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" id="telefonoAcademia"
                                                name="telefono" placeholder="88888888" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="profesorAcademia" class="form-label">Profesor Encargado <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" id="profesorAcademia"
                                                name="profesor_encargado" placeholder="Ej. Guillermo Pérez" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="correoAcademia" class="form-label">Correo Electrónico <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control form-control-sm" id="correoAcademia"
                                                name="email" placeholder="Ej. academia@email.com" required>
                                        </div>
                                    </div>


                                    <div class="mb-3 mt-3">
                                        <label for="fotoAcademiaCrear" class="form-label">Foto de Perfil</label>
                                        <input class="form-control form-control-sm fotoAcademiaInput" type="file"
                                            id="fotoAcademiaCrear" name="imagen" accept="image/*">
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
                                            style="display: none;"><i class="bi bi-trash"></i> Eliminar Foto</button>
                                    </div>

                                </div>

                                <div class="col-md-5 ps-md-4">
                                    <h6 class="text-secondary mb-3">Ubicación</h6>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="provinciaAcademia" class="form-label">Provincia <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="provinciaAcademia"
                                                name="provincia_id" required>
                                                <option value="" disabled selected>Seleccione una provincia...
                                                </option>
                                                @foreach ($provincias as $provincia)
                                                    <option value="{{ $provincia->id_provincia }}">
                                                        {{ $provincia->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="cantonAcademia" class="form-label">Cantón <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="cantonAcademia"
                                                name="canton_id" required>
                                                <option value="" disabled selected>Seleccione un cantón...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="distritoAcademia" class="form-label">Distrito <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="distritoAcademia"
                                                name="distrito_id" required>
                                                <option value="" disabled selected>Seleccione un distrito...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="direccionAcademia" class="form-label">Dirección <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="direccionAcademia" name="direccion"
                                                placeholder="Ej. Santa Teresa, calle 13" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill">Guardar Academia</button>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal EDITAR ACADEMIA --}}
    <div class="modal fade" id="modalEditarAcademia" tabindex="-1" aria-labelledby="modalEditarAcademiaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarAcademiaLabel">
                        Editar Academia
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body p-0">
                    <form id="formEditarAcademia" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-7 border-end pe-md-4">
                                <h6 class="text-secondary mb-3">Información General</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombreAcademiaEditar" class="form-label">Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="nombreAcademiaEditar" name="nombre" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="telefonoAcademiaEditar" class="form-label">Teléfono <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="telefonoAcademiaEditar" name="telefono" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="profesorAcademiaEditar" class="form-label">Profesor Encargado <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="profesorAcademiaEditar" name="profesor_encargado" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="correoAcademiaEditar" class="form-label">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm"
                                            id="correoAcademiaEditar" name="correo" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="usuarioAcademiaEditar" class="form-label">Usuario <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="usuarioAcademiaEditar" name="usuario" readonly>
                                    </div>

                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="fotoAcademiaEditar" class="form-label">Foto de Perfil</label>
                                    <input class="form-control form-control-sm fotoAcademiaInput" type="file"
                                        id="fotoAcademiaEditar" name="imagen" accept="image/*">
                                </div>

                                <div class="mb-3 d-flex flex-column align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                        style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; overflow: hidden;">
                                        <span class="previewText text-muted">Sin foto</span>
                                        <img class="previewImage img-thumbnail rounded-circle" src=""
                                            alt="Vista previa"
                                            style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger removeImageBtn"
                                        style="display: none;">
                                        <i class="bi bi-trash"></i> Eliminar Foto
                                    </button>
                                    <input type="hidden" name="remove_imagen" id="removeImagen" value="0">
                                </div>
                            </div>

                            <div class="col-md-5 ps-md-4">
                                <h6 class="text-secondary mb-3">Ubicación</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="provinciaAcademiaEditar" class="form-label">Provincia <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="provinciaAcademiaEditar"
                                            name="provincia" required>
                                            <option value="" disabled selected>Seleccione una provincia...</option>
                                            @foreach ($provincias as $provincia)
                                                <option value="{{ $provincia->id_provincia }}">{{ $provincia->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="cantonAcademiaEditar" class="form-label">Cantón <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="cantonAcademiaEditar"
                                            name="canton" required>
                                            <option value="" disabled selected>Seleccione un cantón...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="distritoAcademiaEditar" class="form-label">Distrito <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="distritoAcademiaEditar"
                                            name="distrito" required>
                                            <option value="" disabled selected>Seleccione un distrito...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="direccionAcademiaEditar" class="form-label">Dirección <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="direccionAcademiaEditar" name="direccion" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">Estado <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="estado"
                                                id="estado_activo" value="activo" required>
                                            <label class="form-check-label" for="estado_activo">Activo</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="estado"
                                                id="estado_inactivo" value="inactivo" required>
                                            <label class="form-check-label" for="estado_inactivo">Inactivo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formEditarAcademia" class="btn btn-primary rounded-pill">Guardar
                        cambios</button>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/gestionar_academias.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>

@section('scripts')

    <script>
        $(document).ready(function() {
            let columnsConfig = [{
                    data: "imagen",
                    title: "Imagen",
                    orderable: false,
                    render: function(data) {
                        const size = 45; // tamaño consistente
                        if (data) {
                            return `
                                <div class="d-flex align-items-center justify-content-center"
                                    style="width:${size}px; height:${size}px; margin:auto;">
                                    <img src="${data}" 
                                        alt="Foto" 
                                        class="rounded-circle border shadow-sm" 
                                        width="${size}" height="${size}" 
                                        style="object-fit: cover;">
                                </div>
                            `;
                        } else {
                            return `
                                <div class="d-flex align-items-center justify-content-center rounded-circle border bg-light shadow-sm mx-auto"
                                    style="width:${size}px; height:${size}px; color:#6c757d;">
                                    <i class="bi bi-calendar-event" style="font-size:1.2rem;"></i>
                                </div>
                            `;
                        }
                    }
                }, {
                    data: "nombre",
                    title: "Nombre"
                },
                {
                    data: "profesor_encargado",
                    title: "Profesor a cargo"
                },
                {
                    data: "usuario",
                    title: "Usuario"
                },
                {
                    data: "correo",
                    title: "Correo"
                },
                {
                    data: "telefono",
                    title: "Teléfono"
                },
                {
                    data: "ubicacion",
                    title: "Ubicación",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "direccion",
                    title: "Dirección"
                },
                {
                    data: "estado",
                    title: "Estado",
                    orderable: false,
                    searchable: false
                },
                {
                    data: null,
                    title: "Acciones",
                    orderable: false,
                    searchable: false,
                    render: function(row) {
                        let botones = `
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-warning rounded-pill btn-edit"
                                data-id="${row.id_academia}"
                                title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                    `;

                        // Mostrar botón extra SOLO si la academia está inactiva
                        if (row.estado_raw === "inactivo") {
                            botones += `
                        <button class="btn btn-sm btn-info rounded-pill"
                            onclick="invalidarYReenviar(${row.id_academia})"
                            title="Invalidar proceso y reenviar correo">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    `;
                        }

                        botones += `</div>`;
                        return botones;
                    }
                }

            ];

            initDataTable({
                ajaxUrl: "{{ route('academias.index') }}",
                columns: columnsConfig
            });
        });

        function invalidarYReenviar(id) {
            Swal.fire({
                title: "¿Invalidar proceso actual?",
                html: "Esto cancelará el proceso de activación actual<br>y enviará un nuevo correo con una nueva contraseña temporal.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, reiniciar proceso",
                cancelButtonText: "Cancelar",
                confirmButtonColor: "#d33"
            }).then(result => {
                if (!result.isConfirmed) return;

                $.post(`/academias/${id}/invalidar-proceso`, {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .done(resp => {
                        if (!resp.success) {
                            Swal.fire("Aviso", resp.message, "warning");
                            return;
                        }

                        Swal.fire("Proceso reiniciado", resp.message, "success");
                        $('#tabla').DataTable().ajax.reload(null, false);
                    })
                    .fail(xhr => {
                        Swal.fire("Error", "Error inesperado al reiniciar el proceso.", "error");

                        // Errores reales sí se muestran
                        console.error("Error inesperado:", xhr);
                    });
            });
        }
    </script>
@endsection

@endsection
