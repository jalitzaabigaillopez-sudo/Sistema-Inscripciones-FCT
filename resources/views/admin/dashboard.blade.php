@extends('app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
        <div class="d-flex align-items-center gap-3">
            {{-- <img src="{{ asset('images/fct-logo.svg') }}" alt="FCT Logo" height="40"> --}}
            <h4 class="mb-0">Panel Administrativo</h4>
        </div>
        <div class="d-flex align-items-center gap-2">
            <input type="text" class="form-control" placeholder="Buscar..." aria-label="Buscador">
            <button class="btn btn-outline-secondary">🔍</button>
        </div>
    </div>

    {{-- Sidebar + Main --}}
    <div class="row mt-4">
        {{-- Sidebar --}}
        <div class="col-md-3 col-lg-2">
            <nav class="nav flex-column bg-light rounded p-3 shadow-sm">
                <a class="nav-link active" href="#">Menú</a>
                <a class="nav-link" href="">Perfil</a>
                <a class="nav-link" href="#">Inscripción</a>
                <a class="nav-link" href="#">Eventos</a>
                <a class="nav-link" href="#">Estadísticas</a>
                <a class="nav-link" href="#">Verificación de Peso</a>
                <a class="nav-link" href="#">Seguridad</a>
                <a class="nav-link" href="#">Generación de llaves</a>
                <a class="nav-link" href="#">Ranking nacional</a>
                <a class="nav-link" href="#">Catálogos generales</a>
                <hr>
                {{-- <span class="text-muted">Log in as: <strong>Administrador</strong></span> --}}
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="col-md-9 col-lg-10">
            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-warning shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Academias a nivel nacional</h5>
                            <p class="card-text fs-4">100</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Atletas a nivel nacional</h5>
                            <p class="card-text fs-4">300</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-secondary shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Torneos/Eventos</h5>
                            <p class="card-text fs-4">100</p>
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
