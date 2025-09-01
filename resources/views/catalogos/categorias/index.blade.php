@extends('layouts.app')

@section('tituloArriba')
    Administrar Categorías
@endsection

@section('breadcrumb-title', 'Lista de Categorías')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-bold mb-0">Lista de Categorías</h4>
        <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal" data-bs-target="#modalCategoria">
            <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
        </button>
    </div>
    <hr>

    {{-- Tabla --}}
    <div class="card table-card shadow">
        <div class="card-body p-3">
            <div class="table-responsive" style="overflow-x: auto;">
                <table id="tabla" class="table table-striped table-hover table-bordered text-center border">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">División</th>
                            <th class="text-center">Sexo</th>
                            <th class="text-center">Peso Mínimo</th>
                            <th class="text-center">Peso Máximo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $categoria)
                        <tr class="text-center">
                            <td>{{ $categoria->division }}</td>
                            <td>{{ $categoria->sexo }}</td>
                            <td>{{ $categoria->peso_min }}</td>
                            <td>{{ $categoria->peso_max }}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit" 
                                   data-id="{{ $categoria->id }}" 
                                   data-division="{{ $categoria->division }}" 
                                   data-sexo="{{ $categoria->sexo }}" 
                                   data-peso_minimo="{{ $categoria->peso_minimo }}" 
                                   data-peso_maximo="{{ $categoria->peso_maximo }}"
                                   data-bs-toggle="modal" data-bs-target="#modalEditarCategoria">
                                   <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" id="form-eliminar-{{ $categoria->id }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                        onclick="confirmarEliminacion({{ $categoria->id }})">
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
    </div>

    {{-- Modal CREAR --}}
    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalCategoriaLabel">
                        Crear Nueva Categoría
                    </h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="{{ route('categorias.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="division" class="form-label">División <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="division" name="division" required>
                        </div>
                        <div class="mb-3">
                            <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="sexo" name="sexo" required>
                        </div>
                        <div class="mb-3">
                            <label for="pesoMinimo" class="form-label">Peso Mínimo <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="pesoMinimo" name="peso_minimo" required>
                        </div>
                        <div class="mb-3">
                            <label for="pesoMaximo" class="form-label">Peso Máximo <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="pesoMaximo" name="peso_maximo" required>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success rounded-pill">Guardar Categoría</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal EDITAR --}}
    <div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarCategoriaLabel">
                        Editar Categoría
                    </h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" id="formEditarCategoria">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editDivision" class="form-label">División <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editDivision" name="division" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editSexo" name="sexo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPesoMinimo" class="form-label">Peso Mínimo <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="editPesoMinimo" name="peso_minimo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPesoMaximo" class="form-label">Peso Máximo <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="editPesoMaximo" name="peso_maximo" required>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success rounded-pill">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Script para editar y eliminar --}}
<script>
    $(document).on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        let division = $(this).data('division');
        let sexo = $(this).data('sexo');
        let peso_minimo = $(this).data('peso_minimo');
        let peso_maximo = $(this).data('peso_maximo');

        $('#editDivision').val(division);
        $('#editSexo').val(sexo);
        $('#editPesoMinimo').val(peso_minimo);
        $('#editPesoMaximo').val(peso_maximo);
        $('#formEditarCategoria').attr('action', '/categorias/' + id);
    });

    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $('#form-eliminar-' + id).attr('action'),
                    method: $('#form-eliminar-' + id).attr('method'),
                    data: $('#form-eliminar-' + id).serialize(),
                    success: function(response) {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'La categoría ha sido eliminada correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al intentar eliminar la categoría.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    }
</script>
@endsection


    
               
             
              
       