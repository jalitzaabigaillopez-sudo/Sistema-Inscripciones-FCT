@extends('app')

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
                <div class="table-responsive" style="width: 100%; overflow-x: auto;">
                    <table id="tabla" class="table table-striped table-hover table-bordered text-center border"
                        style="width: 100% !important;">
                        <thead class="table-light small">
                            <tr>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">Tipo evento</th>
                                <th class="text-center"></th>
                                <th class="text-center">Costo</th>
                                <th class="text-center">Inicio Ins.</th>
                                <th class="text-center">Fin Ins.</th>
                                <th class="text-center">Ins. Tardía</th>
                                <th class="text-center">Inicio</th>
                                <th class="text-center">Fin</th>
                                <th class="text-center">Estado</th>

                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            {{-- @foreach ($data as $evento)
                                <tr class="text-center">
                                    <td>{{ $evento->nombre }}</td>
                                    <td>{{ $evento->descripcion }}</td>
                                    <td>{{ $evento->tipoEvento->nombre ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->fecha_inicio_inscripcion)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->fecha_final_inscripcion)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->fecha_final)->format('d/m/Y') }}</td>
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
                                            data-bs-toggle="modal" data-bs-target="#modalEditarEvento"
                                            data-evento-id="{{ $evento->id_evento }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('eventos.destroy', $evento) }}" method="POST"
                                            id="form-eliminar-{{ $evento->id_evento }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                onclick="confirmarEliminacion({{ $evento->id_evento }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal CREAR --}}
        <div class="modal fade" id="modalEvento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalEventoLabel">
                            Crear Nuevo Evento
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        {{-- Add 'enctype' for file uploads --}}
                        <form id="create-event-form" method="POST" action="{{ route('eventos.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="text-secondary fw-bold mb-3">Información General</h6>
                                    <div class="mb-3">
                                        <label for="nombreEvento" class="form-label">Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="nombreEvento"
                                            name="nombre" value="{{ old('nombre') }}" required>
                                        @error('nombre')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="descripcionEvento" class="form-label">Descripción</label>
                                        <textarea class="form-control form-control-sm" id="descripcionEvento" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                                        @error('descripcion')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Costo --}}
                                    <div class="mb-3">
                                        <label for="costoEvento" class="form-label">Costo (₡) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                            class="form-control form-control-sm" id="costoEvento" name="costo" required>
                                        <div class="form-text text-muted">Ingrese el costo de inscripción
                                            del evento.</div>
                                        @error('costo')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="imagenEventoCrear" class="form-label">Imagen del Evento</label>
                                        <input class="form-control form-control-sm imagenEventoInput" type="file"
                                            id="imagenEventoCrear" name="imagen" accept="image/*">
                                        @error('imagen')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
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
                                            style="display: none;"> <i class="bi bi-trash"></i> Eliminar Foto</button>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-secondary fw-bold mb-3">Detalles del Evento</h6>
                                    <div class="mb-3">
                                        <label for="id_tipo_evento" class="form-label">Tipo de Evento <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="id_tipo_evento"
                                            name="id_tipo_evento" required>
                                            <option value="" selected disabled>Selecciona un tipo</option>
                                            @foreach ($tipoEvento as $tipo)
                                                <option value="{{ $tipo->id_tipo_evento }}" @selected(old('id_tipo_evento') == $tipo->id_tipo_evento)>
                                                    {{ $tipo->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_tipo_evento')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="modalidades" class="form-label fw-semibold text-success">
                                            <i class="bi bi-columns-gap me-2"></i> Modalidades
                                        </label>

                                        <div class="p-3 border rounded-3 bg-white shadow-sm"
                                            style="max-height: 200px; overflow-y: auto;">
                                            @forelse ($modalidades as $mod)
                                                <div class="form-check form-check-sm mb-2">
                                                    <input class="form-check-input" type="checkbox" name="modalidades[]"
                                                        value="{{ $mod->id_modalidad }}"
                                                        id="mod_{{ $mod->id_modalidad }}">
                                                    <label class="form-check-label" for="mod_{{ $mod->id_modalidad }}">
                                                        {{ $mod->nombre }}
                                                    </label>
                                                </div>
                                            @empty
                                                <div class="text-muted fst-italic">No hay modalidades disponibles.</div>
                                            @endforelse
                                        </div>

                                        <div class="form-text mt-1">
                                            <i class="bi bi-info-circle text-primary me-1"></i>
                                            Selecciona una o más modalidades que estarán disponibles en el evento.
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <h6 class="text-secondary fw-bold">Fechas de inscripciones</h6>
                                        <div class="col-md-6">
                                            <label for="fechaInicioInscripcion" class="form-label">Inicio de Inscripción
                                                <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="fechaInicioInscripcion" name="fecha_inicio_inscripcion"
                                                value="{{ old('fecha_inicio_inscripcion') }}" required>
                                            @error('fecha_inicio_inscripcion')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="fechaFinInscripcion" class="form-label">Fin de Inscripción <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="fechaFinInscripcion" name="fecha_final_inscripcion"
                                                value="{{ old('fecha_final_inscripcion') }}" required>
                                            @error('fecha_final_inscripcion')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="fechaFinInscripcionTardia" class="form-label">
                                                Fin de Inscripción Tardía
                                            </label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="fechaFinInscripcionTardia" name="fecha_final_inscripcion_tardia"
                                                value="{{ old('fecha_final_inscripcion_tardia') }}">
                                        </div>
                                        <h6 class="text-secondary fw-bold mb-3">Fechas del evento</h6>

                                        <div class="col-md-6">
                                            <label for="fechaInicio" class="form-label">Fecha de Inicio <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm" id="fechaInicio"
                                                name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                                            @error('fecha_inicio')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="fechaFin" class="form-label">Fecha Final <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm" id="fechaFin"
                                                name="fecha_final" value="{{ old('fecha_final') }}" required>
                                            @error('fecha_final')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3 mt-4">
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
            <div class="modal-dialog modal-lg modal-dialog-centered">
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
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="text-secondary fw-bold mb-3">Información General</h6>

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

                                    {{-- Costo --}}
                                    <div class="mb-3">
                                        <label for="editCostoEvento" class="form-label">Costo del Evento (₡) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-end-0">₡</span>
                                            <input type="number" class="form-control form-control-sm border-start-0"
                                                id="editCostoEvento" name="costo" min="0" step="0.01" oninput="this.value = this.value.replace(/[^0-9.,]/g, '')">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="imagenEventoEditar" class="form-label">Imagen del Evento</label>
                                        <input class="form-control form-control-sm imagenEventoInputEditar" type="file"
                                            id="imagenEventoEditar" name="imagen" accept="image/*">
                                        @error('imagen')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 d-flex flex-column align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                            style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                            <span class="previewText text-muted">Sin foto</span>
                                            <img class="previewImage img-thumbnail rounded-circle" src=""
                                                alt="Vista previa"
                                                style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                        </div>
                                        <input type="hidden" name="eliminar_imagen" id="eliminarImagenEvento"
                                            value="0">
                                        <button type="button" class="btn btn-sm btn-danger removeImageBtn"
                                            style="display: none;"> <i class="bi bi-trash"></i> Eliminar Foto</button>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label d-block">Estado <span
                                                class="text-danger">*</span></label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="estado"
                                                id="editEstadoActivo" value="activo" checked>
                                            <label class="form-check-label" for="editEstadoActivo">Activo</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="estado"
                                                id="editEstadoInactivo" value="inactivo">
                                            <label class="form-check-label" for="editEstadoInactivo">Inactivo</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="estado"
                                                id="editEstadoFinalizado" value="finalizado">
                                            <label class="form-check-label" for="editEstadoInactivo">Finalizado</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">

                                    <div class="row g-3">
                                        <h6 class="text-secondary fw-bold mb-2">Detalles del Evento</h6>
                                        <div class="col-md-12">
                                            <label for="editIdTipoEvento" class="form-label">Tipo de Evento <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="editIdTipoEvento"
                                                name="id_tipo_evento" required>
                                                @foreach ($tipoEvento as $tipo)
                                                    <option value="{{ $tipo->id_tipo_evento }}">{{ $tipo->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <h6 class="text-secondary fw-bold mb-2">Detalles del Evento</h6> --}}
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="editModalidades" class="form-label fw-semibold text-success">
                                                    <i class="bi bi-columns-gap me-2"></i> Modalidades del Evento
                                                </label>

                                                <div class="p-3 border rounded-3 bg-white shadow-sm"
                                                    style="max-height: 200px; overflow-y: auto;">
                                                    @forelse ($modalidades as $mod)
                                                        <div class="form-check form-check-sm mb-2">
                                                            <input class="form-check-input editModalidadCheckbox"
                                                                type="checkbox" name="modalidades[]"
                                                                value="{{ $mod->id_modalidad }}"
                                                                id="edit_mod_{{ $mod->id_modalidad }}">
                                                            <label class="form-check-label"
                                                                for="edit_mod_{{ $mod->id_modalidad }}">
                                                                {{ $mod->nombre }}
                                                            </label>
                                                        </div>
                                                    @empty
                                                        <div class="text-muted fst-italic">No hay modalidades disponibles.
                                                        </div>
                                                    @endforelse
                                                </div>

                                                <div class="form-text mt-1">
                                                    <i class="bi bi-info-circle text-primary me-1"></i>
                                                    Puedes seleccionar o deseleccionar modalidades para este evento.
                                                </div>
                                            </div>

                                        </div>
                                        <h6 class="text-secondary fw-bold">Fechas de inscripciones</h6>

                                        <div class="col-md-6">
                                            <label for="editFechaInicioInscripcion" class="form-label">Inicio de
                                                Inscripción <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="editFechaInicioInscripcion" name="fecha_inicio_inscripcion">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editFechaFinInscripcion" class="form-label">Fin de
                                                Inscripción <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="editFechaFinInscripcion" name="fecha_final_inscripcion">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="editFechaFinInscripcionTardia" class="form-label">
                                                Fin Inscripción Tardía
                                            </label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="editFechaFinInscripcionTardia" name="fecha_final_inscripcion_tardia">
                                            <div class="form-text text-muted small">
                                                Si se establece, permitirá inscripciones tardías hasta esta fecha.
                                            </div>
                                        </div>

                                        <h6 class="text-secondary fw-bold">Fechas del Evento</h6>

                                        <div class="col-md-6">
                                            <label for="editFechaInicio" class="form-label">Fecha de Inicio <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="editFechaInicio" name="fecha_inicio">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editFechaFin" class="form-label">Fecha Final <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm" id="editFechaFin"
                                                name="fecha_final">
                                        </div>


                                    </div>
                                </div>
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


    <script src="{{ asset('js/gestion_eventos.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>

    @section('scripts')
        <script>
            $(document).ready(function() {
                // Configuración de columnas para la tabla de Eventos
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
                    },
                    {
                        data: "nombre",
                        title: "Nombre"
                    },
                    {
                        data: "descripcion",
                        title: "Descripción",
                        render: function(data) {
                            if (!data) return '<span class="text-muted fst-italic">Sin descripción</span>';
                            let text = data.length > 40 ? data.substring(0, 40) + '...' : data;
                            return `<span data-bs-toggle="tooltip" title="${data}">${text}</span>`;
                        }
                    },
                    {
                        data: "tipo_evento",
                        title: "Tipo",
                        defaultContent: ""
                    },
                    {
                        data: "modalidades",
                        title: "Modalidades",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "costo",
                        title: "Costo",
                        defaultContent: ""
                    },
                    {
                        data: "fecha_inicio_inscripcion",
                        title: "Inicio Ins.",
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleDateString('es-ES') : '';
                        }
                    },
                    {
                        data: "fecha_final_inscripcion",
                        title: "Fin Ins.",
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleDateString('es-ES') : '';
                        }
                    },
                    {
                        data: "fecha_final_inscripcion_tardia",
                        title: "Ins. Tardía",
                        render: function(data, type, row) {
                            if (!data) {
                                return `<span class="text-muted fst-italic">Sin fecha tardía</span>`;
                            }
                            return data ? new Date(data).toLocaleDateString('es-ES') : '';
                        }
                    },
                    {
                        data: "fecha_inicio",
                        title: "Inicio",
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleDateString('es-ES') : '';
                        }
                    },
                    {
                        data: "fecha_final",
                        title: "Fin",
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleDateString('es-ES') : '';
                        }
                    },
                    {
                        data: "estado",
                        title: "Estado",
                        render: function(data) {
                            let badgeClass =
                                data === 'activo' ? 'success' :
                                data === 'inactivo' ? 'danger' :
                                data === 'en curso' ? 'warning':
                                'secondary';
                            return `<span class="badge bg-${badgeClass} rounded-pill text-capitalize">${data}</span>`;
                        }
                    },

                    {
                        data: "acciones",
                        title: "Acciones",
                        orderable: false,
                        searchable: false,
                        // Función para renderizar los botones de acción
                        render: function(data, type, row) {
                            let id_evento = row.id_evento;

                            return `
                         <div class="d-flex justify-content-center">
                            
                            <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit"
                                title="Editar" 
                                data-evento-id="${id_evento}">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="/eventos/${id_evento}" method="POST"
                                id="form-eliminar-${id_evento}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                    data-bs-toggle="tooltip" title="Eliminar Evento"
                                    onclick="confirmarEliminacion(${id_evento}, 'eventos')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                         </div>
                        `;
                        }
                    }
                ];

                // Pintar headers dinámicamente
                let headersRow = $('#tabla-headers');
                headersRow.empty();
                columnsConfig.forEach(col => {
                    headersRow.append(`<th class="text-center">${col.title}</th>`);
                });

                // Inicializar DataTable
                initDataTable({
                    ajaxUrl: "{{ route('eventos.index') }}",
                    columns: columnsConfig,
                    rowId: 'id_evento'
                });


            });
        </script>
    @endsection

    <script>
        // SweetAlert para mensajes de sesión
        @if (session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#3085d6'
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Error',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#3085d6'
                confirmButtonText: 'Aceptar'
            });
        @endif

        // Pasa la URL de la ruta a una variable global para que el JS la use
        const storeEventUrl = "{{ route('eventos.store') }}";
    </script>
@endsection
