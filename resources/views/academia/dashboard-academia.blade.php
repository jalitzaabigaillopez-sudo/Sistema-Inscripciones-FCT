@extends('layouts.academia')

@section('title', 'Dashboard Academia')

@section('content')
<div class="row mb-4">
  <div class="col-md-6">
    <div class="card text-white bg-primary shadow">
      <div class="card-body">
        <h5 class="card-title">Atletas Registrados</h5>
        <p class="card-text fs-4">25</p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card text-white bg-info shadow">
      <div class="card-body">
        <h5 class="card-title">Eventos Inscritos</h5>
        <p class="card-text fs-4">3</p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">Estadísticas de Participación</div>
      <div class="card-body">
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">Avance de Eventos</div>
      <div class="card-body">
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
      labels: ['Evento 1', 'Evento 2', 'Evento 3'],
      datasets: [{
        label: 'Atletas inscritos',
        data: [10, 8, 7],
        backgroundColor: '#0d6efd'
      }]
    }
  });

  new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
      labels: ['Completado', 'Pendiente'],
      datasets: [{
        data: [70, 30],
        backgroundColor: ['#0d6efd', '#6c757d']
      }]
    }
  });
</script>
@endpush
