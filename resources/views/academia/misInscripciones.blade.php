@extends('academia')

@section('title', 'Mis Inscripciones')
@section('content')
    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary float-end">
        <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
    </a>

    <h3 class="mb-4 text-black fw-bold">Mis Inscripciones</h3>


    <div class="card table-card shadow">
        <div class="card-body p-3">
            <div class="table-responsive" style="overflow-x: auto;">
                <div class="mb-4 d-flex justify-content-end">
                    <input type="text" id="buscador" class="form-control form-control-sm" style="max-width: 220px;"
                        placeholder="Buscar inscripción...">
                </div>
                <table id="tablaMisInscripciones" class="table table-striped table-hover table-bordered text-center border">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Evento</th>
                            <th>Academia</th>
                            <th>Encargado</th>
                            <th>Cantidad de inscritos</th>
                            <th>Totalidad de montos</th>
                            <th>Estado</th>
                            <th>Inicio del evento</th>
                            <th>Tipo Inscripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inscripcionesAgrupadas as $index => $ins)
                            <tr>
                                <td data-id-evento="{{ $ins->evento->id_evento }}">{{ $index + 1 }}</td>
                                <td>{{ $ins->evento->nombre }}</td>
                                <td>{{ $academia->nombre }}</td>
                                <td>{{ $academia->profesor_encargado }}</td>
                                <td>{{ $ins->cantidad_inscritos }}</td>
                                
                                <td>{{ number_format((float)(data_get($ins, 'total_montos') ?? data_get($ins, 'total_monto') ?? data_get($ins, 'total') ?? 0), 2) }}</td>
                                <td>
                                    @if($ins->estado == 'activa')
                                        Enviado
                                    @elseif($ins->estado == 'inactiva')
                                        Pendiente
                                    @elseif($ins->estado == 'cancelada')
                                        Cancelada
                                    @else
                                        Estado desconocido
                                    @endif
                                </td>
                                <td>{{ $ins->evento->fecha_inicio }}</td>
                                <td>tardiaaa ejemplo</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
    @if($ins->estado == 'activa')
        <!-- Botones deshabilitados -->
        <button type="button" class="btn btn-sm btn-warning rounded-pill" disabled>
            <i class="bi bi-pencil-square"></i> 
        </button>
        <button type="button" class="btn btn-sm btn-danger rounded-pill" disabled>
            <i class="bi bi-trash"></i>
        </button>
    @else
        <!-- Botones habilitados -->
        <a href="{{ route('editar.inscripcion', ['id_evento' => $ins->evento->id_evento]) }}"
           class="btn btn-sm btn-warning rounded-pill">
            <i class="bi bi-pencil-square"></i> 
        </a>
        <button type="button" class="btn btn-sm btn-danger rounded-pill"
                onclick="confirmarEliminacion({{ $ins->evento->id_evento }})">
            <i class="bi bi-trash"></i> 
        </button>
    @endif
</div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span>Descargar <a href="{{ route('inscripciones.academia.pdf', $academia->id_academia) }}">PDF</a></span>
            </div>
            <!-- ...tu tabla aquí... -->
        </div>
        
        <!-- <nav class="mt-3">
            <ul class="pagination justify-content-aligh-right">
                <li class="page-item disabled">
                    <a class="page-link bg-light text-secondary border-0">Anterior</a>
                </li>
                <li class="page-item active">
                    <a class="page-link bg-primary border-0 text-white">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-light border-0 text-primary">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-light border-0 text-primary">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-light text-primary border-0">Siguiente</a>
                </li>
            </ul>
        </nav> -->
    </div>
    
    </div>
    </div>
    </div>
    </div>
    <!-- Buscador JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#buscador').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#tablaInscripciones tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>

@endsection