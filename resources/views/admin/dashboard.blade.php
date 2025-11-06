@extends('app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="container py-4" style="background-color: #f5f7fa; min-height: 100vh;">
  <h3 class="mb-2 fw-bold text-primary text-center">Bienvenido al Dashboard Administrativo</h3>
  <p class="text-secondary text-center mb-5">Monitorea usuarios, academias, eventos e inscripciones del sistema centralizado.</p>
   {{-- ==================== TARJETAS ==================== --}}
  <div class="row mb-4 g-4">
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 shadow-sm rounded-3 h-100 text-white" style="background:linear-gradient(135deg,#1E3A8A,#3B82F6);">
        <a href="{{ route('usuarios.index') }}" class="card-body text-center text-white text-decoration-none" role="button">
          <i class="bi bi-person-badge fs-1 mb-2"></i>
          <h6 class="fw-semibold">Usuarios</h6>
          <h3 class="fw-bold">{{ $usersCount ?? 0 }}</h3>
          <small>Actualizado hoy</small>
        </a>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 shadow-sm rounded-3 h-100 text-white" style="background:linear-gradient(135deg,#047857,#10B981);">
        <div class="card-body text-center">
          <i class="bi bi-building fs-1 mb-2"></i>
          <h6 class="fw-semibold">Academias</h6>
          <h3 class="fw-bold">{{ $academiesCount ?? 0 }}</h3>
          <small>Actualizado hoy</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 shadow-sm rounded-3 h-100 text-white" style="background:linear-gradient(135deg,#FACC15,#CA8A04);">
        <div class="card-body text-center">
          <i class="bi bi-calendar-event fs-1 mb-2"></i>
          <h6 class="fw-semibold">Eventos Activos</h6>
          <h3 class="fw-bold">{{ $eventosCount ?? 0 }}</h3>
          <small>Monitoreo</small>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 shadow-sm rounded-3 h-100 text-white" style="background:linear-gradient(135deg,#0284C7,#38BDF8);">
      <div class="card-body text-center">
      <i class="bi bi-journal-text fs-1 mb-2"></i>
      <h6 class="fw-semibold">Inscripciones</h6>
      <h3 class="fw-bold">{{ $inscripcionesCount ?? 0 }}</h3>
      <small>Datos acumulados</small>
      </div>
      </div>
    </div>
  </div>

  {{-- ==================== GRÁFICOS ==================== --}}
<div class="row g-4 mt-2">
  <!-- Eventos por mes -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-bar-chart-fill me-2"></i> Eventos por Mes
      </div>
      <div class="card-body">
        <div style="position: relative; height:50vh; width:100%;">
          <canvas id="graficoEventosMes"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Distribución por género -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-success text-white fw-semibold">
        <i class="bi bi-pie-chart-fill me-2"></i> Distribución por Género
      </div>
      <div class="card-body">
        <div style="position: relative; height:50vh; width:100%;">
          <canvas id="graficoGenero"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

  {{-- ==================== EVENTOS Y SISTEMA ==================== --}}
  <br>
  <div class="row g-4 mb-4">
    <!-- Próximos eventos -->
 <div class="col-12 col-lg-6">
  <div class="card border-0 shadow-sm rounded-3 h-100">
    <div class="card-header bg-primary text-white fw-semibold">
      <i class="bi bi-calendar-event-fill me-2"></i> Próximos Eventos
    </div>
    <div class="card-body">
      @if(isset($proximosEventos) && count($proximosEventos) > 0)
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>Imagen</th>
              <th>Nombre</th>
              <th>Fecha</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            @foreach($proximosEventos as $evento)
              <tr>
                <td>
                  @if(!empty($evento->imagen))
                    <img src="{{ asset('storage/' . $evento->imagen) }}"
                         alt="Miniatura de {{ $evento->nombre }}"
                         class="rounded"
                         style="width: 48px; height: 48px; object-fit: cover;">
                  @else
                    <span class="text-muted">Sin imagen</span>
                  @endif
                </td>
                <td>{{ $evento->nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}</td>
                <td>
                  <span class="badge bg-{{ $evento->estado == 'Activo' ? 'success' : 'secondary' }}">
                    {{ $evento->estado }}
                  </span>
                </td>
                <td>
                  <a href="{{ route('inscripciones.index') }}" class="btn btn-sm btn-primary" title="Inscribir a este evento">
                    <i class="bi bi-pencil-square me-1"></i> Inscribir
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
        <p class="text-muted mb-0">No hay próximos eventos registrados.</p>
      @endif
    </div>
  </div>
</div>

    <!-- Info del sistema -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-header text-white fw-semibold" style="background:linear-gradient(135deg,#047857,#10B981);">
          <i class="bi bi-info-circle-fill me-2"></i> Información del Sistema
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between"><span>Usuarios</span><span>{{ $usersCount ?? 0 }}</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Academias</span><span>{{ $academiesCount ?? 0 }}</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Atletas</span><span>{{ $atletasCount ?? 0 }}</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Inscripciones</span><span>{{ $inscripcionesCount ?? 0 }}</span></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
{{-- ==================== Chart.js ==================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxEventos = document.getElementById('graficoEventosMes');
new Chart(ctxEventos, {
  type: 'bar',
  data: {
    labels: {!! json_encode($meses) !!},
    datasets: [{
      label: 'Eventos',
      data: {!! json_encode($eventosPorMes) !!},
      backgroundColor: '#0d6efd'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: { beginAtZero: true, title: { display: true, text: 'Cantidad de eventos' } },
      x: { title: { display: true, text: 'Meses' } }
    }
  }
});

const ctxGenero = document.getElementById('graficoGenero');
new Chart(ctxGenero, {
  type: 'doughnut',
  data: {
    labels: ['Masculino','Femenino'],
    datasets: [{
      data: {!! json_encode($generoDistribucion) !!},
      backgroundColor: ['#0d6efd','#10B981'],
      borderColor: '#f8f9fa',
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } }
  }
});
</script>

{{-- ==================== SWEETALERT ==================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Mostrar solo una vez por sesión
  if (!sessionStorage.getItem('bienvenidaAdminMostrada')) {
    Swal.fire({
      title: '¡Bienvenido Administrador!',
      text: 'Has ingresado al panel de control general del sistema.',
      icon: 'success',
      confirmButtonColor: '#0d6efd',
      confirmButtonText: 'Continuar'
    }).then(() => {
      sessionStorage.setItem('bienvenidaAdminMostrada', 'true');
    });
  }

  // Mensaje de éxito
  @if(session('success'))
    Swal.fire({
      title: 'Éxito',
      text: @json(session('success')),
      icon: 'success',
      confirmButtonColor: '#0d6efd'
    });
  @endif

  // Mensaje de error
  @if(session('error'))
    Swal.fire({
      title: 'Error',
      text: @json(session('error')),
      icon: 'error',
      confirmButtonColor: '#dc3545'
    });
  @endif
});
</script>

@endsection

