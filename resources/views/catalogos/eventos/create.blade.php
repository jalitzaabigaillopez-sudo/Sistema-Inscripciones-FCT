@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Crear Nuevo Torneo</h2>

    <form>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre">
        </div>
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="fecha">
        </div>
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" class="form-control" id="ubicacion">
        </div>
        <div class="mb-3">
            <label for="organizador" class="form-label">Organizador</label>
            <input type="text" class="form-control" id="organizador">
        </div>

        <button type="submit" class="btn btn-success">🟢 Guardar</button>
        <a href="#" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>
@endsection
