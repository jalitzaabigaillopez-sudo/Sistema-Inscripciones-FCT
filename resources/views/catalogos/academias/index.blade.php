@extends('app')

@section('tituloArriba')
    Administrar Academias
@endsection

@section('breadcrumb-title', 'Lista de Academias')

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

        {{-- Tabla --}}
        <div class="card table-card shadow">
            <div class="card-body p-3">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="tabla" class="table table-striped table-hover table-bordered text-center border">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Profesor a cargo</th>
                                <th class="text-center">Correo</th>
                                <th class="text-center">Teléfono</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Ubicación</th>
                                <th class="text-center">Dirección</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

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
                        </tbody>
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
                        <form>
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información General</h6>
                                    <div class="mb-3">
                                        <label for="nombreAcademia" class="form-label">Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="nombreAcademia"
                                            placeholder="Ej. Academia Taekwondo Central" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="profesorAcademia" class="form-label">Profesor Encargado <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="profesorAcademia"
                                            placeholder="Ej. Guillermo Pérez" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefonoAcademia" class="form-label">Teléfono <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="telefonoAcademia"
                                            placeholder="Ej. +506 8888-8888" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="correoAcademia" class="form-label">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm" id="correoAcademia"
                                            placeholder="Ej. academia@email.com" required>
                                    </div>
                                </div>

                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Ubicación</h6>
                                    <div class="mb-3">
                                        <label for="provinciaAcademia" class="form-label">Provincia <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="provinciaAcademia" required>
                                            <option value="" disabled selected>Seleccione una provincia...</option>
                                            <option value="San Jose">San José</option>
                                            <option value="Alajuela">Alajuela</option>
                                            <option value="Cartago">Cartago</option>
                                            <option value="Heredia">Heredia</option>
                                            <option value="Guanacaste">Guanacaste</option>
                                            <option value="Puntarenas">Puntarenas</option>
                                            <option value="Limon">Limón</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cantonAcademia" class="form-label">Cantón <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="cantonAcademia" required>
                                            <option value="" disabled selected>Seleccione un cantón...</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="distritoAcademia" class="form-label">Distrito <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="distritoAcademia" required>
                                            <option value="" disabled selected>Seleccione un distrito...</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="direccionAcademia" class="form-label">Dirección <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="direccionAcademia"
                                            placeholder="Ej. Santa Teresa, calle 13" required>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill">Guardar Academia</button>
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
                            Actualizar Datos de la Academia
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="{{ route('academias.update', $item->id_academia) }}"
                            id="formEditarAcademia" data-id="{{ $item->id_academia }}"
                            data-canton-id="{{ $item->distrito->canton->id_canton }}"
                            data-distrito-id="{{ $item->id_distrito }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información General</h6>
                                    <div class="mb-3">
                                        <label for="nombreAcademiaEditar" class="form-label">Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="nombreAcademiaEditar" name="nombre" value="{{ $item->nombre }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="profesorAcademiaEditar" class="form-label">Profesor Encargado <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="profesorAcademiaEditar" name="profesor_encargado"
                                            value="{{ $item->profesor_encargado }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefonoAcademiaEditar" class="form-label">Teléfono <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="telefonoAcademiaEditar" name="telefono" value="{{ $item->telefono }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="correoAcademiaEditar" class="form-label">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm"
                                            id="correoAcademiaEditar" name="correo" value="{{ $item->correo }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estadoAcademiaEditar" class="form-label">Estado <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="estadoAcademiaEditar"
                                            name="estado" required>
                                            <option value="activo" {{ $item->estado == 'activo' ? 'selected' : '' }}>
                                                Activo</option>
                                            <option value="inactivo" {{ $item->estado == 'inactivo' ? 'selected' : '' }}>
                                                Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Ubicación</h6>
                                    <div class="mb-3">
                                        <label for="provinciaAcademiaEditar" class="form-label">Provincia <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="provinciaAcademiaEditar"
                                            name="provincia" required>
                                            <option value="" disabled>Seleccione una provincia...</option>
                                            @foreach ($provincias as $provincia)
                                                <option value="{{ $provincia->id_provincia }}"
                                                    {{ $item->distrito->canton->provincia->id_provincia == $provincia->id_provincia ? 'selected' : '' }}>
                                                    {{ $provincia->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cantonAcademiaEditar" class="form-label">Cantón <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="cantonAcademiaEditar"
                                            name="canton" required>
                                            <option value="" disabled selected>Seleccione un cantón...</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="distritoAcademiaEditar" class="form-label">Distrito <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="distritoAcademiaEditar"
                                            name="distrito" required>
                                            <option value="" disabled selected>Seleccione un distrito...</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="direccionAcademiaEditar" class="form-label">Dirección <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="direccionAcademiaEditar" name="direccion" value="{{ $item->direccion }}"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill" form="formEditarAcademia">Guardar
                            cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/editar_academia.js') }}"></script>

    <script></script>
@endsection