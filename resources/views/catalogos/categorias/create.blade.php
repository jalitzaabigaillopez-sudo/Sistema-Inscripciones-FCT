@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Crear Nueva Categoría</h2>

    <form>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" placeholder="Ej. Juvenil">
        </div>
        <div class="mb-3">
            <label for="edad_min" class="form-label">Edad Mínima</label>
            <input type="number" class="form-control" id="edad_min">
        </div>
        <div class="mb-3">
            <label for="edad_max" class="form-label">Edad Máxima</label>
            <input type="number" class="form-control" id="edad_max">
        </div>
        <button type="submit" class="btn btn-success">💾 Guardar</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection
