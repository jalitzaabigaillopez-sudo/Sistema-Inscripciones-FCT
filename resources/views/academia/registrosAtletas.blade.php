{{-- resources/views/academia/registrosAtletas.blade.php --}}
@extends('academia')

@section('title', 'Registros de Atletas')

@section('content')
<div class="container py-4">

  {{-- Encabezado + Botón “Nuevo” abre modal --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Registros de Atletas</h1>
    <button
      class="btn btn-success"
      data-bs-toggle="modal"
      data-bs-target="#createAtletaModal"
    >
      <i class="bi bi-plus-circle me-1"></i> Nuevo Atleta
    </button>
  </div>

  {{-- Mensaje flash --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Tabla de atletas --}}
  <div class="card shadow-sm mb-5">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Sexo</th>
            <th>Edad</th>
            <th>Peso</th>
            <th>Modalidad</th>
            <th>Participación</th>
            <th>Tipo</th>
            <th>Grupo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($atletas as $atleta)
            <tr>
              <td>{{ $atletas->firstItem() + $loop->index }}</td>
              <td>
                {{ $atleta->nombre }}
                {{ $atleta->primer_apellido }}
                {{ $atleta->segundo_apellido ?? '' }}
              </td>
             <td>
            {{ $atleta->sexo
                ?? ($loop->index % 2 == 0 ? 'Masculino' : 'Femenino') }}
        </td>
        <td>
            {{ ($atleta->edad !== null && $atleta->edad !== '') 
                ? $atleta->edad 
                : (18 + $loop->index) }}
        </td>
        <td>
            {{ ($atleta->peso !== null && $atleta->peso !== '') 
                ? $atleta->peso.' kg' 
                : (60 + $loop->index).' kg' }}
        </td>
        <td>
            {{ $atleta->modalidad 
                ? ucfirst($atleta->modalidad) 
                : ($loop->index % 2 == 0 ? 'Combate' : 'Poomsae') }}
        </td>
         <td>
            {{ $atleta->participacion 
                ? ucfirst($atleta->participacion) 
                : ($loop->index % 2 == 0 ? 'Individual' : 'Equipo') }}
        </td>
        <td>
            {{ $atleta->tipo 
                ? ucfirst($atleta->tipo) 
                : ($loop->index % 3 == 0 ? 'Atleta' : 'Entrenador') }}
        </td>
        <td>
            {{ $atleta->grupo ?? chr(65 + ($loop->index % 5)) }}
        </td>
              <td class="text-center">
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill"
            type="button" data-bs-toggle="dropdown">
            <i class="bi bi-three-dots"></i>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewAtletaModal{{ $atleta->id }}">
                    <i class="bi bi-pencil-square"></i> Editar
                </a>
            </li>
            <li>
                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $atleta->id }}">
                    <i class="bi bi-trash"></i> Eliminar
                </a>
            </li>
        </ul>
    </div>
</td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center py-4">
                No se encontraron atletas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{-- Paginación --}}
    <div class="card-footer">
      <div class="d-flex justify-content-center">
        {{ $atletas->onEachSide(1)->links('pagination::bootstrap-5') }}
      </div>
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
  @endforeach

</div>

</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    @if ($errors->any())
      new bootstrap.Modal(document.getElementById('createAtletaModal')).show();
    @endif
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