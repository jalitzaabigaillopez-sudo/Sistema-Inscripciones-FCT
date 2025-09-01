@extends('academia')

@section('title', 'Dashboard Academia')

@section('content')

<div class="container">
  <!-- {{ $academia }} -->
    {{-- Main dashboard --}}
   
      <h3 class="mb-4">Bienvenido al Dashboard de Academia</h3>
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card text-bg-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Atletas Registrados</h5>
              <p class="card-text fs-2">35</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-bg-success mb-3">
            <div class="card-body">
              <h5 class="card-title">Eventos Inscritos</h5>
              <p class="card-text fs-2">5</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-bg-info mb-3">
            <div class="card-body">
              <h5 class="card-title">Avance de Eventos</h5>
              <p class="card-text fs-2">80%</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">Atletas inscritos por evento</div>
            <div class="card-body">
              <canvas id="barChart" height="150"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">Estado de eventos</div>
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
  new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
      labels: ['Evento 1', 'Evento 2', 'Evento 3'],
      datasets: [{
        label: 'Atletas inscritos',
        data: [10, 8, 7],
        backgroundColor: '#0d6efd'
      }]
    }
  });

  new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
      labels: ['Completado', 'Pendiente'],
      datasets: [{
        data: [70, 30],
        backgroundColor: ['#0d6efd', '#6c757d']
      }]
    }
  });
</script>
@endpush