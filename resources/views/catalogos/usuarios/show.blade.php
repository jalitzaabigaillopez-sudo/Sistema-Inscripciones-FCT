@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detalle de Usuario</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nombre: Carlos Ramírez</h5>
            <p class="card-text"><strong>Correo:</strong> carlos@federacion.cr</p>
            <p class="card-text"><strong>Rol:</strong> Administrador</p>
            <p class="card-text"><strong>Estado:</strong> Activo</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">↩️ Volver</a>
        <a href="{{ route('usuarios.edit') }}" class="btn btn-warning">✏️ Editar</a>
    </div>
</div>
@endsection
