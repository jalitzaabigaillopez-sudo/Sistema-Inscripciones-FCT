@extends('app')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Listas de Academias</h4>

    {{-- Buscador y botón --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('academias.index') }}" class="w-50">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar academia...">
            </div>
        </form>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAcademia">
            <i class="bi bi-plus-circle me-1"></i> Nueva academia
        </button>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalAcademia" tabindex="-1" aria-labelledby="modalAcademiaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAcademiaLabel">Crear Nueva Academia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombreAcademia" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombreAcademia" placeholder="Academia Central">
                            </div>
                            <div class="col-md-6">
                                <label for="direccionAcademia" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccionAcademia" placeholder="Siquirres, Limón">
                            </div>
                            <div class="col-md-6">
                                <label for="ProfesorAcademia" class="form-label">Profesor encargado</label>
                                <input type="text" class="form-control" id="ProfesorAcademia" placeholder="Nombre del profesor encargado">
                            </div>
                            <div class="col-md-6">
                                <label for="telefonoAcademia" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefonoAcademia" placeholder="+506 8888-8888">
                            </div>
                            <div class="col-md-6">
                                <label for="correoAcademia" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="correoAcademia" placeholder="academia@email.com">
                            </div>
                            <div class="col-md-6">
                                <label for="estadoAcademia" class="form-label">Estado</label>
                                <select class="form-select" id="estadoAcademia">
                                    <option selected disabled>Selecciona el estado</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="pendiente">Pendiente</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Academia</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Profesor a cargo</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Usuario</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Aquí deberías usar @foreach para mostrar datos reales --}}
                
                <tr>
                    <td>Academia 1</td>
                    <td>Profesor 1</td>
                    <td>Siquirres</td>
                    <td>correo1@email.com</td>
                    <td>8888-881</td>
                    <td>usuario1</td>
                    <td>Limón</td>
                    <td>
                        <span class="badge bg-success">Activo</span>
                    </td>
                    <td class="text-center">
                        <a href="#" class="btn btn-sm btn-outline-info me-1" title="Ver detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-primary me-1" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="#" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar esta academia?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
              
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-end mt-3">
        <nav aria-label="Navegación de academias">
            <ul class="pagination mb-0">
                <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
