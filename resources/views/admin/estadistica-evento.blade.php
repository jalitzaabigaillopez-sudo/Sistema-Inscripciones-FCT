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
      <div class="card-header bg-primary text-white fw-bold">
        <i class="bi bi-clipboard-data me-2"></i> Estadísticas de: {{ $eventoSeleccionado->nombre }}
      </div>
      <div class="card-body">
        @php $total = $estadisticas['total_inscripciones'] ?? 0;
        $tarjetas_estadisticas = [
    [
        'label' => 'Inscripciones',
        'valor' => $estadisticas['total_inscripciones'],
        'icon' => 'bi-person-check',
        'color' => 'primary',
        'ruta' => route('inscripciones.index')
    ],
    [
        'label' => 'Atletas únicos',
        'valor' => $estadisticas['total_atletas'],
        'icon' => 'bi-person',
        'color' => 'success',
        'ruta' => route('atletas.index')
    ],
    [
        'label' => 'Academias',
        'valor' => $estadisticas['total_academias'],
        'icon' => 'bi-building',
        'color' => 'info',
        'ruta' => route('academias.index')
    ]
];

        @endphp

        @if($total > 0)
          {{-- Totales en tarjetas responsivas --}}
          <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
          @foreach ($tarjetas_estadisticas as $tarjeta)
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm rounded-3 h-100 text-white"
         style="background: linear-gradient(135deg,
           {{ $tarjeta['color'] === 'primary' ? '#0D6EFD,#3B82F6' :
              ($tarjeta['color'] === 'success' ? '#198754,#34D399' :
              ($tarjeta['color'] === 'info' ? '#0dcaf0,#60A5FA' :
              '#6c757d,#adb5bd') ) }});">
      <a href="{{ $tarjeta['ruta'] }}" class="card-body text-center text-white text-decoration-none" role="button">
        <i class="bi {{ $tarjeta['icon'] }} fs-1 mb-2"></i>
        <h6 class="fw-semibold">{{ $tarjeta['label'] }}</h6>
        <h3 class="fw-bold">{{ $tarjeta['valor'] }}</h3>
        <small>Actualizado hoy</small>
      </a>
    </div>
  </div>
@endforeach

          </div>
          {{-- Tablas de distribución --}}
        <div class="row g-4">
  @foreach([
    'por_sexo' => ['titulo' => 'Sexo', 'color' => 'primary'],
    'por_edad' => ['titulo' => 'Edad', 'color' => 'primary'],
    'por_nacimiento' => ['titulo' => 'Año de Nacimiento', 'color' => 'primary'],
    'por_academia' => ['titulo' => 'Academia', 'color' => 'primary'],
    'por_modalidad' => ['titulo' => 'Modalidad', 'color' => 'primary'],
    'por_submodalidad' => ['titulo' => 'Submodalidad', 'color' => 'primary'],
    'por_grado' => ['titulo' => 'Grado', 'color' => 'primary'],
    'cantidad_academias' => ['titulo' => 'Cantidad de Academias', 'color' => 'primary'],
    'por_categoria' => ['titulo' => 'Categoría', 'color' => 'primary'],
     ] as $key => $grupo)
    @php
      if ($key === 'cantidad_academias') {
        $items = ['Total' => $estadisticas['total_academias'] ?? 0];
      } else {
        $items = $estadisticas[$key] ?? [];
        // Convert collections/objects to arrays if necessary
        if (is_object($items) && method_exists($items, 'toArray')) {
          $items = $items->toArray();
        }
      }

      $total = 0;
      if (is_array($items)) {
        foreach ($items as $nombre => $valor) {
          if (is_array($valor) && isset($valor['cantidad'])) {
            $total += (int) $valor['cantidad'];
          } elseif (is_object($valor) && isset($valor->cantidad)) {
            $total += (int) $valor->cantidad;
          } elseif (is_numeric($valor)) {
            $total += (int) $valor;
          }
        }
      }
    @endphp

    <div class="col-12 col-md-6 col-lg-4">
      <div class="card border-start border-4 border-{{ $grupo['color'] }} shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-semibold text-{{ $grupo['color'] }} mb-3">{{ $grupo['titulo'] }}</h6>

          @if(count($items))
            <div class="table-responsive">
              <table class="table table-sm table-bordered align-middle mb-2">
                <thead class="table-light">
                  <tr>
                    <th>Nombre</th>
                    <th class="text-end">Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($items as $nombre => $cantidad)
                    <tr>
                      <td>{{ $nombre ?: 'Sin nombre' }}</td>
                      <td class="text-end">{{ $cantidad }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="text-end text-muted small">
              <strong>Total:</strong> {{ number_format($total) }}
            </div>
          @else
            <div class="text-muted fst-italic">Sin datos disponibles</div>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>
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
