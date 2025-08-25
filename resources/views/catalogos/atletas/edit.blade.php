@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Atleta</h2>

    <form>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                <select class="form-select" id="tipo_identificacion">
                    <option value="nacional">Nacional</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="identificacion" class="form-label">Identificación</label>
                <input type="text" class="form-control" id="identificacion" value="123456789">
            </div>
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" value="Juan">
            </div>
            <div class="col-md-6">
                <label for="primer_apellido" class="form-label">Primer Apellido</label>
                <input type="text" class="form-control" id="primer_apellido" value="Pérez">
            </div>
            <div class="col-md-6">
                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" id="segundo_apellido" value="Rodríguez">
            </div>
            <div class="col-md-6">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" id="sexo">
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino" selected>Masculino</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" value="2005-06-15">
            </div>
            <div class="col-md-6">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol">
                    <option value="entrenador">Entrenador</option>
                    <option value="asistente">Asistente</option>
                    <option value="atleta" selected>Atleta</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado">
                    <option value="activo" selected>Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-warning">✏️ Actualizar</button>
            <a href="{{ route('atletas.index') }}" class="btn btn-secondary">↩️ Volver</a>
        </div>
    </form>
</div>
@endsection
