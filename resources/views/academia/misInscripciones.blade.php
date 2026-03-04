@extends('academia')

@section('title', 'Mis Inscripciones')

@section('breadcrumb-title', 'Mis inscripciones')

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
                <span>Descargar <a href="{{ route('inscripciones.academia.pdf', $academia->id_academia) }}">PDF <i class="bi bi-file-earmark-pdf"></i></a> </span>

                @php
                $hayTardia = collect($inscripcionesAgrupadas)->contains(fn($x) => !empty($x->en_tardia) && $x->en_tardia);
                @endphp

                @if($hayTardia)
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                    Hay eventos en <b>período de inscripción tardía</b>. Si realizas cambios, la inscripción puede quedar <b>Pendiente</b> para reenviar.
                    </div>
                </div>
                @endif

                <table id="tablaMisInscripciones" class="table table-striped table-hover table-bordered text-center border">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Evento</th>
                            <th>Academia</th>
                            <th>Encargado</th>
                            <th>Cantidad de inscritos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Inicio del evento</th>
                            <th>Tipo</th>
                            <th>Avisos</th>
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
                                
                                <td>₡{{ number_format((float)($ins->total_monto ?? 0), 2) }}</td>
                               {{-- ✅ ESTADO: solo proceso --}}
                                <td>
                                @if($ins->estado === 'activa')
                                    <span class="badge bg-success">Enviado</span>
                                @elseif($ins->estado === 'inactiva')
                                    <span class="badge bg-secondary">Pendiente</span>
                                @elseif($ins->estado === 'cancelada')
                                    <span class="badge bg-danger">Cancelada</span>
                                @else
                                    <span class="badge bg-dark">Desconocido</span>
                                @endif
                                </td>

                                <td>{{ $ins->evento->fecha_inicio }}</td>

                               {{-- ✅ TIPO: normal/tardia/mixto --}}
                                <td>
                                @php $tipo = strtolower($ins->tipo_inscripcion ?? 'normal'); @endphp

                                @if($tipo === 'mixto')
                                    <span class="badge bg-info text-dark" title="Tiene inscripciones en periodo normal y tardío." data-bs-toggle="tooltip">Mixto</span>
                                @elseif($tipo === 'tardia')
                                    <span class="badge bg-warning text-dark">Tardía</span>
                                @else
                                    <span class="badge bg-primary">Normal</span>
                                @endif
                                </td>

                                {{-- ✅ AVISOS: iconos pequeños (no saturan) --}}
                                <td class="text-center">
                                @if(!empty($ins->en_tardia) && $ins->en_tardia)
                                    <span class="badge bg-warning text-dark"
                                        title="Evento en período tardío"
                                        data-bs-toggle="tooltip">
                                        <i class="bi bi-clock-history"></i>
                                    </span>

                                    @if($ins->estado === 'activa')
                                        <span class="badge bg-light text-dark border ms-1"
                                            title="Edición habilitada por tardía. Si guardas cambios, quedará Pendiente."
                                            data-bs-toggle="tooltip">
                                            <i class="bi bi-unlock-fill"></i>
                                        </span>
                                    @endif

                                @elseif(!empty($ins->cerrado) && $ins->cerrado)
                                    <span class="badge bg-dark"
                                        title="El período de inscripción ha finalizado"
                                        data-bs-toggle="tooltip">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>

                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- ✅ ACCIONES --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    {{-- EDITAR: bloquea si cerrado --}}
                                    @if(!empty($ins->can_edit) && $ins->can_edit && empty($ins->cerrado))
                                    <a href="{{ route('editar.inscripcion', ['id_evento' => $ins->evento->id_evento]) }}"
                                        class="btn btn-sm btn-warning rounded-pill"
                                        title="Editar" data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @else
                                    <button type="button"
                                            class="btn btn-sm btn-warning rounded-pill"
                                            disabled
                                            title="{{ !empty($ins->cerrado) && $ins->cerrado ? 'Evento vencido' : 'No editable en este momento' }}"
                                            data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @endif

                                    {{-- ELIMINAR: lo dejas como estaba (solo depende de can_edit si así lo querés) --}}
                                    @if(!empty($ins->can_edit) && $ins->can_edit)
                                    <button type="button"
                                            class="btn btn-sm btn-danger rounded-pill bEliminarMiInscripcion"
                                            title="Eliminar" data-bs-toggle="tooltip">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @else
                                    <button type="button"
                                            class="btn btn-sm btn-danger rounded-pill"
                                            disabled
                                            title="No disponible" data-bs-toggle="tooltip">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif

                                </div>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

        @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
    });
    </script>
    @endpush

    <script>
        $('#buscador').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#tablaMisInscripciones tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>

@endsection