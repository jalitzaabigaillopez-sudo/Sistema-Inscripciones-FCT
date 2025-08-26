@extends('app')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Listas de Usuarios</h4>

    {{-- Buscador y botón --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('usuarios.index') }}" class="w-50">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar usuario...">
            </div>
        </form>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="bi bi-plus-circle me-1"></i> Nuevo usuario
        </button>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioLabel">Crear Nueva Academia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombreUsuario" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombreUsuario" placeholder="Nombre del usuario">
                            </div>
                            <div class="col-md-6">
                                <label for="correoUsuario" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correoUsuario" placeholder="correo@email.com">
                            </div>
                            <div class="col-md-6">
                                <label for="contrasenaUsuario" class="form-label">Contraseña</label>
                                <input type="text" class="form-control" id="contrasenaUsuario" placeholder="Contraseña del usuario">
                            </div>
                            <div class="col-md-6">
                                <label for="rolUsuario" class="form-label">Rol</label>
                                <select class="form-select" id="rolUsuario">
                                    <option selected disabled>Selecciona el rol</option>
                                    <option value="administrador">Administrador</option>
                                    <option value="Academia">Academia</option>
                                    <option value="arbitro">Arbitro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="estadoUsuario" class="form-label">Estado</label>
                                <select class="form-select" id="estadoUsuario">
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
                    <th>Identificación</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Contraseña</th>
                <th>Rol</th>
                <th>Estado</th>
                <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Aquí deberías usar @foreach para mostrar datos reales --}}
                
                <tr>
                       
                <td>123456789</td>
                <td>Doris Vega</td>
                <td>doris@email.com</td>
                <td>contraseña123</td>
                <td>Administrador</td>
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


