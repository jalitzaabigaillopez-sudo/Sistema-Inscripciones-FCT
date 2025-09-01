@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detalle de Atleta</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nombre: María González</h5>
            <p class="card-text"><strong>Edad:</strong> 22 años</p>
            <p class="card-text"><strong>Academia:</strong> Academia Central</p>
            <p class="card-text"><strong>Categoría:</strong> Peso Medio</p>
            <p class="card-text"><strong>Ranking:</strong> #3 Nacional</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('atletas.index') }}" class="btn btn-secondary">↩️ Volver</a>
        <a href="{{ route('atletas.edit')}}" class="btn btn-warning">✏️ Editar</a>
    </div>
</div>
@endsection
