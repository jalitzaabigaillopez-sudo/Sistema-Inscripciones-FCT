@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Academia</h2>

    <form>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Academia</label>
            <input type="text" class="form-control" id="nombre" value="Academia Central">
        </div>
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" class="form-control" id="ubicacion" value="Siquirres">
        </div>
        <div class="mb-3">
            <label for="profesor" class="form-label">Profesor encargado</label>
            <input type="text" class="form-control" id="profesor" value="Profesor 1">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" value="8888-8888">
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" value="academia@email.com">
        </div>

        <button type="submit" class="btn btn-warning">✏️ Actualizar</button>
        <a href="{{ route('academias.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection
