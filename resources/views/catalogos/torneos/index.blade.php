@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Listas de Eventos</h4>

    {{-- Buscador y botón --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('eventos.index') }}" class="w-50">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar evento...">
            </div>
        </form>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEvento">
            <i class="bi bi-plus-circle me-1"></i> Nuevo evento
        </button>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalEvento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEventoLabel">Crear Nuevo Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombreEvento" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombreEvento" placeholder="Nombre del evento">
                            </div>
                            <div class="col-md-6">
                                <label for="descripcionEvento" class="form-label">Descripcion</label>
                                <input type="text" class="form-control" id="descripcionEvento" placeholder="Descripcion del evento">
                            </div>
                              <div class="col-md-6">
                                <label for="fechaInicioInscripcion" class="form-label">Fecha de Inicio de Inscripción</label>
                                <input type="text" class="form-control" id="fechaInicioInscripcion" placeholder="Fecha de Inicio de Inscripción">
                            </div>
                              <div class="col-md-6">
                                <label for="fechaFinInscripcion" class="form-label">Fecha de Fin de Inscripción</label>
                                <input type="text" class="form-control" id="fechaFinInscripcion" placeholder="Fecha de Fin de Inscripción">
                            </div>
                            <div class="col-md-6">
                                <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                                <input type="text" class="form-control" id="fechaInicio" placeholder="Fecha de Inicio">
                            </div>
                            <div class="col-md-6">
                                <label for="fechaFin" class="form-label">Fecha de Fin</label>
                                <input type="text" class="form-control" id="fechaFin" placeholder="Fecha de Fin">
                            </div>
                             <div class="col-md-6">
                                <label for="imagen" class="form-label">Imagen</label>
                                <input type="text" class="form-control" id="imagen" placeholder="URL de la imagen">
                            </div>
                              <div class="col-md-6">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-select" id="estado" placeholder="Estado">
                                <select name="estado" id="estado">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Evento</button>
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
                <th>Descripción</th>
                <th>Fecha de Inicio de inscripción</th>
                <th>Fecha de Fin de inscripción</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Aquí deberías usar @foreach para mostrar datos reales --}}
                
                <tr>
                   <td>Competencia Nacional</td>
                   <td>Evento de competencia a nivel nacional</td>
                   <td>2024-07-01</td>
                   <td>2024-07-15</td>
                   <td>2024-08-01</td>
                   <td>2024-08-05</td>
                   <td>junior.jpg</td>
                   <td>Activo</td>
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
        <nav aria-label="Navegación de eventos">
            <ul class="pagination mb-0">
                <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
