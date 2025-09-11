@extends('academia')

@section('title', 'Mis Inscripciones')
@section('content')
<a href="{{ route('dashboard.academias') }}" class="btn btn-outline-primary float-end">
    <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
</a>

<h3 class="mb-4 text-black fw-bold">Mis Inscripciones</h3>


<div class="card table-card shadow">
    <div class="card-body p-3">
        <div class="table-responsive" style="overflow-x: auto;">
             <div class="mb-4 d-flex justify-content-end">
    <input type="text" id="buscador" class="form-control form-control-sm" style="max-width: 220px;" placeholder="Buscar inscripción...">
</div>
            <table id="tablaInscripciones" class="table table-striped table-hover table-bordered text-center border">
                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        <th>Evento</th>
                        <th>Academia</th>
                        <th>Encargado</th>
                        <th>Sexo</th>
                        <th>Edad</th>
                        <th>Peso</th>
                        <th>Modalidad</th>
                        <th>Tipo de participación</th>
                        <th>Tipo de asistente</th>
                        <th>Grupo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Campeonato Nacional</td>
                        <td>Academia FCT</td>
                        <td>Juan Pérez</td>
                        <td>Masculino</td>
                        <td>18</td>
                        <td>70kg</td>
                        <td>Kata</td>
                        <td>Individual</td>
                        <td>Competidor</td>
                        <td>A</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill"
                                    type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item btn-edit" href="#">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Open Internacional</td>
                        <td>Academia FCT</td>
                        <td>Ana Gómez</td>
                        <td>Femenino</td>
                        <td>22</td>
                        <td>60kg</td>
                        <td>Kumite</td>
                        <td>Equipo</td>
                        <td>Coach</td>
                        <td>B</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill"
                                    type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item btn-edit" href="#">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>  
            </table>
        </div>
       <!-- ...tu tabla aquí... -->
        </div>
        <nav class="mt-3">
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
        </nav>
    </div>
</div>
</div>
    </div>
</div>
<!-- Buscador JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#buscador').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#tablaInscripciones tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>

@endsection