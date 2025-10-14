@extends('academia')

@section('content')
<div class="container py-4">

    {{-- 🔹 Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h4 class="fw-bold mb-0">Gestión de Atletas</h4>

        {{-- 🔍 Buscador --}}
        <form action="{{ route('registro-atletas.index') }}" method="GET" class="d-flex flex-wrap" style="max-width: 420px;">
            <input 
                type="text" 
                name="buscar" 
                class="form-control form-control-sm me-2 mb-2" 
                placeholder="Buscar atleta..." 
                value="{{ $busqueda ?? '' }}">
            <button class="btn btn-sm btn-outline-primary mb-2">
                <i class="bi bi-search"></i> Buscar
            </button>
            @if (!empty($busqueda))
                <a href="{{ route('registro-atletas.index') }}" class="btn btn-sm btn-outline-secondary ms-2 mb-2">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- 🔹 Botón Crear --}}
    <div class="text-end mb-3">
        <button hidden class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#crearModal">
            <i class="bi bi-person-plus"></i> Nuevo Atleta
        </button>
    </div>

    {{-- 🔹 Tabla --}}
    <div class="card table-card shadow">
        <div class="card-body p-3">
            <div class="table-responsive" style="overflow-x: auto;">
                <table id="tabla" class="table table-striped table-hover table-bordered text-center border">
                    <thead class="table-light">
                        <tr>
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
                            <td>{{ $atleta->nombre }} {{ $atleta->primer_apellido }} {{ $atleta->segundo_apellido }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $atleta->sexo === 'Masculino' ? '#0d6efd' : '#e83e8c' }};">
                                  {{ $atleta->sexo }}
                                </span>
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

            <span>Descargar <a href="{{ route('atletas.academia.pdf', $academia->id_academia) }}">PDF</a></span>
        </div>
    </div>

    {{-- 🔹 Paginación simple --}}
    @if ($atletas->hasPages())
    <nav class="d-flex justify-content-center mt-3">
        <ul class="pagination pagination-sm mb-0">
            @if ($atletas->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Anterior</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $atletas->previousPageUrl() }}">Anterior</a></li>
            @endif

            <li class="page-item active"><span class="page-link">{{ $atletas->currentPage() }}</span></li>

            @if ($atletas->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $atletas->nextPageUrl() }}">Siguiente</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
            @endif
        </ul>
    </nav>
    @endif

</div>

{{-- 🔸 Modal Crear --}}
<div class="modal fade" id="crearModal" tabindex="-1" aria-labelledby="crearLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="crearLabel">
                    <i class="bi bi-person-plus"></i> Registrar Nuevo Atleta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('registro-atletas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Tipo Identificación</label>
                            <input type="text" name="tipo_identificacion" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Identificación</label>
                            <input type="text" name="identificacion" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Primer Apellido</label>
                            <input type="text" name="primer_apellido" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Segundo Apellido</label>
                            <input type="text" name="segundo_apellido" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Sexo</label>
                            <select name="sexo" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 🔸 Scripts SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- 🔹 Éxito --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true
});
</script>
@endif

{{-- 🔹 Error --}}
@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ session('error') }}'
});
</script>
@endif

{{-- 🔹 Confirmar eliminación --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function() {
            const atletaId = this.dataset.id;
            Swal.fire({
                title: '¿Eliminar atleta?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/registro-atletas/${atletaId}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
