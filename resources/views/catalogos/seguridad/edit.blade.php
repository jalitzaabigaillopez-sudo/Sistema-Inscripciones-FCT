@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Permiso de Seguridad</h2>

    <form>
        <div class="mb-3">
            <label for="modulo" class="form-label">Módulo</label>
            <input type="text" class="form-control" id="modulo" value="Academias">
        </div>
        <div class="mb-3">
            <label for="permiso" class="form-label">Permiso</label>
            <input type="text" class="form-control" id="permiso" value="Editar">
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Rol Asociado</label>
            <select class="form-select" id="rol">
                <option value="administrador" selected>Administrador</option>
                <option value="academia">Academia</option>
                <option value="arbitro">Árbitro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">✏️ Actualizar</button>
        <a href="{{ route('seguridad.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection
