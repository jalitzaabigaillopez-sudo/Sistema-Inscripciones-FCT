@extends('app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="container">
    <h3 class="mb-4">Dashboard Administrativo</h3>
    <div class="row mb-4">
        <div class="row mb-4">
  <!-- Total de usuarios del sistema -->
  <div class="col-md-3">
    <div class="card text-bg-primary border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body text-center">
        <h6 class="card-title fw-semibold mb-2">Usuarios del Sistema</h6>
        <p class="card-text fs-2 fw-bold mb-1">120</p>
        <i class="bi bi-people-fill fs-3"></i>
      </div>
    </div>
  </div>

  <!-- Academias registradas -->
  <div class="col-md-3">
    <div class="card text-bg-success border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body text-center">
        <h6 class="card-title fw-semibold mb-2">Academias Registradas</h6>
        <p class="card-text fs-2 fw-bold mb-1">15</p>
        <i class="bi bi-building fs-3"></i>
      </div>
    </div>
  </div>

  <!-- Eventos activos -->
  <div class="col-md-3">
    <div class="card text-bg-warning border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body text-center">
        <h6 class="card-title fw-semibold mb-2">Eventos Activos</h6>
        <p class="card-text fs-2 fw-bold mb-1">7</p>
        <i class="bi bi-calendar-event-fill fs-3"></i>
      </div>
    </div>
  </div>

  <!-- Inscripciones totales -->
  <div class="col-md-3">
    <div class="card text-bg-info border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body text-center">
        <h6 class="card-title fw-semibold mb-2">Inscripciones Totales</h6>
        <p class="card-text fs-2 fw-bold mb-1">245</p>
        <i class="bi bi-journal-text fs-3"></i>
      </div>
    </div>
  </div>
</div>

    </div>

    <div class="row">
      <!-- Gráfico de Eventos por Mes -->
      <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-header bg-primary text-white fw-semibold">
            <i class="bi bi-bar-chart-fill me-2"></i> Eventos por Mes
          </div>
          <div class="card-body">
            <canvas id="barChart" height="200"></canvas>
          </div>
        </div>
      </div>
      <!-- Gráfico de Distribución de Género -->
      <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-header bg-info text-white fw-semibold">
            <i class="bi bi-pie-chart-fill me-2"></i> Distribución de Género
          </div>
          <div class="card-body">
            <canvas id="pieChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Bar Chart
  const barCtx = document.getElementById('barChart').getContext('2d');
  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
      datasets: [{
        label: 'Eventos',
        data: [5, 8, 4, 6, 7],
        backgroundColor: '#0d6efd'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        title: {
          display: true,
          text: 'Eventos por mes',
          font: { size: 16 }
        }
      }
    }
  });

  // Pie Chart
  const pieCtx = document.getElementById('pieChart').getContext('2d');
  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['Masculino', 'Femenino'],
      datasets: [{
        data: [60, 40],
        backgroundColor: ['#0d6efd', '#6c757d']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Distribución de género',
          font: { size: 16 }
        }
      }
    }
  });
});
</script>
@endpush
