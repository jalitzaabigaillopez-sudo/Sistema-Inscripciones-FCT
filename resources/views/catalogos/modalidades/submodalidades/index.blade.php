@extends('app')

@section('breadcrumb-title', 'Lista de SubModalidades')


@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de SubModalidades</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal"
                data-bs-target="#modalCrearSubModalidad">
                <i class="bi bi-plus-circle me-1"></i> Nueva Submodalidad
            </button>
        </div>
        <hr>

        <!-- Tabla -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="tabla" class="table table-striped table-hover table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">Cantidad de Atletas</th>
                                <th class="text-center">Sexo Mixto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================
        MODAL CREAR SUBMODALIDAD
        ========================================================== -->
    <div class="modal fade" id="modalCrearSubModalidad" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-success fw-bold w-100 text-center">
                        Registrar Nueva Submodalidad
                    </h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCrearSubModalidad" method="POST" action="{{ route('submodalidades.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control form-control-sm" name="descripcion" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad de Atletas <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" name="cantidad_atletas" required
                                min="1">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="sexo_mixto" value="1"
                                id="sexoMixtoCrear" checked>
                            <label class="form-check-label" for="sexoMixtoCrear">Sexo Mixto</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==========================================================
        MODAL EDITAR SUBMODALIDAD
    ========================================================== -->
    <div class="modal fade" id="modalEditarSubModalidad" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-primary fw-bold w-100 text-center">Editar Submodalidad</h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarSubModalidad" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editNombre" name="nombre"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control form-control-sm" id="editDescripcion" name="descripcion" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad de Atletas</label>
                            <input type="number" class="form-control form-control-sm" id="editCantidad"
                                name="cantidad_atletas" required min="1">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="editSexoMixto" name="sexo_mixto"
                                value="1">
                            <label class="form-check-label" for="editSexoMixto">Sexo Mixto</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('js/datatable.js') }}"></script>


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            // Inicializar tabla
            let table = initDataTable({
                ajaxUrl: "{{ route('submodalidades.index') }}",
                columns: [{
                        data: "nombre"
                    },
                    {
                        data: "descripcion"
                    },
                    {
                        data: "cantidad_atletas"
                    },
                    {
                        data: "sexo_mixto",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "acciones",
                        orderable: false,
                        searchable: false,
                        render: function(id) {
                            return `
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-warning btn-sm me-1 rounded-pill btn-edit" data-id="${id}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-danger btn-sm rounded-pill btn-delete" data-id="${id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>`;
                        }
                    }
                ]
            });

            // ==============================
            // CREAR SUBMODALIDAD
            // ==============================
            $('#formCrearSubModalidad').on('submit', function(e) {
                e.preventDefault();

                $.post($(this).attr('action'), $(this).serialize())
                    .done(() => {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Creada correctamente!',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'

                        }).then(() => {
                            // Cerrar modal después del SweetAlert
                            $('#modalCrearSubModalidad').modal('hide');
                        });

                        // RECARGAR TABLA
                        table.ajax.reload();
                    })
                    .fail(xhr => {
                        if (xhr.status === 422) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.errors.nombre[0]
                            });
                        }
                    });
            });

            // ==============================
            // LIMPIAR FORMULARIO AL CERRAR / ABRIR MODAL
            // ==============================
            $('#modalCrearSubModalidad').on('hidden.bs.modal show.bs.modal', function() {
                const form = $('#formCrearSubModalidad');
                form[0].reset(); // Limpia todos los campos del formulario
                form.find('input[type="checkbox"]').prop('checked',
                    true); // Restaura el checkbox por defecto
            });

            // ==============================
            // EDITAR - CARGAR DATOS
            // ==============================
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get(`/submodalidades/${id}/edit`, function(data) {
                    $('#editNombre').val(data.nombre);
                    $('#editDescripcion').val(data.descripcion);
                    $('#editCantidad').val(data.cantidad_atletas);
                    $('#editSexoMixto').prop('checked', data.sexo_mixto == 1);
                    $('#formEditarSubModalidad').attr('action', `/submodalidades/${id}`);
                    $('#modalEditarSubModalidad').modal('show');
                });
            });

            // ==============================
            // GUARDAR EDICIÓN (PUT VIA POST)
            // ==============================
            $('#formEditarSubModalidad').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serializeArray();
                formData.push({
                    name: '_method',
                    value: 'PUT'
                });

                // ✅ Forzar envío del valor del checkbox aunque esté desmarcado
                const isChecked = $('#editSexoMixto').is(':checked');
                formData.push({
                    name: 'sexo_mixto',
                    value: isChecked ? 1 : 0
                });


                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $.param(formData),
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Actualizada correctamente!'
                        });
                        $('#modalEditarSubModalidad').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.errors.nombre[0]
                            });
                        }
                    }
                });
            });

            // ==============================
            // ELIMINAR SUBMODALIDAD
            // ==============================
            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: '¿Eliminar submodalidad?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: `/submodalidades/${id}`,
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminada correctamente'
                                });
                                table.ajax.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
