@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="container">
    <h3 class="mb-4">Bienvenido al Dashboard Administrativo</h3>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Usuarios</h5>
                    <p class="card-text fs-2">120</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Academias</h5>
                    <p class="card-text fs-2">15</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Eventos este año</h5>
                    <p class="card-text fs-2">7</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Eventos por mes</div>
                <div class="card-body">
                    <canvas id="barChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Distribución de género</div>
                <div class="card-body">
                    <canvas id="pieChart" height="150"></canvas>
                </div>
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
