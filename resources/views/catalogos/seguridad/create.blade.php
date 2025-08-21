@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Asignar Nuevo Permiso</h2>

    <form>
        <div class="mb-3">
            <label for="modulo" class="form-label">Módulo</label>
            <input type="text" class="form-control" id="modulo" placeholder="Ej. Academias">
        </div>
        <div class="mb-3">
            <label for="permiso" class="form-label">Permiso</label>
            <input type="text" class="form-control" id="permiso" placeholder="Ej. Ver, Editar, Eliminar">
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Rol Asociado</label>
            <select class="form-select" id="rol">
                <option selected disabled>Selecciona un rol</option>
                <option value="administrador">Administrador</option>
                <option value="academia">Academia</option>
                <option value="arbitro">Árbitro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">🟢 Guardar</button>
        <a href="#" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>
@endsection
