@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Categoría</h2>

    <form>
        <div class="mb-3">
            <label for="division" class="form-label">División</label>
            <select class="form-select" id="division">
                <option value="Cadete">Cadete</option>
                <option value="Junior">Junior</option>
                <option value="Senior">Senior</option>
                <option value="Ejecutivo">Ejecutivo</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="sexo" class="form-label">Sexo</label>
            <select class="form-select" id="sexo">
                <option value="Femenino">Femenino</option>
                <option value="Masculino">Masculino</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="peso_min" class="form-label">Peso Mínimo</label>
            <input type="number" class="form-control" id="peso_min" value="60">
        </div>
        <div class="mb-3">
            <label for="peso_max" class="form-label">Peso Máximo</label>
            <input type="number" class="form-control" id="peso_max" value="70">
        </div>

        <button type="submit" class="btn btn-warning">✏️ Actualizar</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection
