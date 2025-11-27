@extends('academia') 

@section('title', 'Dashboard Academia')

@section('content')
<div class="container mt-4">
  <div class="mb-4">
  <h2 class="fw-bold text-dark">¡Bienvenida, {{ $nombre_academia }}!</h2>
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

  {{-- Información de la academia --}}
 <div class="row mb-4 g-3">
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-success text-white fw-semibold">
        <i class="bi bi-info-circle-fill me-2"></i> Información de la Academia
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          @if(!empty($academia->imagen))
            <img src="{{ asset('storage/' . $academia->imagen) }}"
                 alt="Logo de {{ $academia->nombre }}"
                 class="rounded-circle me-3"
                 style="width: 64px; height: 64px; object-fit: cover;">
          @else
            <i class="bi bi-building-check fs-1 text-success me-3"></i>
          @endif
          <div>
            <h5 class="fw-bold mb-0">{{ $academia->nombre ?? 'Academia Desconocida' }}</h5>
            @if(!empty(trim($academia->direccion)))
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

  {{-- ==================== EVENTOS Y SISTEMA ==================== --}}
  
  {{-- Próximos eventos --}}
 
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
                @php
  $inscripcion = $evento->inscripciones->firstWhere('id_usuario', auth()->id());
@endphp

@if($inscripcion && $inscripcion->estado === 'enviado')
  <button class="btn btn-sm btn-secondary" disabled title="Este evento ya fue enviado">
    <i class="bi bi-lock-fill me-1"></i> Enviado
  </button>
@elseif($inscripcion && $inscripcion->estado === 'pendiente')
  <a href="{{ route('editar.inscripcion', ['id_inscripcion' => $inscripcion->id_inscripcion]) }}"
     class="btn btn-sm btn-warning"
     title="Continuar inscripción">
    <i class="bi bi-pencil-square me-1"></i> Continuar
  </a>
@elseif($evento->fecha_inicio < now() )
  <button class="btn btn-sm btn-outline-danger" disabled title="Periodo cerrado">
    <i class="bi bi-calendar-x me-1"></i> Cerrado
  </button>
@else
  <a href="{{ route('editar.inscripcion', ['id_evento' => $evento->id_evento]) }}"
     class="btn btn-sm btn-primary"
     title="Nueva inscripción">
    <i class="bi bi-pencil-square me-1"></i> Inscribir
  </a>
@endif

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
           @if(empty($inscripciones) || array_sum($inscripciones) === 0)
    <p class="text-muted">No hay inscripciones registradas por categoría.</p>
  @else
    <div class="chart-container" style="position: relative; height:50vh; width:100%;">
      <canvas id="graficoCategorias"></canvas>
    </div>
  @endif
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
          @if(empty($gradosCount) || array_sum($gradosCount) === 0)
  <p class="text-muted">No hay atletas registrados por grado.</p>
@else
  <div class="chart-container" style="position: relative; height:50vh; width:100%;">
    <canvas id="graficoGrados"></canvas>
  </div>
@endif
          </div>
        </div>
      </div>
    </div>
  </div>

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

{{-- ==================== GRÁFICOS CON CHART.JS ==================== --}}

<div style="width:100%; height:300px;">
  <canvas id="graficoGrados"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctxGrados = document.getElementById('graficoGrados');
  new Chart(ctxGrados, {
    type: 'doughnut',
    data: {         
      labels: {!! json_encode($gradosLabels ?? []) !!},
      datasets: [{
        label: 'Atletas por Grado',
        data: {!! json_encode($gradosCount ?? []) !!},
        backgroundColor: {!! json_encode($coloresGrados ?? []) !!},
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

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-inscribir').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const url = this.dataset.url;
      const id = this.dataset.id;

      // Confirmación antes de redirigir
      Swal.fire({
        title: 'Inscribir atleta',
        text: `¿Deseas gestionar la inscripción para el evento #${id}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0d6efd'
      }).then(result => {
        if (result.isConfirmed) {
          // Redirigir a la URL (puedes cambiar por fetch/AJAX si quieres cargar modal)
          window.location.href = url;
        }
      });
    });
  });
});
</script>
@endsection