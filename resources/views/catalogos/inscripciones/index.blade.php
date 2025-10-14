@extends('app')

@section('tituloArriba')
    Inscripciones a Eventos
@endsection

@section('breadcrumb-title', 'Lista de Eventos')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Inscripciones por Eventos</h4>
        </div>

        <!--  
                        {{-- Selección de Evento --}}
                        <div class="mb-4">
                            <label for="eventoSelect" class="form-label">Seleccione un evento</label>
                            <select id="eventoSelect" class="form-select">
                                <option value="" selected disabled>-- Elige un evento --</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }}
                                        ({{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Botón para inscribirse --}}
                        <div class="mb-4">
                            {{-- data-bs-target="#modalInscripcionAcademia" disabled id="btnInscribirse" --}}
                            <button type="button" class="btn btn-success" data-bs-toggle="modal">
                                <i class="bi bi-plus-circle me-1"></i> Inscribir Atleta
                            </button>
                        </div>
                        -->

        {{-- Modal de Inscripción de Academia --}}
        <div class="modal fade" id="modalInscripcionAcademia" tabindex="-1" aria-labelledby="modalInscripcionAcademiaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold w-100 mb-3" id="modalInscripcionAcademiaLabel">
                            Inscribirse como Academia
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="{{ route('inscripciones.store') }}">
                            @csrf
                            <input type="hidden" name="evento_id" id="evento_id">
                            <div class="mb-3">
                                <label for="academia" class="form-label">Seleccione Academia</label>
                                <select name="academia_id" id="academia" class="form-select" required>
                                    <option value="" selected disabled>-- Elige una academia --</option>
                                    @foreach($academias as $academia)
                                        <option value="{{ $academia->id }}">{{ $academia->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar Inscripción</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Inscripciones --}}
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover table-bordered text-center border" id="tablaInscripciones">
                <thead class="table-light">
                    <tr>
                        <th>Evento</th>
                        <th>Academia</th>
                        <th>Numero de atletas inscritos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscripciones as $item)
                                    <tr>
                                        <td>{{ $item->evento->nombre }}</td>
                                        <td>{{ $item->academia->nombre }}</td>
                                        <td>{{ $item->total_atletas }}</td>

                                        <td>
                                            <span class="badge rounded-pill 
                                                {{ $item->estado == 'activa' ? 'bg-success' :
                                                ($item->estado == 'inactiva' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ ucfirst($item->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->estado === 'activa')
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill" type="button"
                data-bs-toggle="dropdown">
            </button>
            <ul class="dropdown-menu">

                <a class="dropdown-item btn-edit"
                    href="{{ route('admin.editar.inscripcion', ['id_evento' => $item->evento->id_evento, 'id_academia' => $item->academia->id_academia]) }}">
                    <i class="bi bi-pencil-square"></i> Ver/Editar
                </a>

                <li>
                    <a class="dropdown-item text-danger" href="{{ route('admin.eliminar.inscripcion', ['id_evento' => $item->evento->id_evento, 'id_academia' => $item->academia->id_academia]) }}">
                        <i class="bi bi-trash"></i> Eliminar
                    </a>
                </li>

            </ul>
        </div>
    @endif
                                        </td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <span>Descargar <a href="{{ route('inscripciones.pdf') }}">PDF</a> - <a href="{{ url('/inscripciones/exportar-excel') }}">Excel</a></span>
    </div>
  

@endsection
