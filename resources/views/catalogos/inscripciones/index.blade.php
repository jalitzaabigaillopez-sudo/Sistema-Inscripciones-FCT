@extends('app')

@section('content')
<a href="{{ route('adminDash') }}" class="btn btn-outline-primary float-end">
    Volver al Dashboard
</a>
<div class="container mt-4">
    <h2 class="mb-4">Listado de Inscripciones</h2>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalInscripcion">
        ➕ Nueva Inscripción
    </button>

    <div class="modal fade" id="modalInscripcion" tabindex="-1" aria-labelledby="modalInscripcionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInscripcion">Crear Nueva Inscripcion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="atleta" class="form-label">Atleta</label>
                            <input type="text" class="form-control" id="atleta" placeholder="Nombre del Atleta">
                        </div>
                        <div class="mb-3">
                            <label for="evento" class="form-label">Evento</label>
                            <input type="text" class="form-control" id="evento" placeholder="Nombre del Evento">
                        </div>
                         <div class="mb-3">
                            <label for="fecha_inscripcion" class="form-label">Fecha Inscripción</label>
                            <input type="date" class="form-control" id="fecha_inscripcion">
                        </div>
                       <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado">
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
                <th>Atleta</th>
                <th>Evento</th>
                <th>Fecha Inscripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Maria Gomez</td>
                <td>Copa Caribe</td>
                <td>2023-05-15</td>
                <td>Activo</td>
                <td>
                    <a href="{{ route('inscripciones.show') }}" class="btn btn-sm btn-info">👁️ Ver</a>
                    <a href="{{ route('inscripciones.edit') }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
