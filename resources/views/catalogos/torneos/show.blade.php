@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detalle de Torneo</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nombre: Copa Nacional 2025</h5>
            <p class="card-text"><strong>Fecha:</strong> 15 de septiembre de 2025</p>
            <p class="card-text"><strong>Ubicación:</strong> Gimnasio Nacional</p>
            <p class="card-text"><strong>Organizador:</strong> Federación Costarricense</p>
            <p class="card-text"><strong>Participantes:</strong> 120 atletas</p>
        </div>
    </div>

    <div class="mt-3">
          <a href="{{ route('torneos.edit') }}" class="btn btn-warning">✏️ Editar</a>
        <a href="{{ route('torneos.index') }}" class="btn btn-secondary">↩️ Volver</a>
    
    </div>
</div>
@endsection
