@extends('layouts.app')

@section('tituloArriba')
    Administrar Eventos
@endsection

@section('breadcrumb-title', 'Lista de Eventos')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de Eventos</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal"
                data-bs-target="#modalEvento">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Evento
            </button>
        </div>
        <hr>

        {{-- Tabla --}}
        <div class="card table-card shadow">
            <div class="card-body p-3">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="tabla" class="table table-striped table-hover table-bordered text-center border">
                        <thead class="table-light small">
                            <tr>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">Tipo evento</th>
                                <th class="text-center">Inicio Ins.</th>
                                <th class="text-center">Fin Ins.</th>
                                <th class="text-center">Inicio</th>
                                <th class="text-center">Fin</th>
                                <th class="text-center">Imagen</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $evento)
                                <tr class="text-center">
                                    <td>{{ $evento->nombre }}</td>
                                    <td>{{ $evento->descripcion }}</td>
                                    <td>{{ $evento->tipoEvento->nombre ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->fecha_inicio_inscripcion)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->fecha_final_inscripcion)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->fecha_final)->format('d/m/Y') }}</td>
                                    <td>{{ $evento->imagen }}</td>
                                    <td>
                                        @if ($evento->estado === 'activo')
                                            <span class="badge rounded-pill bg-success">
                                                {{ ucfirst($evento->estado) }}
                                            </span>
                                        @elseif($evento->estado === 'inactivo')
                                            <span class="badge rounded-pill bg-danger">
                                                {{ ucfirst($evento->estado) }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary">
                                                {{ ucfirst($evento->estado) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarEvento">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('eventos.destroy', $evento) }}" method="POST"
                                            id="form-eliminar-{{ $evento->id }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                onclick="confirmarEliminacion({{ $evento->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal CREAR --}}
        <div class="modal fade" id="modalEvento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalEventoLabel">
                            Crear Nuevo Evento
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="{{ route('eventos.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="nombreEvento" class="form-label">Nombre <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="nombreEvento" name="nombre"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcionEvento" class="form-label">Descripción</label>
                                <textarea class="form-control form-control-sm" id="descripcionEvento" name="descripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="id_tipo_evento" class="form-label">Tipo de Evento <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="id_tipo_evento" name="id_tipo_evento"
                                    required>
                                    <option selected disabled>Selecciona un tipo</option>
                                    {{-- @foreach ($tipos_evento as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach --}}
                                </select>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="fechaInicioInscripcion" class="form-label">Fecha Inicio Inscripción</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaInicioInscripcion"
                                        name="fecha_inicio_inscripcion">
                                </div>
                                <div class="col-md-6">
                                    <label for="fechaFinInscripcion" class="form-label">Fecha Fin Inscripción</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaFinInscripcion"
                                        name="fecha_fin_inscripcion">
                                </div>
                                <div class="col-md-6">
                                    <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaInicio"
                                        name="fecha_inicio">
                                </div>
                                <div class="col-md-6">
                                    <label for="fechaFin" class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaFin"
                                        name="fecha_fin">
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="imagen" class="form-label">Imagen (URL)</label>
                                <input type="text" class="form-control form-control-sm" id="imagen"
                                    name="imagen">
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select form-select-sm" id="estado" name="estado">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar Evento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal EDITAR --}}
        <div class="modal fade" id="modalEditarEvento" tabindex="-1" aria-labelledby="modalEditarEventoLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarEventoLabel">
                            Editar Evento
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" id="formEditarEvento">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="editNombreEvento" class="form-label">Nombre <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editNombreEvento"
                                    name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDescripcionEvento" class="form-label">Descripción</label>
                                <textarea class="form-control form-control-sm" id="editDescripcionEvento" name="descripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="editIdTipoEvento" class="form-label">Tipo de Evento <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="editIdTipoEvento" name="id_tipo_evento"
                                    required>
                                    {{-- @foreach ($tipos_evento as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach --}}
                                </select>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="editFechaInicioInscripcion" class="form-label">Fecha Inicio
                                        Inscripción</label>
                                    <input type="date" class="form-control form-control-sm"
                                        id="editFechaInicioInscripcion" name="fecha_inicio_inscripcion">
                                </div>
                                <div class="col-md-6">
                                    <label for="editFechaFinInscripcion" class="form-label">Fecha Fin Inscripción</label>
                                    <input type="date" class="form-control form-control-sm"
                                        id="editFechaFinInscripcion" name="fecha_fin_inscripcion">
                                </div>
                                <div class="col-md-6">
                                    <label for="editFechaInicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control form-control-sm" id="editFechaInicio"
                                        name="fecha_inicio">
                                </div>
                                <div class="col-md-6">
                                    <label for="editFechaFin" class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control form-control-sm" id="editFechaFin"
                                        name="fecha_fin">
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="editImagen" class="form-label">Imagen (URL)</label>
                                <input type="text" class="form-control form-control-sm" id="editImagen"
                                    name="imagen">
                            </div>
                            <div class="mb-3">
                                <label for="editEstado" class="form-label">Estado</label>
                                <select class="form-select form-select-sm" id="editEstado" name="estado">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Script para editar y eliminar --}}
    <script>
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $('#editNombreEvento').val($(this).data('nombre'));
            $('#editDescripcionEvento').val($(this).data('descripcion'));
            $('#editIdTipoEvento').val($(this).data('id_tipo_evento'));
            $('#editFechaInicioInscripcion').val($(this).data('fecha_inicio_inscripcion'));
            $('#editFechaFinInscripcion').val($(this).data('fecha_fin_inscripcion'));
            $('#editFechaInicio').val($(this).data('fecha_inicio'));
            $('#editFechaFin').val($(this).data('fecha_fin'));
            $('#editImagen').val($(this).data('imagen'));
            $('#editEstado').val($(this).data('estado'));
            $('#formEditarEvento').attr('action', '/eventos/' + id);
        });

        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $('#form-eliminar-' + id).attr('action'),
                        method: $('#form-eliminar-' + id).attr('method'),
                        data: $('#form-eliminar-' + id).serialize(),
                        success: function(response) {
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: 'El evento ha sido eliminado correctamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al intentar eliminar el evento.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
