@extends('app')

@section('tituloArriba')
    Administrar Tipos de Eventos
@endsection

@section('breadcrumb-title', 'Lista de Tipos de Eventos')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-bold mb-0">Lista de Tipos de Eventos</h4>
        <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal" data-bs-target="#modalTipoEvento">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo de Evento
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
   @foreach ($data as $tipoEvento)
                        <tr class="text-center">
                            <td class="small">{{ $tipoEvento->nombre }}</td>
                            <td class="small">{{ $tipoEvento->descripcion }}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit" 
                                   data-id="{{ $tipoEvento->id_tipo_evento }}">
                                   <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('tipos_eventos.destroy', $tipoEvento) }}" method="POST" id="form-eliminar-{{ $tipoEvento->id_tipo_evento }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                        onclick="confirmarEliminacion({{ $tipoEvento->id_tipo_evento }})">
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
    <div class="modal fade" id="modalTipoEvento" tabindex="-1" aria-labelledby="modalTipoEventoLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalTipoEventoLabel">
                        Crear Nuevo Tipo de Evento
                    </h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="{{ route('tipos_eventos.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nombreTipoEvento" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="nombreTipoEvento" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionTipoEvento" class="form-label">Descripción</label>
                            <textarea class="form-control form-control-sm" id="descripcionTipoEvento" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success rounded-pill">Guardar Tipo de Evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal EDITAR --}}
    <div class="modal fade" id="modalEditarTipoEvento" tabindex="-1" aria-labelledby="modalEditarTipoEventoLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarTipoEventoLabel">
                        Editar Tipo de Evento
                    </h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" id="formEditarTipoEvento">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editNombreTipoEvento" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editNombreTipoEvento" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescripcionTipoEvento" class="form-label">Descripción</label>
                            <textarea class="form-control form-control-sm" id="editDescripcionTipoEvento" name="descripcion" rows="3"></textarea>
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

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Script para editar y eliminar --}}
<script>
   document.addEventListener('DOMContentLoaded', function() {
            // Script para editar
            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let tipoId = $(this).data('id');
                console.log('Click en editar, ID:', tipoId);

                $.get('/tipos_eventos/' + tipoId + '/datos', function(data) {
                    console.log('Datos recibidos:', data);

                    $('#editNombreTipoEvento').val(data.nombre);
                    $('#editDescripcionTipoEvento').val(data.descripcion);

                    $('#formEditarTipoEvento').attr('action', '/tipos_eventos/' + data.id_tipo_evento);

                    let modal = new bootstrap.Modal(document.getElementById(
                        'modalEditarTipoEvento'));
                    modal.show();
                });
            });

            // Función para confirmar eliminación
            window.confirmarEliminacion = function(id) {
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
                        // Enviamos la petición de eliminación al backend
                        $.ajax({
                            url: '/tipos_eventos/' + id,
                            method: 'POST', // Usamos POST y simulamos DELETE
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: 'El tipo de evento ha sido eliminada correctamente.',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un error al intentar eliminar el tipo de evento.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            }

            // Manejo de SweetAlert para mensajes de sesión (éxito y errores)
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if ($errors->any())
                const errores = @json($errors->all());
                let htmlErrors = '<ul>';
                errores.forEach(error => {
                    htmlErrors += error;
                });
                htmlErrors += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    html: htmlErrors,
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
</script>
@endsection
