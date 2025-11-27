@extends('academia')

@section('title', 'Reportes generales')

@section('breadcrumb-title', 'Reportes Generales')

@section('content')
    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary float-end">
        <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
    </a>

    <div class="container py-4">
        <h3 class="mb-4 text-black fw-bold">Reportes de eventos</h3>

        {{-- Sección: Evento --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-semibold">
                <i class="bi bi-calendar-check me-2"></i> Selección de Evento
            </div>
            <div class="card-body">
                <select id="reporte-evento-select" class="form-select" required>
                    <option selected disabled>Selecciona un evento</option>
                    @foreach($eventos as $evento)
                        <option value="{{ $evento->id_evento }}">
                            {{ $evento->nombre }} - {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }} --
                            {{ \Carbon\Carbon::parse($evento->fecha_final)->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="text-end mt-4">
            <button id="bExportar" class="btn btn-secundary">
                <i class="bi bi-send-check"></i> Exportar
            </button>
        </div>

    </div>

@endsection