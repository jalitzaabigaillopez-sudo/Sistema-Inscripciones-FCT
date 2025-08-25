@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detalle de Categoría</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nombre: Sub-18</h5>
            <p class="card-text"><strong>Edad mínima:</strong> 15 años</p>
            <p class="card-text"><strong>Edad máxima:</strong> 18 años</p>
            <p class="card-text"><strong>Descripción:</strong> Categoría juvenil para competencias regionales.</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">↩️ Volver</a>
        <a href="{{ route('categorias.edit') }}" class="btn btn-warning">✏️ Editar</a>
    </div>
</div>
@endsection
