@extends('app')

@section('tituloArriba')
    Inscripciones a Eventos
@endsection

@section('breadcrumb-title', 'Lista de Eventos')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-bold mb-0">Inscripciones por Evento</h4>
    </div>

    {{-- Selección de Evento --}}
    <div class="mb-4">
        <label for="eventoSelect" class="form-label">Seleccione un evento</label>
        <select id="eventoSelect" class="form-select">
            <option value="" selected disabled>-- Elige un evento --</option>
            @foreach($eventos as $evento)
                <option value="{{ $evento->id }}">{{ $evento->nombre }} ({{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }})</option>
            @endforeach
        </select>
    </div>


    {{-- Modal de Inscripción de Academia --}}
    <div class="modal fade" id="modalInscripcionAcademia" tabindex="-1" aria-labelledby="modalInscripcionAcademiaLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-center fw-bold w-100 mb-3" id="modalInscripcionAcademiaLabel">
                        Inscribirse como Academia
                    </h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="#">
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
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success rounded-pill">Guardar Inscripción</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  

<!-- Modal -->
<!-- Botón que abre el modal -->
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalInscripcionAdmin">
    <i class="bi bi-plus-circle"></i> Inscribir como Academia a Evento
</button>

<!-- Modal -->
<div class="modal fade" id="modalInscripcionAdmin" tabindex="-1" aria-labelledby="modalInscripcionAdminLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
      
      <div class="modal-header border-bottom-0 pb-2">
        <h5 class="modal-title fw-bold w-100 text-center" id="modalInscripcionAdminLabel">
          Inscripción de Academia a Evento
        </h5>
        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body p-0">
        <form method="POST" action="#">
          @csrf

          <!-- Selección de Evento -->
          <div class="mb-3">
            <label for="evento_id" class="form-label">Seleccione un evento</label>
            <select name="evento_id" id="evento_id" class="form-select" required>
              <option value="" disabled selected>-- Elige un evento --</option>
              @foreach($eventos as $evento)
                <option value="{{ $evento->id }}">
                  {{ $evento->nombre }} ({{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Selección de Academia -->
          <div class="mb-3">
            <label for="academia_id" class="form-label">Seleccione una academia</label>
            <select name="academia_id" id="academia_id" class="form-select" required>
              <option value="" disabled selected>-- Elige una academia --</option>
              @foreach($academias as $academia)
                <option value="{{ $academia->id }}">{{ $academia->nombre }}</option>
              @endforeach
            </select>
          </div>

          <!-- Registro de Participantes -->
          <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold">
              <i class="bi bi-person-plus me-2"></i> Registro de Participantes
            </div>
            <div class="card-body" id="participantesContainer">
              <!-- Aquí se agregan dinámicamente los participantes -->
            </div>
            <div class="card-footer text-end">
              <button type="button" class="btn btn-outline-success" id="addParticipante">
                <i class="bi bi-plus-circle"></i> Agregar Participante
              </button>
            </div>
          </div>

          <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success rounded-pill">Guardar Inscripción</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<!-- Script para añadir participantes dinámicamente -->
<script>
let participanteIndex = 0;

document.getElementById('addParticipante').addEventListener('click', function() {
    const container = document.getElementById('participantesContainer');

    const row = document.createElement('div');
    row.classList.add('row', 'g-3', 'mb-2');
    row.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="participantes[${participanteIndex}][nombre]" class="form-control" placeholder="Nombre completo" required>
        </div>
        <div class="col-md-2">
            <select name="participantes[${participanteIndex}][sexo]" class="form-select" required>
                <option disabled selected>Sexo</option>
                <option>Masculino</option>
                <option>Femenino</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="participantes[${participanteIndex}][edad]" class="form-control" placeholder="Edad" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="participantes[${participanteIndex}][peso]" class="form-control" placeholder="Peso (kg)" required>
        </div>
        <div class="col-md-2">
            <select name="participantes[${participanteIndex}][modalidad]" class="form-select" required>
                <option disabled selected>Modalidad</option>
                <option>Combate</option>
                <option>Poomsae</option>
                <option>Freestyle</option>
                <option>TK13</option>
            </select>
        </div>
        <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <select class="form-select">
                        <option selected disabled>Tipo de participación</option>
                        <option>Individual</option>
                        <option>Pareja</option>
                        <option>Trío</option>
                        <option>Equipo</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select">
                        <option selected disabled>Tipo de asistente</option>
                        <option>Atleta</option>
                        <option>Entrenador</option>
                        <option>Asistente</option>
                    </select>
                </div>
                 <div class="col-md-4">
                    <select class="form-select">
                        <option selected disabled>Grupo</option>
                        <option>Pareja A</option>
                        <option>Equipo B</option>
                        <option>-</option>
                    </select>
                </div>
        <div class="col-md-1 text-center">
            <button type="button" class="btn btn-sm btn-outline-danger removeParticipante">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;

    container.appendChild(row);

    // Botón eliminar
    row.querySelector('.removeParticipante').addEventListener('click', function() {
        row.remove();
    });

    participanteIndex++;
});
</script>

    <!-- Modal -->

    {{-- Tabla de Inscripciones --}}
    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover table-bordered text-center border" id="tablaInscripciones">
            <thead class="table-light">
                <tr>
                    <th>Evento</th>
                    <th>Academia</th>
                    <th>Entrenadores</th>
                    <th>Asistentes</th>
                    <th>Atletas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $inscripcion)
                <tr>
                    <td>{{ $inscripcion->evento->nombre }}</td>
                    <td>{{ $inscripcion->atleta?->academia?->nombre ?? 'N/A'  }}</td>
                    <td>{{ $inscripcion->entrenadores_count }}</td>
                    <td>{{ $inscripcion->asistentes_count }}</td>
                    <td>{{ $inscripcion->atletas_count }}</td>
                    <td>
                        <span class="badge rounded-pill {{ $inscripcion->estado == 'activo' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($inscripcion->estado) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('inscripciones.destroy', $inscripcion) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Eliminar inscripción?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Script para habilitar botón de inscribirse según evento seleccionado --}}
<script>
    document.getElementById('eventoSelect').addEventListener('change', function() {
        document.getElementById('btnInscribirse').disabled = false;
        document.getElementById('evento_id').value = this.value;
    });
</script>
<script>
let participanteIndex = 0;

document.getElementById('addParticipante').addEventListener('click', function() {
    const container = document.getElementById('participantesContainer');

    const row = document.createElement('div');
    row.classList.add('row', 'g-3', 'mb-2');
    row.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="participantes[${participanteIndex}][nombre]" class="form-control" placeholder="Nombre completo" required>
        </div>
        <div class="col-md-2">
            <select name="participantes[${participanteIndex}][sexo]" class="form-select" required>
                <option disabled selected>Sexo</option>
                <option>Masculino</option>
                <option>Femenino</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="participantes[${participanteIndex}][edad]" class="form-control" placeholder="Edad" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="participantes[${participanteIndex}][peso]" class="form-control" placeholder="Peso (kg)" required>
        </div>
        <div class="col-md-2">
            <select name="participantes[${participanteIndex}][modalidad]" class="form-select" required>
                <option disabled selected>Modalidad</option>
                <option>Combate</option>
                <option>Poomsae</option>
                <option>Freestyle</option>
                <option>TK13</option>
            </select>
        </div>
        <div class="col-md-1 text-center">
            <button type="button" class="btn btn-sm btn-outline-danger removeParticipante">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;

    container.appendChild(row);

    // Botón eliminar
    row.querySelector('.removeParticipante').addEventListener('click', function() {
        row.remove();
    });

    participanteIndex++;
});
</script>

@endsection
