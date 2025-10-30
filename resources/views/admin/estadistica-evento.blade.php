
@extends('app')

@section('title', 'Estadísticas por Evento')

@section('content')
<div class="container py-4">
  <h3 class="mb-4 fw-bold text-primary text-center">Estadísticas por Evento</h3>

  {{-- Buscador --}}
  <form method="GET" action="{{ route('estadisticas.eventos') }}" class="mb-4">
    <div class="row g-3 align-items-end">
      <div class="col-md-8">
        <label for="id_evento" class="form-label">Selecciona un evento</label>
        <select name="id_evento" id="id_evento" class="form-select" required>
          <option value="">-- Seleccionar --</option>
          @foreach($eventos as $evento)
            <option value="{{ $evento->id_evento }}" {{ request('id_evento') == $evento->id_evento ? 'selected' : '' }}>
              {{ $evento->nombre }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-search me-1"></i> Consultar
        </button>
      </div>
    </div>
  </form>

  {{-- Resultados --}}
  @if($eventoSeleccionado)
    <div class="card shadow-sm">
      <div class="card-header bg-warning fw-semibold">
        Estadísticas de: {{ $eventoSeleccionado->nombre }}
      </div>
      <div class="card-body">
        @php
          $total_ins = $estadisticas['total_inscripciones'] ?? 0;
          $total_atletas = $estadisticas['total_atletas'] ?? 0;
          $total_academias = $estadisticas['total_academias'] ?? 0;
        @endphp

        @if($total_ins > 0)
          <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item d-flex justify-content-between"><span>Total de Inscripciones</span><span>{{ $total_ins }}</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Total de Atletas</span><span>{{ $total_atletas }}</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Total de Academias</span><span>{{ $total_academias }}</span></li>
          </ul>

          {{-- Distribuciones --}}
          @foreach(['por_modalidad' => 'Modalidades', 'por_submodalidad' => 'Submodalidades', 'por_grado' => 'Grados', 'por_categoria' => 'Categorías'] as $key => $label)
            @php $items = is_array($estadisticas[$key] ?? null) ? $estadisticas[$key] : ([]); @endphp
            <div class="mb-3">
              <h6 class="fw-semibold">{{ $label }}</h6>
              @if(count($items))
                <ul class="list-group list-group-sm">
                  @foreach($items as $nombre => $cantidad)
                    <li class="list-group-item d-flex justify-content-between">
                      <span>{{ $nombre }}</span><span>{{ $cantidad }}</span>
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="text-muted">Sin datos registrados.</p>
              @endif
            </div>
          @endforeach
        @else
          <p class="text-muted">No hay inscripciones para este evento.</p>
        @endif
      </div>
    </div>
  @elseif(request('id_evento'))
    <div class="alert alert-danger mt-4">No se encontraron estadísticas para el evento seleccionado.</div>
  @endif
</div>
@endsection
