@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Crear Nueva Categoría de Peso</h2>

    <form>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" placeholder="Ej. Peso Ligero">
        </div>
        <div class="mb-3">
            <label for="rango" class="form-label">Rango (kg)</label>
            <input type="text" class="form-control" id="rango" placeholder="Ej. 55 - 65">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">🟢 Guardar</button>
        <a href="#" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>
@endsection
