@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Crear Nueva Inscripción</h2>

    <form>
        <div class="mb-3">
            <label for="atleta" class="form-label">Atleta</label>
            <input type="text" class="form-control" id="atleta" placeholder="María Gómez">
        </div>
        <div class="mb-3">
            <label for="evento" class="form-label">Evento</label>
            <input type="text" class="form-control" id="evento" placeholder="Copa Caribe">
        </div>
        <div class="mb-3">
            <label for="fecha_inscripcion" class="form-label">Fecha Inscripción</label>
            <input type="date" class="form-control" id="fecha_inscripcion">
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado">
                <option value="confirmada">Confirmada</option>
                <option value="pendiente">Pendiente</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">💾 Guardar</button>
        <a href="{{ route('inscripciones.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection
 