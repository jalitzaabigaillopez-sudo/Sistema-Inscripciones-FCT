@extends('layouts.admin')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="row mb-4">
  {{-- Bloques de estadísticas --}}
  <div class="col-md-4">
    <div class="card text-white bg-primary shadow">
      <div class="card-body">
        <h5 class="card-title">Academias</h5>
        <p class="card-text fs-4">120</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white bg-info shadow">
      <div class="card-body">
        <h5 class="card-title">Atletas</h5>
        <p class="card-text fs-4">450</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white bg-secondary shadow">
      <div class="card-body">
        <h5 class="card-title">Eventos</h5>
        <p class="card-text fs-4">35</p>
      </div>
    </div>
  </div>
</div>

{{-- Gráficos de ejemplo --}}
<div class="row">
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">Eventos por Mes</div>
      <div class="card-body">
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">Distribución de Atletas</div>
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
  const barCtx = document.getElementById('barChart');
  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
      datasets: [{
        label: 'Eventos',
        data: [5, 8, 4, 6, 7],
        backgroundColor: '#0d6efd'
      }]
    }
  });

  const pieCtx = document.getElementById('pieChart');
  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['Masculino', 'Femenino'],
      datasets: [{
        data: [60, 40],
        backgroundColor: ['#0d6efd', '#6c757d']
      }]
    }
  });
</script>
@endpush
