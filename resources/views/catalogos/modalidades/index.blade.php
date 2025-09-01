@extends('layouts.app')

@section('tituloArriba')
    Administrar Modalidades
@endsection

@section('breadcrumb-title', 'Lista de Modalidades')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de Modalidades</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal"
                data-bs-target="#modalModalidad">
                <i class="bi bi-plus-circle me-1"></i> Nueva Modalidad
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
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr class="text-center">
                                    <td class="small">{{ $item->nombre }}</td>
                                    <td class="small">{{ $item->descripcion }}</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit"
                                            title="Editar" data-id="{{ $item->id_modalidad }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('modalidades.destroy', $item) }}" method="POST"
                                            id="form-eliminar-{{ $item->id_modalidad }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                data-bs-toggle="tooltip" title="Eliminar Modalidad"
                                                onclick="confirmarEliminacion({{ $item->id_modalidad }})"
                                                onclick="return confirm('¿Eliminar esta academia?')">
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
        <div class="modal fade" id="modalModalidad" tabindex="-1" aria-labelledby="modalModalidadLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered ">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalModalidadLabel">
                            Registrar Nueva Modalidad</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="{{ route('modalidades.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="nombreModalidad" class="form-label">Nombre <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="nombreModalidad"
                                    name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcionModalidad" class="form-label">Descripción</label>
                                <textarea class="form-control form-control-sm" id="descripcionModalidad" name="descripcion" rows="3"></textarea>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar Modalidad</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal EDITAR --}}
        <div class="modal fade" id="modalEditarModalidad" tabindex="-1" aria-labelledby="modalEditarModalidadLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered ">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarModalidadLabel">
                            Editar Modalidad</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="" id="formEditarModalidad">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="editNombreModalidad" class="form-label">Nombre <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editNombreModalidad"
                                    name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDescripcionModalidad" class="form-label">Descripción</label>
                                <textarea class="form-control form-control-sm" id="editDescripcionModalidad" name="descripcion" rows="3"></textarea>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    {{-- Script para editar --}}
    <script>
        $(document).ready(function() {
            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let modalidadId = $(this).data('id');
                console.log('Click en editar, ID:', modalidadId); // <-- Para depurar

                $.get('/modalidades/' + modalidadId + '/datos', function(data) {
                    console.log('Datos recibidos:', data); // <-- Para depurar

                    $('#editNombreModalidad').val(data.nombre);
                    $('#editDescripcionModalidad').val(data.descripcion);


                    $('#formEditarModalidad').attr('action', '/modalidades/' + modalidadId);

                    // Abrir modal
                    let modal = new bootstrap.Modal(document.getElementById(
                        'modalEditarModalidad'));
                    modal.show();
                });
            });
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
                                text: 'La modalidad ha sido eliminada correctamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al intentar eliminar la modalidad.',
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
