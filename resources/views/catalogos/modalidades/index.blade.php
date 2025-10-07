@extends('app')

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
                                <th class="text-center"></th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

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
                        <form id="formCrearModalidad" method="POST" action="{{ route('modalidades.store') }}">
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

                            <div class="mb-3">
                                <label for="subModalidades" class="form-label fw-semibold text-success">
                                    <i class="bi bi-layers me-2"></i> Submodalidades
                                </label>

                                <div class="p-3 border rounded-3 bg-white shadow-sm"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @forelse ($submodalidades as $sub)
                                        <div class="form-check form-check-sm mb-2">
                                            <input class="form-check-input" type="checkbox" name="submodalidades[]"
                                                value="{{ $sub->id_subModalidad }}" id="sub_{{ $sub->id_subModalidad }}">
                                            <label class="form-check-label" for="sub_{{ $sub->id_subModalidad }}">
                                                {{ $sub->nombre }}
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-muted fst-italic">No hay submodalidades disponibles.</div>
                                    @endforelse
                                </div>

                                <div class="form-text mt-1">
                                    <i class="bi bi-info-circle text-primary me-1"></i>
                                    Selecciona una o más submodalidades según corresponda.
                                </div>
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

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-success">
                                    <i class="bi bi-layers me-2"></i> Submodalidades
                                </label>

                                <div class="p-3 border rounded-3 bg-white shadow-sm"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($submodalidades as $sub)
                                        <div class="form-check form-check-sm mb-2">
                                            <input class="form-check-input sub-check" type="checkbox"
                                                name="submodalidades[]" value="{{ $sub->id_subModalidad }}"
                                                id="edit_sub_{{ $sub->id_subModalidad }}">
                                            <label class="form-check-label" for="edit_sub_{{ $sub->id_subModalidad }}">
                                                {{ $sub->nombre }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-text mt-1">
                                    <i class="bi bi-info-circle text-primary me-1"></i>
                                    Marca las submodalidades que pertenecen a esta modalidad.
                                </div>
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
    <script src="{{ asset('js/datatable.js') }}"></script>


    @section('scripts') ...

        <script>
            $(document).ready(function() {
                let columnsConfig = [{
                        data: "nombre",
                        title: "Nombre"
                    },
                    {
                        data: "descripcion",
                        title: "Descripción"
                    },
                    {
                        data: "submodalidades",
                        title: "Submodalidades",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "acciones",
                        title: "Acciones",
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            let id_modalidad = data;
                            return `
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit"
                            title="Editar" data-id="${id_modalidad}">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form action="/modalidades/${id_modalidad}" method="POST"
                            id="form-eliminar-${id_modalidad}" class="d-inline">
                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                data-bs-toggle="tooltip" title="Eliminar Modalidad"
                                onclick="confirmarEliminacion(${id_modalidad}, 'modalidades')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                `;
                        }
                    }
                ];

                initDataTable({
                    ajaxUrl: "{{ route('modalidades.index') }}",
                    columns: columnsConfig
                });
            });
        </script>
    @endsection

    <script>
        // CREAR
        document.addEventListener('DOMContentLoaded', function() {
            // Interceptar el envío del formulario de creación
            $('#formCrearModalidad').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Modalidad registrada!',
                            text: 'La modalidad se creó correctamente.',
                            confirmButtonColor: '#198754',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Error de validación (nombre duplicado u otro)
                            let errores = xhr.responseJSON.errors;

                            if (errores && errores.nombre) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al registrar',
                                    text: errores.nombre[0],
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Aceptar'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ocurrió un error inesperado. Intenta de nuevo.',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error del servidor',
                                text: 'No se pudo registrar la modalidad.',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    }
                });
            });
        });

        // EDITAR
        document.addEventListener('DOMContentLoaded', function() {
            // Script para editar
            $(document).on('click', '.btn-edit', function(e) {
                e.preventDefault();
                let modalidadId = $(this).data('id');
                console.log('Click en editar, ID:', modalidadId);

                $.get('/modalidades/' + modalidadId + '/datos', function(data) {
                    console.log('Datos recibidos:', data);

                    // Cargar nombre y descripción
                    $('#editNombreModalidad').val(data.nombre);
                    $('#editDescripcionModalidad').val(data.descripcion);

                    // Asignar acción al form
                    $('#formEditarModalidad').attr('action', '/modalidades/' + data.id_modalidad);

                    // Desmarcar todos los checkboxes primero
                    $('.sub-check').prop('checked', false);

                    // Marcar solo los que tiene asignados
                    if (data.sub_modalidades && data.sub_modalidades.length > 0) {
                        data.sub_modalidades.forEach(sub => {
                            $('#edit_sub_' + sub.id_subModalidad).prop('checked', true);
                        });
                    }

                    // Mostrar modal
                    new bootstrap.Modal(document.getElementById('modalEditarModalidad')).show();
                });
            });

            $('#formEditarModalidad').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Modalidad actualizada!',
                            text: 'Los cambios se guardaron correctamente.',
                            confirmButtonColor: '#198754',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Laravel envía errores de validación con status 422
                            let errores = xhr.responseJSON.errors;
                            console.log('Errores:', errores);

                            if (errores && errores.nombre) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de validación',
                                    text: errores.nombre[0],
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Aceptar'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error inesperado',
                                    text: 'Ocurrió un error al actualizar la modalidad.',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error del servidor',
                                text: 'No se pudo actualizar la modalidad.',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    }
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
                            url: '/modalidades/' + id,
                            method: 'POST', // Usamos POST y simulamos DELETE
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: 'La modalidad ha sido eliminada correctamente.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Aceptar',
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un error al intentar eliminar la modalidad.',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Aceptar',
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
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: "Aceptar",
                    // timer: 1000
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
                    confirmButtonColor: '#3085d6'
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
    </script>
@endsection
