@extends('app')

@section('content')
<a href="{{ route('dashboard-academia') }}" class="btn btn-outline-primary float-end">
    Volver al Dashboard
</a>
<div class="container-fluid py-4">
  <h4 class="mb-4">📋 Datos del Atleta</h4>
  <div class="row g-4">
    
    {{-- Listado de atletas --}}
    <div class="col-12 col-md-7">
      <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Catálogo general</div>
        <div class="card-body">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Buscar atleta...">
            <button class="btn btn-outline-primary">Buscar</button>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-hover">
              <thead class="table-light">
                <tr>
                  <th>Cédula</th>
                  <th>Nombre completo</th>
                  <th>Año nacimiento</th>
                  <th>Género</th>
                  <th>Rol</th>
                  <th>Categoría</th>
                  <th>División</th>
                  <th>Cinturón</th>
                </tr>
              </thead>
              <tbody>
                {{-- Datos dinámicos aquí --}}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Formulario lateral --}}
    <div class="col-12 col-md-5">
      <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Formulario atleta</div>
        <div class="card-body">
          <form>
            @foreach([
              'Cédula', 'Nombre completo', 'Año nacimiento', 'Género',
              'Rol', 'Categoría', 'División', 'Cinturón'
            ] as $field)
            <div class="mb-2">
              <label>{{ $field }}</label>
              <input type="text" class="form-control">
            </div>
            @endforeach
            <div class="d-flex justify-content-between mt-3 flex-wrap gap-2">
              <button class="btn btn-success">Crear</button>
              <button class="btn btn-warning">Editar</button>
              <button class="btn btn-danger">Eliminar</button>
              <button class="btn btn-primary">Actualizar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

  {{-- Footer --}}
  <footer class="text-center text-muted small mt-4">
    <p>Log in as: <strong>Academia</strong></p>
    <p>© FCT 2025</p>
  </footer>
</div>
@endsection
</div>