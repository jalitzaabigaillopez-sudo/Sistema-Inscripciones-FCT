@extends('app')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Inscripción</h2>

    <form>
       <div class="mb-3">
            <label class="form-label">Atleta</label>
            <input type="text" name="atleta" class="form-control" value="María Gómez" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Evento</label>
            <input type="text" name="evento" class="form-control" value="Copa Caribe" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" value="2025-09-01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="activo" >Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">✏️ Actualizar</button>
        <a href="{{ route('inscripciones.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection


   
