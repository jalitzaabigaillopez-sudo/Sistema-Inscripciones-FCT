@extends('academia')

@section('title', 'Inscripción a Evento')

@section('content')
<a href="{{ route('dashboard.academias') }}" class="btn btn-outline-primary float-end">
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
            <select class="form-select">
                <option selected disabled>Selecciona un evento</option>
                <option>Campeonato Nacional Juvenil</option>
                <option>Festival TKD Infantil</option>
                <option>Open Costa Rica</option>
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
                <input type="text" class="form-control" placeholder="Academia XYZ">
            </div>
            <div class="mb-3">
                <label class="form-label">Encargado</label>
                <input type="text" class="form-control" placeholder="Nombre completo">
            </div>
        </div>
    </div>

    {{-- Sección: Registro de Participantes --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-semibold">
            <i class="bi bi-person-plus me-2"></i> Registro de Participantes
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Nombre completo">
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected disabled>Sexo</option>
                        <option>Masculino</option>
                        <option>Femenino</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" placeholder="Edad">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" placeholder="Peso (kg)">
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected disabled>Modalidad</option>
                        <option>Combate</option>
                        <option>Poomsae</option>
                        <option>Freestyle</option>
                        <option>TK13</option>
                    </select>
                </div>
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
                <div class="col-md-4 align-text-end">
                    <button class="btn btn-outline-success w-100">
                        <i class="bi bi-plus-circle"></i> Agregar Participante
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección: Lista de Participantes --}}
    <div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold">
        <i class="bi bi-list-check me-2"></i> Lista de Participantes
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Sexo</th>
                        <th>Edad</th>
                        <th>Peso</th>
                        <th>Modalidad</th>
                        <th>Participación</th>
                        <th>Tipo</th>
                        <th>Grupo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Juan Pérez</td>
                        <td>Masculino</td>
                        <td>15</td>
                        <td>52</td>
                        <td>Combate</td>
                        <td>Individual</td>
                        <td>Atleta</td>
                        <td>—</td>
                           <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary rounded-pill" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                             <button class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar">
                            <i class="bi bi-trash"></i>
                           </button>
                           </div>
                         </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>María Gómez</td>
                        <td>Femenino</td>
                        <td>14</td>
                        <td>48</td>
                        <td>Poomsae</td>
                        <td>Pareja</td>
                        <td>Atleta</td>
                        <td>Pareja A</td>
                          <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary rounded-pill" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                             <button class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar">
                            <i class="bi bi-trash"></i>
                           </button>
                           </div>
                         </td>
                    </tr>
                    <tr>
                        
                    </tr>
                        <td>3</td>
                        <td>Carlos Ruiz</td>
                        <td>Masculino</td>
                        <td>16</td>
                        <td>60</td>
                        <td>Freestyle</td>
                        <td>Trío</td>
                        <td>Atleta</td>
                        <td>Equipo B</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary rounded-pill" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                             <button class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar">
                            <i class="bi bi-trash"></i>
                           </button>
                           </div>
                         </td>

                    {{-- Más filas estáticas si deseas mostrar ejemplos --}}
                </tbody>
            </table>
        </div>
    </div>
</div>


    {{-- Botón final --}}
    <div class="text-end mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-send-check"></i> Enviar Inscripción
        </button>
    </div>

</div>
@endsection


