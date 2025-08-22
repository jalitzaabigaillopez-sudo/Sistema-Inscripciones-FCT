@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">✏️ Editar Modalidad</h2>
    <form>
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="Karate Do" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3">Disciplina tradicional japonesa</textarea>
        </div>
        <div class="col-md-6">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado">
                    <option value="activo" selected>Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <br>
        <button type="submit" class="btn btn-primary">💾 Actualizar</button>
        <a href="{{ route('modalidades.index') }}" class="btn btn-secondary">↩️ Volver</a>
    </form>
</div>
@endsection
