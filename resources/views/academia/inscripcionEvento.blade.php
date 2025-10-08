@extends('academia')

@section('title', 'Inscripción a Evento')

@section('content')

    <input type="hidden" id="modeView"  value="{{ $bloquearSelectEventos }}">

    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary float-end">
        <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
    </a>

    <div class="container py-4">
        <h3 class="mb-4 text-black fw-bold">Inscripción de Academia a Evento</h3>

        {{-- Sección: Evento --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-semibold">
                <i class="bi bi-calendar-check me-2"></i> Selección de Evento
            </div>
            <div class="card-body">
                <select id="evento-select" class="form-select" required>
                    <option selected disabled>Selecciona un evento</option>
                    @foreach($eventos as $evento)
                        @if ($bloquearSelectEventos == false)
                            <option value="{{ $evento->id_evento }}" @if($eventosIds->contains($evento->id_evento)) disabled @endif>
                                {{ $evento->nombre }}
                            </option>
                        @else
                            <option value="{{ $evento->id_evento }}">
                                {{ $evento->nombre }}
                            </option>
                        @endif

                    @endforeach
                </select>
            </div>
        </div>

        {{-- Sección: Academia --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-semibold">
                <i class="bi bi-building me-2"></i> Datos de la Academia
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nombre de la Academia</label>
                    <input type="text" class="form-control" placeholder="{{ $academia->nombre }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Encargado</label>
                    <input type="text" class="form-control" placeholder="{{ $academia->profesor_encargado }}" readonly>
                </div>
            </div>
        </div>

        {{-- Sección: Registro de Participantes --}}
        <div id="panelRegistro" class="card mb-4 shadow-sm baseCard" style="display: none;">
            <div class="card-header fw-semibold">
                <i class="bi bi-person-plus me-2" id="lEditParticipante"></i> Registro de Participante
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-control atletas-select">
                            <option selected disabled>Selecciona un atleta</option>
                            @foreach($atletas as $atleta)
                                <option data-id="{{ $atleta->id_atleta }}" data-sexo="{{ $atleta->sexo }}"
                                    data-id_atleta="{{ $atleta->id_atleta }}"
                                    data-fecha_nacimiento="{{ $atleta->fecha_nacimiento }}"
                                    data-id_division="{{ $atleta->id_division }}">{{ $atleta->nombre }}
                                    {{ $atleta->primer_apellido }}
                                    {{ $atleta->segundo_apellido }} -
                                    {{ $atleta->identificacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select rol-select" required>
                            <option value="">Rol</option>
                            <option value="atleta">Atleta</option>
                            <option value="entrenador">Entrenador</option>
                            <option value="asistente">Asistente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control inputSexo" placeholder="Sexo" readonly>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control inputEdad" placeholder="Edad" readonly>
                    </div>
                    <div class="col-md-2">
                        <input id="pesoInput" type="number" class="form-control inputPeso" placeholder="Peso (kg)" required>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <select class="form-select modalidades-select" required>
                            <option selected disabled>Modalidad</option>

                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select submodalidades-select" required>
                            <option selected disabled>Submodalidad</option>

                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select categorias-select" required>
                            <option selected disabled>Categoria</option>
                            <!-- <option>Pareja A</option>
                                                            <option>Equipo B</option>
                                                            <option>-</option> -->
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- contenedor donde van las copias -->
        <div id="contenedor"></div>

        <div id="containerButton" class="mb-4 col-md-4 d-flex justify-content-end gap-2" style="display: none;">
            <button id="bInscribir" class="btn btn-outline-success w-100">
                <i class="bi bi-plus-circle"></i> Inscribir
            </button>
        </div>
        <hr>

        {{-- Sección: Lista de Participantes --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-semibold">
                <i class="bi bi-list-check me-2"></i> Lista de Participantes
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla-inscripcion" class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Sexo</th>
                                <th>Edad</th>
                                <th>Tipo</th>
                                <th>Modalidad</th>
                                <th>SubModalidad</th>
                                <th>Rango peso</th>
                                <th>Grupo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        {{-- Botón final --}}
        <div class="text-end mt-4">
            <button id="bEnviar" class="btn btn-primary">
                <i class="bi bi-send-check"></i> Enviar Inscripción
            </button>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.inscripcionApp = {
            continuarEdicion: {{ $bloquearSelectEventos ? 'true' : 'false' }},
            atletasInscripcion: @json($atletasInscripcion ?? []),
            eventos: @json($eventos ?? []),
            academia: @json($academia ?? null)
        };  
    </script>
@endpush