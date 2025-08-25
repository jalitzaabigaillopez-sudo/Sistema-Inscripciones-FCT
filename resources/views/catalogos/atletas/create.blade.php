@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Atletas</h2>
    <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearAtletaModal">
        ➕ Crear Nuevo Atleta
    </button>
    <!-- Modal -->
    <div class="modal fade" id="crearAtletaModal" tabindex="-1" aria-labelledby="crearAtletaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('atletas.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearAtletaModalLabel">Crear Nuevo Atleta </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Atleta</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej. Juan Pérez">
                        </div>
                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" required placeholder="Ej. Siquirres">
                        </div>
                        <div class="mb-3">
                            <label for="profesor" class="form-label">Profesor encargado</label>
                            <input type="text" class="form-control" id="profesor" name="profesor" required placeholder="Profesor 1">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required placeholder="Ej. 8888-8888">
                        </div>
                        <div class="mb-4">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required placeholder="Ej. academia@email.com">
                        </div>
                        <div class="mb-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
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

    <a href="{{ route('atletas.index') }}" class="btn btn-secondary">↩️ Volver</a>
</div>
@endsection