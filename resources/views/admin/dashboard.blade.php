@extends('app')

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
    <div class="row g-3">
    <!-- Gráfico de Eventos por Mes (Estático con solo CSS) -->
    <div class="col-12 col-lg-6 mb-4 mb-lg-0">
      <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-bar-chart-fill me-2"></i> Eventos por Mes
      </div>
      <div class="card-body">
        <div class="ratio ratio-16x9 d-flex align-items-end" style="height:220px;">
        <div style="display:flex; align-items:flex-end; width:100%; gap:10px;">
          <div style="flex:1; background:#0d6efd; height:40%; border-radius:4px 4px 0 0;"></div>
          <div style="flex:1; background:#198754; height:80%; border-radius:4px 4px 0 0;"></div>
          <div style="flex:1; background:#ffc107; height:53%; border-radius:4px 4px 0 0;"></div>
          <div style="flex:1; background:#0dcaf0; height:93%; border-radius:4px 4px 0 0;"></div>
          <div style="flex:1; background:#6f42c1; height:66%; border-radius:4px 4px 0 0;"></div>
          <div style="flex:1; background:#dc3545; height:26%; border-radius:4px 4px 0 0;"></div>
          <div style="flex:1; background:#3543dc; height:33%; border-radius:4px 4px 0 0;"></div>
        </div>
        </div>
        <div class="d-flex justify-content-between mt-2 px-1" style="font-size:0.9rem;">
        <span>Ene</span><span>Feb</span><span>Mar</span><span>Abr</span><span>May</span><span>Jun</span><span>Jul</span>
        </div>
      </div>
      </div>
    </div>
    <!-- Gráfico de Distribución de Género (Estático con solo CSS) -->
    <div class="col-12 col-lg-6 mb-4 mb-lg-0">
      <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-info text-white fw-semibold">
        <i class="bi bi-pie-chart-fill me-2"></i> Distribución de Género
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-center align-items-center" style="height:220px;">
        <div style="width:140px; height:140px; border-radius:50%; background:conic-gradient(#0d6efd 0% 55%, #02ea25 55% 95%);"></div>
        </div>
        <div class="d-flex justify-content-center gap-3 mt-2" style="font-size:0.9rem;">
        <span><span style="display:inline-block;width:12px;height:12px;background:#0d6efd;border-radius:2px;margin-right:4px;"></span>Masculino</span>
        <span><span style="display:inline-block;width:12px;height:12px;background:#02ea25;border-radius:2px;margin-right:4px;"></span>Femenino</span>
        </div>
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
