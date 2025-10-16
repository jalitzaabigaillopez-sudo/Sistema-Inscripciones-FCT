// ...existing code...
@extends('academia')

@section('content')
<div class="container py-4">

    {{-- 🔹 Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h4 class="fw-bold mb-0">Gestión de Atletas</h4>

    {{-- 🔹 Botón Crear --}}
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearModal">
            <i class="bi bi-plus-lg"></i> Nuevo Atleta
        </button>

        {{-- 🔸 Modal Crear --}}
        <div class="modal fade" id="crearModal" tabindex="-1" aria-labelledby="crearLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="crearLabel">
                            <i class="bi bi-plus-lg"></i> Nuevo Atleta
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('registro-atletas.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            {{-- Formulario --}}
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label fw-semibold">Tipo Identificación</label>
                                    <input type="text" name="tipo_identificacion" class="form-control" required>
                                </div>  
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔹 Tabla dentro de card con buscador en header y paginación en footer --}}
    <div class="card table-card shadow">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <div>
                
                <div class="text-muted small">Mostrando
                    {{ method_exists($atletas, 'count') ? $atletas->count() : 0 }}
                    de
                    {{ method_exists($atletas, 'total') ? $atletas->total() : (is_countable($atletas) ? count($atletas) : 0) }}
                    registros
                </div>
            </div>

            {{-- 🔍 Buscador dentro del card header --}}
            <form action="{{ route('registro-atletas.index') }}" method="GET" class="d-flex align-items-center" style="min-width:260px;">
                <input
                    type="text"
                    name="buscar"
                    class="form-control form-control-sm me-2"
                    placeholder="Buscar atleta..."
                    value="{{ $busqueda ?? '' }}">
                <button class="btn btn-sm btn-outline-primary me-2">
                    <i class="bi bi-search"></i>
                </button>
                @if (!empty($busqueda))
                    <a href="{{ route('registro-atletas.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto;">
                <table id="tabla" class="table table-striped table-hover table-bordered text-center border mb-0">
                    <thead class="table-light">
                       <tr id="tabla-headers">
                            <th>ID</th>
                            <th>Identificación</th>
                            <th>Nombre Completo</th>
                            <th>Sexo</th>
                            <th>Fecha Nacimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($atletas as $atleta)
                        <tr>
                            <td class="fw-semibold">{{ $atleta->id_atleta }}</td>
                            <td>{{ $atleta->identificacion }}</td>
                            <td class="text-start">{{ $atleta->nombre }} {{ $atleta->primer_apellido }} {{ $atleta->segundo_apellido }}</td>
                            <td>
                               
                                  {{ $atleta->sexo }}
                               
                            </td>
                            <td>{{ \Carbon\Carbon::parse($atleta->fecha_nacimiento)->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editarModal{{ $atleta->id_atleta }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" data-id="{{ $atleta->id_atleta }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- 🔸 Modal Editar --}}
                        <div class="modal fade" id="editarModal{{ $atleta->id_atleta }}" tabindex="-1" aria-labelledby="editarLabel{{ $atleta->id_atleta }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title fw-bold" id="editarLabel{{ $atleta->id_atleta }}">
                                            <i class="bi bi-pencil"></i> Editar Atleta
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('registro-atletas.update', $atleta->id_atleta) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            {{-- Formulario --}}
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label class="form-label fw-semibold">Tipo Identificación</label>
                                                    <input type="text" name="tipo_identificacion" class="form-control" value="{{ $atleta->tipo_identificacion }}">
                                                </div>
                                                <div class="col">
                                                    <label class="form-label fw-semibold">Identificación</label>
                                                    <input type="text" name="identificacion" class="form-control" value="{{ $atleta->identificacion }}">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label class="form-label fw-semibold">Primer Apellido</label>
                                                    <input type="text" name="primer_apellido" class="form-control" value="{{ $atleta->primer_apellido }}">
                                                </div>
                                                <div class="col">
                                                    <label class="form-label fw-semibold">Segundo Apellido</label>
                                                    <input type="text" name="segundo_apellido" class="form-control" value="{{ $atleta->segundo_apellido }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Nombre</label>
                                                <input type="text" name="nombre" class="form-control" value="{{ $atleta->nombre }}">
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label fw-semibold">Sexo</label>
                                                    <select name="sexo" class="form-select">
                                                        <option value="Masculino" {{ $atleta->sexo == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                                        <option value="Femenino" {{ $atleta->sexo == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label fw-semibold">Fecha Nacimiento</label>
                                                    <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $atleta->fecha_nacimiento }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-muted py-3">No hay atletas registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card footer con paginación y contador --}}
        <div class="card-footer d-flex flex-wrap justify-content-between align-items-center">
            <div class="small text-muted">
                Mostrando
                <strong>{{ method_exists($atletas, 'firstItem') ? $atletas->firstItem() : (method_exists($atletas, 'count') ? ($atletas->count() ? 1 : 0) : 0) }}</strong>
                -
                <strong>{{ method_exists($atletas, 'lastItem') ? $atletas->lastItem() : (method_exists($atletas, 'count') ? $atletas->count() : 0) }}</strong>
                de
                <strong>{{ method_exists($atletas, 'total') ? $atletas->total() : (is_countable($atletas) ? count($atletas) : 0) }}</strong>
                registros
            </div>

            <div>
                @if (method_exists($atletas, 'links'))
                    {{-- Enlaces de paginación (Bootstrap) --}}
                    <nav aria-label="Page navigation">
                        {{ $atletas->links('pagination::bootstrap-5') }}
                    </nav>
                @else
                    {{-- Paginación manual simple si no es LengthAwarePaginator --}}
                    <nav class="d-flex">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                            <li class="page-item active"><span class="page-link">1</span></li>
                            <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>

    <span class="d-block mt-2">Descargar <a href="{{ route('atletas.academia.pdf', $academia->id_academia) }}">PDF</a></span>
</div>


@endsection