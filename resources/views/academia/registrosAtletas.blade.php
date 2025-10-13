{{-- resources/views/academia/registrosAtletas.blade.php --}}
@extends('academia')

@section('title', 'Registros de Atletas')

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

    {{-- 🔹 Botón nuevo --}}
    <div class="text-end mb-3">
        <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#crearModal">
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

  {{-- Crear atleta Modal --}}
  <div
    class="modal fade"
    id="createAtletaModal"
    tabindex="-1"
    aria-labelledby="createAtletaLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('atletas.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="createAtletaLabel">Nuevo Atleta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input
                  type="text"
                  name="nombre"
                  class="form-control @error('nombre') is-invalid @enderror"
                  value="{{ old('nombre') }}"
                  required
                >
                @error('nombre')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Primer Apellido</label>
                <input
                  type="text"
                  name="primer_apellido"
                  class="form-control @error('primer_apellido') is-invalid @enderror"
                  value="{{ old('primer_apellido') }}"
                  required
                >
                @error('primer_apellido')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Segundo Apellido</label>
                <input
                  type="text"
                  name="segundo_apellido"
                  class="form-control @error('segundo_apellido') is-invalid @enderror"
                  value="{{ old('segundo_apellido') }}"
                >
                @error('segundo_apellido')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label class="form-label">Sexo</label>
                <select name="sexo" class="form-select @error('sexo') is-invalid @enderror" required>
                  <option value="">Seleccionar</option>
                  <option value="Masculino" {{ old('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                  <option value="Femenino" {{ old('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                </select>
                @error('sexo')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label">Edad</label>
                <input
                  type="number"
                  name="edad"
                  class="form-control @error('edad') is-invalid @enderror"
                  min="0"
                  max="120"
                  value="{{ old('edad') }}"
                  required
                >
                @error('edad')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label">Peso (kg)</label>
                <input
                  type="number"
                  step="0.01"
                  name="peso"
                  class="form-control @error('peso') is-invalid @enderror"
                  value="{{ old('peso') }}"
                >
                @error('peso')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label">Modalidad</label>
                <input
                  type="text"
                  name="modalidad"
                  class="form-control @error('modalidad') is-invalid @enderror"
                  value="{{ old('modalidad') }}"
                >
                @error('modalidad')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4">
                <label class="form-label">Participación</label>
                <input
                  type="text"
                  name="participacion"
                  class="form-control @error('participacion') is-invalid @enderror"
                  value="{{ old('participacion') }}"
                >
                @error('participacion')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Tipo</label>
                <input
                  type="text"
                  name="tipo"
                  class="form-control @error('tipo') is-invalid @enderror"
                  value="{{ old('tipo') }}"
                >
                @error('tipo')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Grupo</label>
                <input
                  type="text"
                  name="grupo"
                  class="form-control @error('grupo') is-invalid @enderror"
                  value="{{ old('grupo') }}"
                >
                @error('grupo')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-secondary"
              data-bs-dismiss="modal"
            >Cancelar</button>
            <button type="submit" class="btn btn-success">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Varios modales por atleta --}}
  @foreach($atletas as $atleta)
    {{-- Ver detalles --}}
    <div
      class="modal fade"
      id="viewAtletaModal{{ $atleta->id }}"
      tabindex="-1"
      aria-labelledby="viewAtletaLabel{{ $atleta->id }}"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5
              class="modal-title"
              id="viewAtletaLabel{{ $atleta->id }}"
            >
              Detalles de {{ $atleta->nombre }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p><strong>Nombre:</strong> {{ $atleta->nombre }} {{ $atleta->primer_apellido }} {{ $atleta->segundo_apellido }}</p>
            <p><strong>Sexo:</strong> {{ $atleta->sexo }}</p>
            <p><strong>Edad:</strong> {{ $atleta->edad }}</p>
            <p><strong>Peso:</strong> {{ $atleta->peso }} kg</p>
            <p><strong>Modalidad:</strong> {{ $atleta->modalidad }}</p>
            <p><strong>Participación:</strong> {{ $atleta->participacion }}</p>
            <p><strong>Tipo:</strong> {{ $atleta->tipo }}</p>
            <p><strong>Grupo:</strong> {{ $atleta->grupo }}</p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >Cerrar</button>
          </div>
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

    {{-- Editar atleta --}}
    <div
  class="modal fade"
  id="editAtletaModal{{ $atleta->id }}"
  tabindex="-1"
  aria-labelledby="editAtletaLabel{{ $atleta->id }}"
  aria-hidden="true"
>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form
        action="{{ route('atletas.update', $atleta) }}"
        method="POST"
      >
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5
            class="modal-title"
            id="editAtletaLabel{{ $atleta->id }}"
          >
            Editar {{ $atleta->nombre ?? 'Nombre'.$loop->iteration }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Nombre</label>
              <input
                type="text"
                name="nombre"
                value="{{ $atleta->nombre ?? 'Nombre'.$loop->iteration }}"
                class="form-control"
                required
              >
            </div>
            <div class="col-md-4">
              <label class="form-label">Primer Apellido</label>
              <input
                type="text"
                name="primer_apellido"
                value="{{ $atleta->primer_apellido ?? 'Apellido'.$loop->iteration }}"
                class="form-control"
                required
              >
            </div>
            <div class="col-md-4">
              <label class="form-label">Segundo Apellido</label>
              <input
                type="text"
                name="segundo_apellido"
                value="{{ $atleta->segundo_apellido ?? 'Segundo'.$loop->iteration }}"
                class="form-control"
              >
            </div>
            <div class="col-md-3">
              <label class="form-label">Sexo</label>
              <select name="sexo" class="form-select" required>
                <option value="Masculino" {{ ($atleta->sexo ?? ($loop->index % 2 == 0 ? 'Masculino' : 'Femenino')) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                <option value="Femenino" {{ ($atleta->sexo ?? ($loop->index % 2 == 0 ? 'Masculino' : 'Femenino')) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
              </select>
            </div>
             <div class="col-md-3">
          <label class="form-label">Edad</label>
          <input type="number" name="edad" value="{{ $atleta->edad }}" class="form-control" min="0" max="120" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Peso (kg)</label>
          <input type="number" step="0.01" name="peso" value="{{ $atleta->peso }}" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">Modalidad</label>
          <input type="text" name="modalidad" value="{{ $atleta->modalidad }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Participación</label>
          <input type="text" name="participacion" value="{{ $atleta->participacion }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Tipo</label>
          <input type="text" name="tipo" value="{{ $atleta->tipo }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Grupo</label>
          <input type="text" name="grupo" value="{{ $atleta->grupo }}" class="form-control">
        </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-secondary"
            data-bs-dismiss="modal"
          >Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
    {{-- Eliminar atleta --}}
    <div
      class="modal fade"
      id="deleteAtletaModal{{ $atleta->id }}"
      tabindex="-1"
      aria-labelledby="deleteAtletaLabel{{ $atleta->id }}"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form
            action="{{ route('atletas.destroy', $atleta) }}"
            method="POST"
          >
            @csrf
            @method('DELETE')
            <div class="modal-header">
              <h5
                class="modal-title"
                id="deleteAtletaLabel{{ $atleta->id }}"
              >
                Eliminar {{ $atleta->nombre }}
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              ¿Está seguro de que desea eliminar a {{ $atleta->nombre }}?
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-outline-secondary"
                data-bs-dismiss="modal"
              >Cancelar</button>
              <button type="submit" class="btn btn-danger">Eliminar</button>
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

@section('styles')
<style>
  .modal-lg {
    max-width: 800px;
  }
  .pagination {
    flex-wrap: wrap;
    justify-content: center;
    overflow-x: auto;
    max-width: 100%;
  }
</style>
@endsection