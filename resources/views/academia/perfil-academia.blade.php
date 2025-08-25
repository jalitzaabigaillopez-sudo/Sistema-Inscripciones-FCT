@extends('app')

@section('content')
<a href="{{ route('dashboard-academia') }}" class="btn btn-outline-primary float-end">
    Volver al Dashboard
</a>
<div class="container-fluid py-4">
  <h4 class="mb-4">👤 Perfil Academia</h4>
  <div class="row justify-content-center">
    <div class="col-12 col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Ajustes de perfil</div>
        <div class="card-body">
          <form>
            @foreach([
              'Cédula', 'Nombre completo', 'Correo', 'Contraseña', 'Rol', 'Estado'
            ] as $field)
            <div class="mb-3">
              <label>{{ $field }}</label>
              <input type="{{ $field === 'Contraseña' ? 'password' : 'text' }}" class="form-control">
            </div>
            @endforeach
            <div class="d-flex justify-content-between flex-wrap gap-2 mt-3">
              <button class="btn btn-success">Crear</button>
              <button class="btn btn-warning">Editar</button>
              <button class="btn btn-danger">Eliminar</button>
              <button class="btn btn-primary">Actualizar</button>
            </div>
          </form>
        </div>
      </div>

      {{-- Footer --}}
      <footer class="text-center text-muted small mt-4">
        <p>Log in as: <strong>Academia</strong></p>
        <p>© FCT 2025</p>
      </footer>
    </div>
  </div>
</div>
@endsection
