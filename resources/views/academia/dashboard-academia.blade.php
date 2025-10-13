@extends('academia')

@section('title', 'Dashboard Academia')

@section('content')

<div class="container">
    {{-- Main dashboard mejorado --}}

    <h3 class="mb-4 fw-bold text-">Dashboard de Academia</h3>
    <div class="row mb-4">
  <div class="col-md-4">
    <div class="card text-bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Eventos Inscritos</h5>
        <p class="card-text">5</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Atletas Registrados</h5>
        <p class="card-text">35</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-info mb-3">
      <div class="card-body">
        <h5 class="card-title">Avance de Eventos</h5>
        <p class="card-text">80%</p>
        <div class="progress">
          <div class="progress-bar bg-primary" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>
</div>

    </div>
@endsection