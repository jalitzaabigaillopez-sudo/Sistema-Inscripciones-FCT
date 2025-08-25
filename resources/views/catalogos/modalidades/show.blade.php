@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detalle de Modalidad</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nombre: Karate Do</h5>
            <p class="card-text">Descripción: Disciplina tradicional japonesa</p>
            <p class="card-text">Estado: Activo</p>
        </div>
    </div>

    <a href="{{ route('modalidades.edit') }}" class="btn btn-warning mt-3">✏️ Editar</a>
    <a href="{{ route('modalidades.index') }}" class="btn btn-secondary mt-3">↩️ Volver</a>
</div>
@endsection

