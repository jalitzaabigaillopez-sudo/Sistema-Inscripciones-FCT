@extends('app')

@section('title', 'Dashboard Academia')

@section('content')
<div class="container-fluid px-0">
  <div class="d-flex flex-column flex-md-row min-vh-100">
    
   

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
