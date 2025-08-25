@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Crear Nuevo Usuario</h2>

    <form>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre">
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo">
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-select" id="rol">
                <option selected disabled>Seleccionar...</option>
                <option value="administrador">Administrador</option>
                <option value="academia">Academia</option>
                <option value="arbitro">Árbitro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">🟢 Guardar</button>
        <a href="{{ route('usuarios.edit') }}" class="btn btn-warning">✏️ Editar</a>
        <a href="" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>
@endsection
