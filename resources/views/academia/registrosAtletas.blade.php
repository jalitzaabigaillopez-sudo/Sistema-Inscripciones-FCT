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
          @forelse($atletas as $i => $atleta)
            <tr>
              <td>{{ $atletas->firstItem() + $i }}</td>
              <td>
                {{ $atleta->nombre }}
                {{ $atleta->primer_apellido }}
                {{ $atleta->segundo_apellido ?? '' }}
              </td>
              <td>{{ $atleta->sexo }}</td>
              <td>{{ $atleta->edad ?? '—' }}</td>
              <td>{{ $atleta->peso ? $atleta->peso.' kg' : '—' }}</td>
              <td>{{ ucfirst($atleta->modalidad ?? '—') }}</td>
              <td>{{ ucfirst($atleta->participacion ?? '—') }}</td>
              <td>{{ ucfirst($atleta->tipo ?? '—') }}</td>
              <td>{{ $atleta->grupo ?? '—' }}</td>
              <td class="text-nowrap">
                {{-- Ver --}}
                <button
                  class="btn btn-sm btn-info"
                  title="Ver"
                  data-bs-toggle="modal"
                  data-bs-target="#viewAtletaModal{{ $atleta->id }}"
                >
                  <i class="bi bi-eye"></i>
                </button>
                {{-- Editar --}}
                <button
                  class="btn btn-sm btn-warning"
                  title="Editar"
                  data-bs-toggle="modal"
                  data-bs-target="#editAtletaModal{{ $atleta->id }}"
                >
                  <i class="bi bi-pencil"></i>
                </button>
                {{-- Eliminar --}}
                <button
                  class="btn btn-sm btn-danger"
                  title="Eliminar"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteAtletaModal{{ $atleta->id }}"
                >
                  <i class="bi bi-trash"></i>
                </button>
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
                class="form-control"
                required
              >
            </div>
            <div class="col-md-4">
              <label class="form-label">Primer Apellido</label>
              <input
                type="text"
                name="primer_apellido"
                class="form-control"
                required
              >
            </div>
            <div class="col-md-4">
              <label class="form-label">Segundo Apellido</label>
              <input
                type="text"
                name="segundo_apellido"
                class="form-control"
              >
            </div>

            <div class="col-md-3">
              <label class="form-label">Sexo</label>
              <select name="sexo" class="form-select" required>
                <option value="">Seleccionar</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Edad</label>
              <input
                type="number"
                name="edad"
                class="form-control"
                min="0"
                max="120"
                required
              >
            </div>
            <div class="col-md-3">
              <label class="form-label">Peso (kg)</label>
              <input
                type="number"
                step="0.01"
                name="peso"
                class="form-control"
              >
            </div>
            <div class="col-md-3">
              <label class="form-label">Modalidad</label>
              <input
                type="text"
                name="modalidad"
                class="form-control"
              >
            </div>

            <div class="col-md-4">
              <label class="form-label">Participación</label>
              <input
                type="text"
                name="participacion"
                class="form-control"
              >
            </div>
            <div class="col-md-4">
              <label class="form-label">Tipo</label>
              <input
                type="text"
                name="tipo"
                class="form-control"
              >
            </div>
            <div class="col-md-4">
              <label class="form-label">Grupo</label>
              <input
                type="text"
                name="grupo"
                class="form-control"
              >
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
              Editar {{ $atleta->nombre }}
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
                  value="{{ $atleta->nombre }}"
                  class="form-control"
                  required
                >
              </div>
              <div class="col-md-4">
                <label class="form-label">Primer Apellido</label>
                <input
                  type="text"
                  name="primer_apellido"
                  value="{{ $atleta->primer_apellido }}"
                  class="form-control"
                  required
                >
              </div>
              <div class="col-md-4">
                <label class="form-label">Segundo Apellido</label>
                <input
                  type="text"
                  name="segundo_apellido"
                  value="{{ $atleta->segundo_apellido }}"
                  class="form-control"
                >
              </div>

              <div class="col-md-3">
                <label class="form-label">Sexo</label>
                <select name="sexo" class="form-select" required>
                  <option
                    {{ $atleta->sexo=='Masculino' ? 'selected' : '' }}
                    value="Masculino"
                  >Masculino</option>
                  <option
                    {{ $atleta->sexo=='Femenino' ? 'selected' : '' }}
                    value="Femenino"
                  >Femenino</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Edad</label>
                <input
                  type="number"
                  name="edad"
                  value="{{ $atleta->edad }}"
                  class="form-control"
                  min="0"
                  max="120"
                  required
                >
              </div>
              <div class="col-md-3">
                <label class="form-label">Peso (kg)</label>
                <input
                  type="number"
                  step="0.01"
                  name="peso"
                  value="{{ $atleta->peso }}"
                  class="form-control"
                >                                       
                </div>
                <div class="col-md-3">
                    <label class="form-label">Modalidad</label>
                    <input
                      type="text"
                      name="modalidad"
                      value="{{ $atleta->modalidad }}"          
                        class="form-control"
                    >
                </div>  
                <div class="col-md-4">
                    <label class="form-label">Participación</label>
                    <input
                      type="text"
                      name="participacion"
                      value="{{ $atleta->participacion }}"
                      class="form-control"      
                    >
                </div>  
                <div class="col-md-4">
                    <label class="form-label">Tipo</label>          
                    <input
                      type="text"
                      name="tipo"
                      value="{{ $atleta->tipo }}"
                      class="form-control"
                    >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Grupo</label>
                    <input
                        type="text"
                        name="grupo"
                        value="{{ $atleta->grupo }}"
                        class="form-control"
                    >
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
              <input type="hidden" name="_method" value="PUT">
            </div>
            </form>
        </div>
    </div>
</div>
    {{-- Eliminar atleta --}}
    <div
        class="modal fade
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
                        <input type="hidden" name="_method" value="DELETE">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('scripts')

<script>
  // Reabrir modal si hay errores de validación
  @if ($errors->any())
    var createAtletaModal = new bootstrap.Modal(document.getElementById('createAtletaModal'));
    createAtletaModal.show();
  @endif
</script>
@endsection
<script>
  document.addEventListener('DOMContentLoaded', () => {
    @if($errors->any() && old('_method') == 'PUT')
      new bootstrap.Modal(document.getElementById('editAtletaModal{{ old("id") }}')).show();
    @elseif($errors->any())
      new bootstrap.Modal(document.getElementById('createAtletaModal')).show();
    @endif
  });
</script>
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
