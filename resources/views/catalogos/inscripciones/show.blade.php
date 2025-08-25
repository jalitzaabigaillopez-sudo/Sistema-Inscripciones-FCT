@extends('app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detalle de Inscripción</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Atleta: María Gómez</h5>
            <p class="card-text">Evento: Copa Caribe</p>
            <p class="card-text">Fecha: 2025-09-01</p>
            <p class="card-text">Estado: Confirmada</p>
        </div>
    </div>

    <a href="{{ route('inscripciones.edit') }}" class="btn btn-warning mt-3">✏️ Editar</a>
    <a href="{{ route('inscripciones.index') }}" class="btn btn-secondary mt-3">↩️ Volver</a>
</div>
@endsection
