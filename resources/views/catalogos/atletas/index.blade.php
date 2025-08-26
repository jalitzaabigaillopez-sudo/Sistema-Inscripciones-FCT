@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Listas de Atletas</h4>

    {{-- Buscador y botón --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('atletas.index') }}" class="w-50">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar atleta...">
            </div>
        </form>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAtleta">
            <i class="bi bi-plus-circle me-1"></i> Nuevo atleta
        </button>
    </div>

    {{-- Modal --}}
    
    <div class="modal fade" id="modalAtleta" tabindex="-1" aria-labelledby="modalAtletaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAtletaLabel">Crear Nuevo Atleta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('atletas.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                            <input type="text" class="form-control" id="tipo_identificacion" name="tipo_identificacion" required placeholder="Ej. Cédula">
                        </div>
                        <div class="mb-3">
                            <label for="identificacion" class="form-label">Identificacion</label>
                            <input type="text" class="form-control" id="identificacion" name="identificacion" required placeholder="Ej. 123456789">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Juan">
                        </div>
                        <div class="mb-3">
                            <label for="apellido1" class="form-label">Primer apellido</label>
                            <input type="text" class="form-control" id="apellido1" name="apellido1" required placeholder="Ej. Pérez">
                        </div>
                        <div class="mb-4">
                            <label for="apellido2" class="form-label">Segundo apellido</label>
                            <input type="text" class="form-control" id="apellido2" name="apellido2" required placeholder="Ej. Gómez">
                        </div>
                          <div class="mb-4">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="atleta">Atleta</option>
                                <option value="entrenador">Entrenador</option>
                            </select>
                        </div>
                          <div class="mb-4">
                            <label for="sexo" class="form-label">Sexo</label>
                            <select class="form-select" id="sexo" name="sexo" required>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                          <div class="mb-4">
                            <label for="fecha" class="form-label">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required placeholder="1995-08-15">
                        </div>
                        <div class="mb-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
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
                  
                    <th>Tipo_Identificacion</th>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Primer apellido</th>
                    <th>Segundo apellido</th>
                    <th>Rol</th>
                    <th>Sexo</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Aquí deberías usar @foreach para mostrar datos reales --}}
                
                <tr>
                    <td>Cédula</td>
                    <td>123456789</td>
                    <td>Juan</td>
                    <td>Pérez</td>
                    <td>Gómez</td>
                    <td>Atleta</td>
                    <td>Masculino</td>
                    <td>1995-08-15</td>
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
        <nav aria-label="Navegación de atletas">
            <ul class="pagination mb-0">
                <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

