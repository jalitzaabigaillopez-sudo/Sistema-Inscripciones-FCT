@extends('academia') 

@section('title', 'Dashboard Academia')

@section('content')
<div class="container mt-4">
  <div class="mb-4">
    <h2 class="fw-bold text-dark">
      ¡Bienvenida{{ isset($academia->nombre) ? ',' : '' }}
      @if(isset($academia->nombre))
        {{ $academia->nombre }}
      @else
        a tu Academia
      @endif
      !
    </h2>
    <p class="text-muted">Este es tu panel de control para gestionar la academia.</p>
  </div>

  {{-- Tarjetas resumen --}}
  <div class="row mb-4 g-3">
    <!-- Eventos inscritos -->
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card text-bg-success border-0 shadow-sm rounded-3 h-100">
        <div class="card-body text-center">
          <h6 class="card-title fw-bold mb-2 text-white">Eventos Inscritos</h6>
          <div class="d-flex justify-content-center align-items-center mb-2">
            <i class="bi bi-calendar-check fs-2 text-white me-2"></i>
            <span class="fs-1 fw-bold text-white">{{ $eventosInscritos ?? 0 }}</span>
          </div>
          <small class="text-light">Actualizado hoy</small>
        </div>
      </div>
    </div>

    <!-- Atletas registrados -->
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card text-bg-primary border-0 shadow-sm rounded-3 h-100">
        <div class="card-body text-center">
          <h6 class="card-title fw-bold mb-2 text-white">Atletas Registrados</h6>
          <div class="d-flex justify-content-center align-items-center mb-2">
            <i class="bi bi-person-fill fs-2 text-white me-2"></i>
            <span class="fs-1 fw-bold text-white">{{ $totalAtletas ?? 0 }}</span>
          </div>
          <small class="text-light">Actualizado hoy</small>
        </div>
      </div>
    </div>

    <!-- Avance de eventos -->
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card text-bg-info border-0 shadow-sm rounded-3 h-100">
        <div class="card-body text-center">
          <h6 class="card-title fw-bold mb-2 text-white">Avance de Eventos</h6>
          <p class="card-text fs-4 fw-semibold text-white mb-1">{{ $avanceEventos ?? 0 }}%</p>
          <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-primary" role="progressbar"
                 style="width: {{ $avanceEventos ?? 0 }}%;"
                 aria-valuenow="{{ $avanceEventos ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
          <small class="text-light">Progreso general</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Información de la academia --}}
  <div class="row mb-4 g-3">
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-header bg-success text-white fw-semibold">
          <i class="bi bi-info-circle-fill me-2"></i> Información de la Academia
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <i class="bi bi-building-check fs-1 text-success me-3"></i>
            <div>
              <h5 class="fw-bold mb-0">{{ $academia->nombre ?? 'Academia Desconocida' }}</h5>
              @if(!empty($academia->direccion))
              <small class="text-muted"><i class="bi bi-geo-alt-fill me-1"></i>{{ $academia->direccion }}</small>
              @else
              <small class="text-muted">Ubicación no registrada</small>
              @endif
            </div>
          </div>
            <ul class="list-unstyled mb-0">
            <li class="mb-2">
      <i class="bi bi-person-badge-fill me-2 text-primary"></i>
      <strong>Profesor Encargado:</strong> {{ $academia->profesor_encargado ?? 'No asignado' }}
    </li>
            <li class="mb-2">
              <i class="bi bi-envelope-fill me-2 text-primary"></i>
              <strong>Correo:</strong> {{ $academia->correo ?? 'N/A' }}
            </li>
            <li>
              <i class="bi bi-telephone-fill me-2 text-primary"></i>
              <strong>Teléfono:</strong> {{ $academia->telefono ?? 'N/A' }}
            </li>
            </ul>
        </div>
      </div>
    </div>

    {{-- Próximos eventos --}}
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-header bg-primary text-white fw-semibold">
          <i class="bi bi-calendar-event-fill me-2"></i> Próximos Eventos
        </div>
        <div class="card-body">
          @if(isset($proximosEventos) && count($proximosEventos) > 0)
            <table class="table table-sm align-middle">
              <thead class="table-light">
                <tr>
                  <th>Nombre</th>
                  <th>Fecha</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach($proximosEventos as $evento)
                  <tr>
                    <td>{{ $evento->nombre }}</td>
                    <td>{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</td>
                    <td>
                      <span class="badge bg-{{ $evento->estado == 'Activo' ? 'success' : 'secondary' }}">
                        {{ $evento->estado }}
                      </span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
            <p class="text-muted mb-0">No hay próximos eventos registrados.</p>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== GRÁFICOS ==================== --}}
  <div class="row g-4 mt-2">
    <!-- Inscripciones por categoría -->
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-header bg-primary text-white fw-semibold">
          <i class="bi bi-bar-chart-fill me-2"></i> Inscripciones por Categoría de Edad
        </div>
        <div class="card-body">
          <div class="chart-container" style="position: relative; height:50vh; width:100%;">
            <canvas id="graficoCategorias"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Distribución de grados -->
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-header bg-success text-white fw-semibold">
          <i class="bi bi-pie-chart-fill me-2"></i> Distribución por Grado / Cinta
        </div>
        <div class="card-body">
          <div class="chart-container" style="position: relative; height:50vh; width:100%;">
            <canvas id="graficoGrados"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Chart.js responsivo --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctxCategorias = document.getElementById('graficoCategorias');
  new Chart(ctxCategorias, {
    type: 'bar',
    data: {
      labels: {!! json_encode($categorias ?? ['Infantil','Cadete','Junior','Adulto','Master']) !!},
      datasets: [{
        label: 'Inscripciones',
        data: {!! json_encode($inscripciones ?? [10,15,8,12,5]) !!},
        backgroundColor: '#0d6efd'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Cantidad' } },
        x: { title: { display: true, text: 'Categorías' } }
      }
    }
  });

  const ctxGrados = document.getElementById('graficoGrados');
  new Chart(ctxGrados, {
    type: 'pie',
    data: {
      labels: {!! json_encode($grados ?? ['Blanca','Amarilla','Verde','Azul','Roja','Negra']) !!},
      datasets: [{
        data: {!! json_encode($gradosCount ?? [5,8,10,7,4,3]) !!},
        backgroundColor: ['#ffffff','#ffc107','#28a745','#0d6efd','#dc3545','#212529']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  {{-- DEBUG --}}
@if(isset($academia))
  <pre>{{ print_r($academia->toArray(), true) }}</pre>
@else
  <p>No hay academia definida</p>
@endif

{{-- ==================== SWEETALERT ACADEMIA ==================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

  // 🔹 Mostrar bienvenida solo una vez por academia
  @if(isset($id_academia))
    const academiaId = {{ $id_academia }};
    const academiaNombre = @json($nombre_academia ?? null);
    const key = `bienvenidaAcademia_${academiaId}`;

    if (!sessionStorage.getItem(key)) {
      Swal.fire({
        title: `¡Bienvenida${academiaNombre ? ', ' + academiaNombre : ''}!`,
        text: 'Has ingresado al panel de control de tu academia.',
        icon: 'info',
        confirmButtonColor: '#0d6efd',
        confirmButtonText: 'Continuar'
      }).then(() => {
        sessionStorage.setItem(key, 'true');
      });
    }
  @else
    console.warn('No se encontró id_academia, no se muestra bienvenida.');
  @endif

  // 🔹 Mensaje de éxito (por ejemplo, al registrar atleta)
  @if(session('success'))
    Swal.fire({
      title: '¡Operación Exitosa!',
      text: @json(session('success')),
      icon: 'success',
      confirmButtonColor: '#198754'
    });
  @endif

  // 🔹 Mensaje de error
  @if(session('error'))
    Swal.fire({
      title: 'Ups...',
      text: @json(session('error')),
      icon: 'error',
      confirmButtonColor: '#dc3545'
    });
  @endif

});
</script>






@endsection

