@extends('app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="container">
  <h3 class="mb-4 fw-bold text-#222A59">Dashboard Administrativo</h3>
  <div class="row mb-4 g-3">
    <!-- Total de usuarios del sistema -->
 
<div class="col-12 col-sm-6 col-lg-3">
  <div class="card text-bg-primary border-0 shadow-sm rounded-3 h-100">
    <div class="card-body text-center">
      <h6 class="card-title fw-bold mb-2 text-white">Usuarios en el Sistema</h6>
      <div class="d-flex justify-content-center align-items-center mb-2">
        <i class="bi bi-person-badge fs-2 text-white me-2"></i>
        <span class="fs-1 fw-bold text-white">{{ $usersCount ?? '7' }}</span>
      </div>
      <small class="text-light">Actualizado hoy</small>
    </div>
  </div>
</div>
<!-- Academias registradas -->
<div class="col-12 col-sm-6 col-lg-3">
  <div class="card text-bg-success border-0 shadow-sm rounded-3 h-100">
    <div class="card-body text-center">
      <h6 class="card-title fw-bold mb-2 text-white">Academias Registradas</h6>
      <div class="d-flex justify-content-center align-items-center mb-2">
        <i class="bi bi-building fs-2 text-white me-2"></i>
        <span class="fs-1 fw-bold text-white">{{ $academiesCount ?? '7' }}</span>
      </div>
      <small class="text-light">Actualizado hoy</small>
    </div>
  </div>
</div>
    <!-- Eventos activos -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card text-bg-warning border-0 shadow-sm rounded-3 h-100">
        <div class="card-body text-center">
          <h6 class="card-title fw-semibold mb-2">Eventos Activos</h6>
          <p class="card-text fs-2 fw-bold mb-1">7</p>
          <i class="bi bi-calendar-event-fill fs-3"></i>
        </div>
      </div>
    </div>
    <!-- Inscripciones totales -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card text-bg-info border-0 shadow-sm rounded-3 h-100">
        <div class="card-body text-center">
          <h6 class="card-title fw-semibold mb-2">Inscripciones Totales</h6>
          <p class="card-text fs-2 fw-bold mb-1">245</p>
          <i class="bi bi-journal-text fs-3"></i>
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

