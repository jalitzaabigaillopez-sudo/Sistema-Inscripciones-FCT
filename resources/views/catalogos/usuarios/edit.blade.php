@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Usuario</h2>

    <form>
        <div class="mb-3">
            <label for="identificacion" class="form-label">Identificación</label>
            <input type="text" class="form-control" id="identificacion" value="123456789">
        </div>
        <div class="mb-3">
            <label for="nombre_completo" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre_completo" value="Doris Vega">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" value="doris@email.com">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" value="••••••••">
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-select" id="rol">
                <option value="administrador" selected>Administrador</option>
                <option value="academia">Academia</option>
                <option value="arbitro">Árbitro</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado">
                <option value="activo" selected>Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="imagen">
        </div>

        <button type="submit" class="btn btn-warning">✏️ Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection
