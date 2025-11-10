@extends('app')

@section('title', 'Estadísticas por Evento')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center mb-4">
    <h4 class="fw-bold mb-0">Estadísticas por Evento</h4>
    <form method="GET" action="{{ route('estadisticas.eventos') }}" class="ms-auto d-flex gap-2">
      <select name="id_evento" class="form-select form-select-sm" required>
        <option value="">-- Seleccionar evento --</option>
        @foreach($eventos as $evento)
          <option value="{{ $evento->id_evento }}" {{ request('id_evento') == $evento->id_evento ? 'selected' : '' }}>
            {{ $evento->nombre }}
          </option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-search me-1"></i> Consultar
      </button>
    </form>
  </div>
  <hr>

  @if($eventoSeleccionado)
    <div class="mb-4">
      <h5 class="fw-semibold text-primary mb-3">
        <i class="bi bi-clipboard-data me-2"></i> Estadísticas de: {{ $eventoSeleccionado->nombre }}
      </h5>

      {{-- Tarjetas resumen --}}
      <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
        @php
          $tarjetas = [
            ['label' => 'Inscripciones', 'valor' => $estadisticas['total_inscripciones'], 'icon' => 'bi-person-check', 'color' => 'primary'],
            ['label' => 'Atletas únicos', 'valor' => $estadisticas['total_atletas'], 'icon' => 'bi-person', 'color' => 'success'],
            ['label' => 'Academias', 'valor' => $estadisticas['total_academias'], 'icon' => 'bi-building', 'color' => 'info'],
          ];
        @endphp

        @foreach($tarjetas as $t)
          <div class="col">
            <div class="card border-0 shadow-sm rounded-3 h-100 text-white"
                 style="background: linear-gradient(135deg,
                   {{ $t['color'] === 'primary' ? '#0D6EFD,#3B82F6' :
                      ($t['color'] === 'success' ? '#198754,#34D399' :
                      ($t['color'] === 'info' ? '#0dcaf0,#60A5FA' :
                      '#6c757d,#adb5bd') ) }});">
              <div class="card-body text-center text-white">
                <i class="bi {{ $t['icon'] }} fs-1 mb-2"></i>
                <h6 class="fw-semibold">{{ $t['label'] }}</h6>
                <h3 class="fw-bold">{{ number_format($t['valor']) }}</h3>
                <small>Actualizado hoy</small>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- Cuadros estadísticos --}}
    @php
      $grupos = [
        'por_sexo' => 'Sexo',
        'por_edad' => 'Rango de Edad',
        'por_nacimiento_top' => 'Año de Nacimiento',
        'por_academia' => 'Academia',
        'por_modalidad' => 'Modalidad',
        'por_submodalidad' => 'Submodalidad',
        'por_grado' => 'Grado',
      ];
    @endphp

       <div class="row g-4">
      @foreach($grupos as $key => $titulo)
        @php
          $items = $estadisticas[$key] ?? [];
        @endphp

        <div class="col-12 col-md-6 col-lg-4">
          <div class="card shadow-sm border-start border-4 border-primary h-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h6 class="fw-semibold text-primary mb-3">{{ $titulo }}</h6>

                @if(!empty($items))
                  <div class="table-responsive mb-2">
                    <table class="table table-sm table-hover table-bordered align-middle">
                      <thead class="table-light">
                        <tr>
                          <th class="text-secondary">Nombre</th>
                          <th class="text-end text-secondary">Cantidad</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($items as $nombre => $cantidad)
                          <tr>
                            <td class="text-wrap">{{ $nombre }}</td>
                            <td class="text-end">{{ number_format($cantidad) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @else
                  <div class="text-muted fst-italic">Sin datos disponibles</div>
                @endif
              </div>

              <div class="text-end text-muted small mt-auto pt-2 border-top">
                <strong>Total:</strong> {{ number_format(array_sum($items)) }}
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @elseif(request('id_evento'))
    <div class="alert alert-danger mt-4">No se encontraron estadísticas para el evento seleccionado.</div>
  @endif
</div>
@endsection
