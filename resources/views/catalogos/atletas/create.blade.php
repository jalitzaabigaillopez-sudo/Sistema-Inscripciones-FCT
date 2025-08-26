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
                        <th>Tipo_Identificacion</th>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Primer apellido</th>
                    <th>Segundo apellido</th>
                    <th>Rol</th>
                    <th>Sexo</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Estado</th>
                        <h5 class="modal-title" id="crearAtletaModalLabel">Crear Nuevo Atleta </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                            <input type="text" class="form-control" id="tipo_identificacion" name="tipo_identificacion" required placeholder="Ej. Cédula">
                        </div>
                        <div class="mb-3">
                            <label for="identificacion" class="form-label">Identificacion</label>
                            <input type="text" class="form-control" id="identificacion" name="identificacion" required placeholder="Ej. 123456789">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Juan">
                        </div>
                        <div class="mb-3">
                            <label for="apellido1" class="form-label">Primer apellido</label>
                            <input type="text" class="form-control" id="apellido1" name="apellido1" required placeholder="Ej. Pérez">
                        </div>
                        <div class="mb-4">
                            <label for="apellido2" class="form-label">Segundo apellido</label>
                            <input type="text" class="form-control" id="apellido2" name="apellido2" required placeholder="Ej. Gómez">
                        </div>
                          <div class="mb-4">
                            <label for="rol" class="form-label">Rol</label>
                            <input type="text" class="form-control" id="rol" name="rol" required placeholder="atleta">
                        </div>
                          <div class="mb-4">
                            <label for="sexo" class="form-label">Sexo</label>
                            <input type="text" class="form-control" id="sexo" name="sexo" required placeholder="Masculino">
                        </div>
                          <div class="mb-4">
                            <label for="fecha" class="form-label">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required placeholder="1995-08-15">
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
                        <button type="submit" class="btn btn-success"> Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <a href="{{ route('atletas.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection