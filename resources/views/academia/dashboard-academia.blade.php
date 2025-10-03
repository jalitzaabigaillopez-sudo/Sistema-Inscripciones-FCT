@extends('academia')

@section('title', 'Dashboard Academia')

@section('content')

<div class="container">
    {{-- Main dashboard mejorado --}}

    <h3 class="mb-4 fw-bold text-">Dashboard de Academia</h3>
    <div class="row mb-4">
  <div class="col-md-4">
    <div class="card text-bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Eventos Inscritos</h5>
        <p class="card-text">5</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Atletas Registrados</h5>
        <p class="card-text">35</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-info mb-3">
      <div class="card-body">
        <h5 class="card-title">Avance de Eventos</h5>
        <p class="card-text">80%</p>
        <div class="progress">
          <div class="progress-bar bg-primary" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<canvas id="graficoTaekwondo" width="400" height="200"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('graficoTaekwondo').getContext('2d');
  const graficoTaekwondo = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Infantil (6-9)', 'Cadete (10-13)', 'Junior (14-17)', 'Adulto (18-30)', 'Master (+30)'],
      datasets: [{
        label: 'Inscripciones',
        data: [10, 15, 8, 12, 5],
        backgroundColor: '#17a2b8'
      }]
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: 'Inscripciones por Categoría de Edad - Taekwondo'
        },
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Cantidad de Inscripciones'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Categorías'
          }
        }
      }
    }
  });
</script>


      <!-- Tarjeta de Calendario de Eventos (pantalla completa) -->
<div class="col-12 mb-4">
  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-secondary text-white fw-semibold">
      <i class="bi bi-calendar3-week me-2"></i> Calendario de Eventos
    </div>
    <div class="card-body">
      <div id="calendar" style="width: 100%; height: 600px;"></div>
    </div>
  </div>
</div>

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<!-- SweetAlert2 (opcional para mostrar detalles) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  if (!calendarEl) return;

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: '/events',
    eventClick: function(info) {
      if (info.event.extendedProps.status === 'activo') {
        Swal.fire({
          icon: 'question',
          title: '¿Deseas inscribirte al evento?',
          showCancelButton: true,
          confirmButtonText: 'Inscribirme',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '/nuevaInscripcion'; // Redirigir a la página de inscripciones
          }
        });
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Evento no activo',
          text: 'No puedes inscribirte a este evento porque no está activo.'
        });
      }
    }
  });

  calendar.render();
});


</script>
    </div>
@endsection