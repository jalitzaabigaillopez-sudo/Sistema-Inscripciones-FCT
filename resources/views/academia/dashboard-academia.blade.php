@extends('academia')

@section('title', 'Dashboard Academia')

@section('content')
<div class="container">
    {{-- Main dashboard mejorado --}}

    <h3 class="mb-4 fw-bold text-#222A59">Dashboard de Academia</h3>
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card shadow-sm border-0 text-bg-primary mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Atletas Registrados</h5>
            <p class="card-text fs-1 fw-semibold">35</p>
            <i class="bi bi-person-fill fs-3"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 text-bg-success mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Eventos Inscritos</h5>
            <p class="card-text fs-1 fw-semibold">5</p>
            <i class="bi bi-calendar-event-fill fs-3"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 text-bg-info mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Avance de Eventos</h5>
            <p class="card-text fs-1 fw-semibold">80%</p>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-primary text-white">Atletas inscritos por evento</div>
          <div class="card-body">
            <canvas id="barChart" height="150"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-info text-white">Estado de eventos</div>
          <div class="card-body">
            <canvas id="pieChart" height="150"></canvas>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Bar Chart: Atletas por evento
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Combate', 'Poomsae', 'Freestyle'],
            datasets: [{
                label: 'Atletas inscritos',
                data: [12, 8, 5],
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Distribución por evento',
                    font: { size: 16 }
                }
            }
        }
    });

    // Pie Chart: Estado de eventos
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Activos', 'Finalizados', 'Pendientes'],
            datasets: [{
                data: [3, 2, 1],
                backgroundColor: ['#0dcaf0', '#198754', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Estado actual de eventos',
                    font: { size: 16 }
                }
            }
        }
    });
});
</script>

@endpush