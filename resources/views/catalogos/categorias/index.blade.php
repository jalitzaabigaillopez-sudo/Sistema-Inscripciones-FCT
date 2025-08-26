@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Listas de Categorias</h4>

    {{-- Buscador y botón --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('categorias.index') }}" class="w-50">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar categoria...">
            </div>
        </form>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCategoria">
            <i class="bi bi-plus-circle me-1"></i> Nueva categoria
        </button>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoriaLabel">Crear Nueva Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="division" class="form-label">Division</label>
                                <input type="text" class="form-control" id="division" placeholder="Division Central">
                            </div>
                            <div class="col-md-6">
                                <label for="sexo" class="form-label">Sexo</label>
                                <input type="text" class="form-control" id="sexo" placeholder="Masculino/Femenino">
                            </div>
                            <div class="col-md-6">
                                <label for="pesoMinimo" class="form-label">Peso minimo</label>
                                <input type="text" class="form-control" id="pesoMinimo" placeholder="Peso minimo">
                            </div>
                            <div class="col-md-6">
                                <label for="pesoMaximo" class="form-label">Peso máximo</label>
                                <input type="text" class="form-control" id="pesoMaximo" placeholder="Peso máximo">
                            </div>
                           
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Categoria</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                <th>División</th>
                <th>Sexo</th>
                <th>Peso Mínimo</th>
                <th>Peso Máximo</th>
                <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Aquí deberías usar @foreach para mostrar datos reales --}}
                
                <tr>
                    <td>Junior</td>
                <td>Masculino</td>
                <td>60</td>
                <td>70</td>
                  
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
        <nav aria-label="Navegación de categorias">
            <ul class="pagination mb-0">
                <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
            </ul>
        </nav>
    </div>
</div>
@endsection


