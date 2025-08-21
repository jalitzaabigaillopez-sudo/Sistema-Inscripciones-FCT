@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Evento</h2>

    <form>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Evento</label>
            <input type="text" class="form-control" id="nombre" value="Campeonato Nacional">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" rows="3">Evento anual de karate</textarea>
        </div>
        <div class="mb-3">
            <label for="fecha_inicio_inscripcion" class="form-label">Inicio de Inscripción</label>
            <input type="date" class="form-control" id="fecha_inicio_inscripcion" value="2025-08-01">
        </div>
        <div class="mb-3">
            <label for="fecha_final_inscripcion" class="form-label">Fin de Inscripción</label>
            <input type="date" class="form-control" id="fecha_final_inscripcion" value="2025-08-15">
        </div>
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" value="2025-09-01">
        </div>
        <div class="mb-3">
            <label for="fecha_final" class="form-label">Fecha Final</label>
            <input type="date" class="form-control" id="fecha_final" value="2025-09-03">
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado">
                <option value="activo" selected>Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">✏️ Actualizar</button>
        <a href="{{ route('torneos.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection

