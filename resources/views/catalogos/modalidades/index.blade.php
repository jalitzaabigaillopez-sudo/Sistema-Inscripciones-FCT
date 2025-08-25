@extends('layouts.app')

@section('content')
<a href="{{ route('adminDash') }}" class="btn btn-outline-primary float-end">
    Volver al Dashboard
</a>
<div class="container mt-4">
    <h2 class="mb-4">Listado de Modalidades</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalModalidad">
        ➕ Nueva Modalidad
    </button>
    <div class="modal fade" id="modalModalidad" tabindex="-1" aria-labelledby="modalModalidadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalModalidadLabel">Crear Nueva Modalidad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
            <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" required>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" id="descripcion" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" class="form-select" id="estado" required>
              <option selected disabled>Seleccionar...</option>
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
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
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Karate Do</td>
                <td>Disciplina tradicional japonesa</td>
                <td>Activo</td>
                <td>
                    <a href="{{ route('modalidades.show') }}" class="btn btn-sm btn-info">👁️ Ver</a>
                    <a href="{{ route('modalidades.edit') }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
