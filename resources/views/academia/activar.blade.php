@extends('admin')

@section('title', 'Activar Academia')

@section('content')
<a href="{{ route('admin.academias.index') }}" class="btn btn-outline-primary float-end mb-3">
    <i class="bi bi-arrow-left-circle"></i> Volver a la lista
</a>

<h3 class="mb-4 text-black fw-bold">Activar / Desactivar Academia</h3>

<div class="card shadow-sm">
    <div class="card-body">
        <!-- Información de la academia -->
        <div class="mb-4">
            <h5 class="fw-bold">Datos de la Academia</h5>
            <p><strong>Nombre:</strong> Academia XYZ</p>
            <p><strong>Encargado:</strong> Juan Pérez</p>
            <p><strong>Teléfono:</strong> 8888-1234</p>
            <p><strong>Correo:</strong> academia@correo.com</p>
            <p><strong>Dirección:</strong> San José, Costa Rica</p>
            <p><strong>Estado actual:</strong> 
                <span class="badge bg-danger">Inactivo</span>
            </p>
        </div>

        <!-- Formulario para cambiar estado -->
        <form method="POST" action="#">
            @csrf
            <div class="mb-3">
                <label class="form-label">Cambiar estado</label>
                <select class="form-select" name="estado">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <div class="text-end">
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
