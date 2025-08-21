@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detalle de Peso</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Categoría: Peso Ligero</h5>
            <p class="card-text"><strong>Rango:</strong> 55kg - 65kg</p>
            <p class="card-text"><strong>Descripción:</strong> Categoría para atletas de peso medio bajo.</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('pesos.index') }}" class="btn btn-secondary">↩️ Volver</a>
        <a href="{{ route('pesos.edit') }}" class="btn btn-warning">✏️ Editar</a>
    </div>
</div>
@endsection
