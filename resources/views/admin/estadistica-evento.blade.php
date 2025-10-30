@extends('app')

@section('title', 'Estadísticas por Evento')

@section('content')
<div class="container py-4">
  <h2 class="text-center text-primary fw-bold mb-4">
    <i class="bi bi-bar-chart-line-fill me-2"></i> Estadísticas por Evento
  </h2>

  {{-- Buscador --}}
  <form method="GET" action="{{ route('estadisticas.eventos') }}" class="card shadow-sm p-3 mb-4 border-start border-4 border-primary">
    <div class="row g-3">
      <div class="col-12 col-md-9">
        <label for="id_evento" class="form-label fw-semibold">Selecciona un evento</label>
        <select name="id_evento" id="id_evento" class="form-select" required>
          <option value="">-- Seleccionar --</option>
          @foreach($eventos as $evento)
            <option value="{{ $evento->id_evento }}" {{ request('id_evento') == $evento->id_evento ? 'selected' : '' }}>
              {{ $evento->nombre }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-12 col-md-3 d-grid">
        <button type="submit" class="btn btn-outline-primary">
          <i class="bi bi-search me-1"></i> Consultar
        </button>
      </div>
    </div>
  </form>

  {{-- Resultados --}}
  @if($eventoSeleccionado)
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-warning text-dark fw-bold">
        <i class="bi bi-clipboard-data me-2"></i> Estadísticas de: {{ $eventoSeleccionado->nombre }}
      </div>
      <div class="card-body">
        @php $total = $estadisticas['total_inscripciones'] ?? 0; @endphp

        @if($total > 0)
          {{-- Totales en tarjetas responsivas --}}
          <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            @foreach([
              ['label' => 'Inscripciones', 'valor' => $estadisticas['total_inscripciones'], 'icon' => 'bi-person-check', 'color' => 'primary'],
              ['label' => 'Atletas únicos', 'valor' => $estadisticas['total_atletas'], 'icon' => 'bi-person', 'color' => 'success'],
              ['label' => 'Academias', 'valor' => $estadisticas['total_academias'], 'icon' => 'bi-building', 'color' => 'info'],
            ] as $item)
              <div class="col">
                <div class="card h-100 border-start border-4 border-{{ $item['color'] }} shadow-sm">
                  <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="fw-semibold text-muted mb-1">{{ $item['label'] }}</h6>
                      <h4 class="fw-bold text-{{ $item['color'] }}">{{ $item['valor'] }}</h4>
                    </div>
                    <i class="bi {{ $item['icon'] }} fs-2 text-{{ $item['color'] }}"></i>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Distribuciones colapsables --}}
          @foreach([
            'por_modalidad' => 'Distribución por Modalidad',
            'por_submodalidad' => 'Distribución por Submodalidad',
            'por_grado' => 'Distribución por Grado',
            'por_categoria' => 'Distribución por Categoría'
          ] as $key => $titulo)
            @php $items = $estadisticas[$key] ?? []; @endphp
            <div class="accordion mb-3" id="accordion-{{ $key }}">
              <div class="accordion-item">
                <h2 class="accordion-header" id="heading-{{ $key }}">
                  <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}">
                    {{ $titulo }}
                  </button>
                </h2>
                <div id="collapse-{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#accordion-{{ $key }}">
                  <div class="accordion-body">
                    @if(count($items))
                      <ul class="list-group list-group-sm">
                        @foreach($items as $nombre => $cantidad)
                          <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $nombre }}</span>
                            <span class="badge bg-secondary">{{ $cantidad }}</span>
                          </li>
                        @endforeach
                      </ul>
                    @else
                      <p class="text-muted">Sin datos registrados.</p>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="alert alert-info">No hay inscripciones para este evento.</div>
        @endif
      </div>
    </div>
  @elseif(request('id_evento'))
    <div class="alert alert-danger">No se encontraron estadísticas para el evento seleccionado.</div>
  @endif
</div>
@endsection
