@extends('academia')

@section('title', 'Inscripción a Evento')

@section('breadcrumb-title', 'Inscripción a Evento')

@section('content')

    <input type="hidden" id="modeView" value="{{ $bloquearSelectEventos }}">

    <div class="container-fluid">
        <h3 class="mb-4 text-black fw-bold">Inscripción de Academia a Evento</h3>

        <h6 class="mb-4 text-black fw-bold">Academia: {{ $academia->nombre }} | Encargado:
            {{ $academia->profesor_encargado }}</h6>

        {{-- Sección: Evento --}}
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-calendar-check me-2"></i> Selección de Evento
                    </div>
                    <span id="lFechaEvento" class="text-end"></span>
                </div>
                    <div class="card-body">

                        {{-- 🔔 Contenedor para mostrar el aviso solo en caso tardío --}}
                        <div id="aviso-periodo" style="display: none;"></div>

                        <select id="evento-select" class="form-select" required>
                            <option selected disabled>Selecciona un evento</option>
                            @foreach ($eventos as $evento)
                                @php
                                    $hoy = \Carbon\Carbon::today();
                                    $fase = 'normal';
                                    if (
                                        $evento->fecha_final_inscripcion_tardia &&
                                        $hoy->gt($evento->fecha_final_inscripcion)
                                    ) {
                                        $fase = 'tardía';
                                    }
                                @endphp

                                <option value="{{ $evento->id_evento }}"
                                    data-fecha-inicio="{{ $evento->fecha_inicio_inscripcion }}"
                                    data-fecha-fin="{{ $evento->fecha_final_inscripcion }}"
                                    data-fecha-tardia="{{ $evento->fecha_final_inscripcion_tardia }}"
                                    data-modo="{{ $evento->tipoEvento->modo }}"
                                    data-fase="{{ $fase }}" @if (!$bloquearSelectEventos && isset($eventosIds) && $eventosIds->contains($evento->id_evento)) disabled @endif>
                                    {{ $evento->nombre }}
                                    @if ($fase === 'tardía')
                                        (Inscripción tardía)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>


        {{-- Sección: Academia --}}
        {{-- <div class="card mb-4 shadow-sm">
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
        </div> --}}

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
                            @foreach ($atletas as $atleta)
                                <option data-id="{{ $atleta->id_atleta }}" data-sexo="{{ $atleta->sexo }}"
                                    data-id_atleta="{{ $atleta->id_atleta }}"
                                    data-fecha_nacimiento="{{ $atleta->fecha_nacimiento }}"
                                    data-id_division="{{ $atleta->id_division }}"
                                    data-id_grado="{{ $atleta->grado->id_grado }}"
                                    data-grado="{{ $atleta->grado->nombre }}"
                                    data-division="{{ $atleta->division->division }}"
                                    
                                    
                                    data-nombre="{{ $atleta->nombre }}"
                                    data-primer_apellido="{{ $atleta->primer_apellido }}"
                                    data-segundo_apellido="{{ $atleta->segundo_apellido }}"
                                    data-identificacion="{{ $atleta->identificacion }}"
                                    >
                                    {{ $atleta->nombre }}
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
                    <div class="col-md-1">
                        <input type="text" class="form-control inputEdad" placeholder="Edad" readonly>
                    </div>
                    <div class="col-md-2">
                        <input id="inputDivision" type="text" class="form-control inputDivision" placeholder="Division" readonly>
                    </div>
                    <div class="col-md-1">
                        <input id="pesoInput" type="number" class="form-control inputPeso" placeholder="Peso (kg)"
                            required>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-2">
                        <select class="form-select grados-select" required>
                            <option selected disabled>Grado</option>

                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select modalidades-select" required>
                            <option selected disabled>Modalidad</option>

                        </select>
                    </div>
                    <div class="col-md-3">
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
                                <th>Grado</th>
                                <th>Division</th>
                                <th>Modalidad</th>
                                <th>SubModalidad</th>
                                <th>Categoria</th>
                                <th style="display:none;">Grupo</th>
                                <th>Equipo</th>
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

    <!-- MODAL -->
        <div class="modal fade" id="I_modalEditarAtleta" tabindex="-1" aria-labelledby="modalEditarAtletaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="I_modalEditarAtletaLabel">
                            Actualizar Grado del Atleta</h5>

                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" name="cerrar"
                            aria-label="Cerrar"></button>
                    </div>
                    
                        <div class="modal-body p-0">
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información Personal</h6>
                                    
                                    <div class="mb-3">
                                        <label for="e_identificacion" class="form-label">Identificación</label>
                                        <input type="text" class="form-control form-control-sm" id="e_identificacion"
                                            name="identificacion" required disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="e_nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control form-control-sm" id="e_nombre"
                                            name="nombre" required disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="e_apellido1" class="form-label">Primer Apellido</label>
                                        <input type="text" class="form-control form-control-sm" id="e_apellido1"
                                            name="primer_apellido" required disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="e_apellido2" class="form-label">Segundo Apellido</label>
                                        <input type="text" class="form-control form-control-sm" id="e_apellido2"
                                            name="segundo_apellido" disabled>
                                    </div>
                                </div>

                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Información Deportiva</h6>

                                    <div class="row">


                                        <div class="col-md-6 mb-3">
                                            <label for="e_grado" class="form-label">Grado (Cinturón) <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="e_grado" name="id_grado"
                                                required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" name="cancelar"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button id="I_bActualizarGrado" type="button" class="btn btn-success rounded-pill">Guardar cambios</button>
                        </div>
                    
                </div>
            </div>
        </div>
     
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectEvento = document.getElementById("evento-select");
            const avisoDiv = document.getElementById("aviso-periodo");

            selectEvento.addEventListener("change", function() {
                const selectedOption = selectEvento.options[selectEvento.selectedIndex];
                const fase = selectedOption.dataset.fase;
                const fechaTardia = selectedOption.dataset.fechaTardia;

                avisoDiv.style.display = "none";
                avisoDiv.innerHTML = "";

                if (fase === "tardía") {
                    // Formatear fecha tardía
                    let fechaFormateada = "N/D";
                    if (fechaTardia) {
                        const fecha = new Date(fechaTardia);
                        fechaFormateada = fecha.toLocaleDateString("es-ES", {
                            day: "numeric",
                            month: "long",
                            year: "numeric"
                        });
                    }

                    avisoDiv.innerHTML = `
                        <div class="alert alert-warning border-2 shadow-sm" role="alert"
                            style="border-left: 5px solid #f39c12; background-color: #fff8e1;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-warning"></i>
                                <div>
                                    <strong>Período de inscripción tardía en curso</strong><br>
                                    Este evento se encuentra actualmente en el período de <b>inscripción tardía</b>.
                                    <div class="small text-muted mt-1">
                                        <i class="bi bi-calendar-event me-1"></i> Vigente hasta el 
                                        <b>${fechaFormateada}</b>.
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    avisoDiv.style.display = "block";
                }
            });

            // FORZAR EJECUCIÓN AL CARGAR (cuando vienes del dashboard)
            setTimeout(() => {
                if (selectEvento.selectedIndex > 0) {
                    selectEvento.dispatchEvent(new Event('change'));
                }
            }, 100);
        });

        window.inscripcionApp = {
            continuarEdicion: {{ $bloquearSelectEventos ? 'true' : 'false' }},
            atletasInscripcion: @json($atletasInscripcion ?? []),
            eventos: @json($eventos ?? []),
            academia: @json($academia ?? null)
        };
    </script>



<!-- MODAL -->
 <script>
    // Esperar a que el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('I_modalEditarAtleta');

        modal.addEventListener('hidden.bs.modal', function () {
            // Limpiar todos los inputs dentro del modal
            modal.querySelectorAll('input, select').forEach(el => {
                // Si es un input tipo texto
                if (el.tagName === 'INPUT' && el.type === 'text') {
                    el.value = '';
                }

                // Si es un select
                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0; // vuelve al "Seleccione..."
                }
            });

            // Si deseas eliminar datos asociados, por ejemplo variables globales
            // o contenido cargado dinámicamente, puedes hacerlo aquí
            console.log('Modal cerrado: datos limpiados');
        });
    });
</script>

@endpush
