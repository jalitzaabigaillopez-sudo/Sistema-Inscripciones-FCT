@extends('layouts.admin')

@section('content')
<a href="{{ route('adminDash') }}" class="btn btn-outline-primary float-end">
    Volver al Dashboard
</a>
<div class="container mt-4">
    <h2 class="mb-4">Configuración de Seguridad</h2>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalSeguridad">
        ➕ Nuevo Permiso
    </button>

    <div class="modal fade" id="modalSeguridad" tabindex="-1" aria-labelledby="modalSeguridadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSeguridadLabel">Asignar Permiso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
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
                                <option value="administrador">Administrador</option>
                                <option value="academia">Academia</option>
                                <option value="arbitro">Árbitro</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">💾 Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Módulo</th>
                <th>Permiso</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Academias</td>
                <td>Editar</td>
                <td>Administrador</td>
                <td>
                    <a href="{{ route('seguridad.show') }}" class="btn btn-sm btn-info">👁️ Ver</a>
                    <a href="{{ route('seguridad.edit') }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
