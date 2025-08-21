@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detalle de Academia</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nombre: Academia Central</h5>
            <p class="card-text">Ubicación: Siquirres</p>
            <p class="card-text">Profesor encargado: Profesor 1</p>
            <p class="card-text">Teléfono: 8888-8888</p>
            <p class="card-text">Correo: academia@email.com</p>
            <p class="card-text">Estado: Activo</p>
            <p class="card-text">ID: 1</p>
        </div>
    </div>

    <a href="{{ route('academias.edit') }}" class="btn btn-warning mt-3">✏️ Editar</a>
    <a href="{{ route('academias.index') }}" class="btn btn-secondary mt-3">↩️ Volver</a>
</div>
@endsection
