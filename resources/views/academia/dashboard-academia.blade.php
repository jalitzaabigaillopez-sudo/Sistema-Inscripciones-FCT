@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
  <div class="d-flex flex-column flex-md-row min-vh-100">
    
    {{-- Sidebar --}}
    <nav class="bg-white border-end p-3" style="min-width: 240px;">
      <div class="mb-4 text-center">
        <img src="{{ asset('images/fct-logo.png') }}" alt="FCT logo" class="img-fluid mb-2" style="max-height: 60px;">
        <h6 class="text-muted">Panel Academia</h6>
      </div>
      <ul class="nav flex-column">
        @foreach([
          ['label' => 'Inicio', 'icon' => 'house'],
          ['label' => 'Perfil', 'icon' => 'person'],
          ['label' => 'Atletas', 'icon' => 'person-running'],
          ['label' => 'Inscripción', 'icon' => 'clipboard-check'],
          ['label' => 'Eventos', 'icon' => 'calendar-event']
        ] as $item)
        <li class="nav-item mb-2">
          <a href="#" class="nav-link d-flex align-items-center text-dark">
            <i class="bi bi-{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
          </a>
        </li>
        @endforeach
      </ul>
    </nav>

    {{-- Main content --}}
    <main class="flex-grow-1 bg-light p-4 d-flex flex-column">
      {{-- Top bar --}}
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h4 class="mb-3 mb-md-0">Bienvenida, Academia</h4>
        <div class="d-flex align-items-center w-100 w-md-auto">
          <input type="text" class="form-control me-2" placeholder="Buscar..." style="max-width: 200px;">
          <div class="rounded-circle bg-secondary" style="width: 40px; height: 40px;"></div>
        </div>
      </div>

      {{-- Dashboard cards --}}
      <div class="row g-3 mb-4">
        @foreach([
          ['border' => 'border-start border-primary', 'icon' => 'bar-chart-line', 'text' => 'Estadísticas generales'],
          ['border' => 'border-start border-info', 'icon' => 'person-lines-fill', 'text' => 'Atletas registrados'],
          ['border' => 'border-start border-success', 'icon' => 'calendar-check', 'text' => 'Eventos activos']
        ] as $box)
        <div class="col-12 col-md-4">
          <div class="card bg-white {{ $box['border'] }} shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
              <i class="bi bi-{{ $box['icon'] }} fs-2 me-3 text-muted"></i>
              <div>
                <h6 class="mb-1">{{ $box['text'] }}</h6>
                <small class="text-muted">Ver detalles</small>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Gráficos o módulos extendidos --}}
      <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
          <div class="card bg-white shadow-sm h-100">
            <div class="card-header fw-bold text-muted">Rendimiento por evento</div>
            <div class="card-body text-muted">[Gráfico o tabla aquí]</div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="card bg-white shadow-sm h-100">
            <div class="card-header fw-bold text-muted">Inscripciones recientes</div>
            <div class="card-body text-muted">[Listado o resumen aquí]</div>
          </div>
        </div>
      </div>

      {{-- Footer --}}
      <footer class="mt-auto pt-4 pb-2">
        <div class="container text-center">
          <small class="text-muted">© 2023 FCT. Todos los derechos reservados.</small>
        </div>
      </footer>
