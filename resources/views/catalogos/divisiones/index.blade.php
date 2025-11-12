@extends('app')

@section('tituloArriba')
    Administrar Divisiones
@endsection

@section('breadcrumb-title', 'Lista de Divisiones')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de Divisiones</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal"
                data-bs-target="#modalDivision">
                <i class="bi bi-plus-circle me-1"></i> Nueva División
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
                                <th class="text-center">Año de Inicio</th>
                                <th class="text-center">Año Final</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($data as $division)
                                <tr class="text-center">
                                    <td class="small">{{ $division->division }}</td>
                                    <td class="small">{{ $division->year_inicio }}</td>
                                    <td class="small">{{ $division->year_final }}</td>
                                    <td class="text-center">

                                        <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit"
                                            title="Editar" data-id_division="{{ $division->id_division }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('divisiones.destroy', $division) }}" method="POST"
                                            id="form-eliminar-{{ $division->id_division }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                data-bs-toggle="tooltip" title="Eliminar división"
                                                onclick="confirmarEliminacion({{ $division->id_division }})"
                                                onclick="return confirm('¿Eliminar esta división?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal CREAR --}}
        <div class="modal fade" id="modalDivision" tabindex="-1" aria-labelledby="modalDivisionLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalDivisionLabel">
                            Crear Nueva División
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form id="formCrearDivision" method="POST" action="{{ route('divisiones.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="NombreDivision" class="form-label">División<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="NombreDivision"
                                    name="division" required>
                            </div>
                            <div class="mb-3">
                                <label for="Year_Inicio" class="form-label">Año de Inicio <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="Year_Inicio"
                                    name="year_inicio" required>
                            </div>
                            <div class="mb-3">
                                <label for="Year_Final" class="form-label">Año de Finalización <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="Year_Final" name="year_final"
                                    required>
                            </div>

                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar División</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal EDITAR --}}
        <div class="modal fade" id="modalEditarDivision" tabindex="-1" aria-labelledby="modalEditarDivisionLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarDivisionLabel">
                            Editar División
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="" id="formEditarDivision">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="editNombreDivision" class="form-label">División<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editNombreDivision"
                                    name="division" required>
                            </div>
                            <div class="mb-3">
                                <label for="editYearInicio" class="form-label">Año de Inicio <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editYearInicio"
                                    name="year_inicio" required>
                            </div>
                            <div class="mb-3">
                                <label for="editYearFinal" class="form-label">Año de Finalización <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editYearFinal"
                                    name="year_final" required>
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


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/datatable.js') }}"></script>


    @section('scripts')
        <script>
            $(document).ready(function() {
                // ===========================================================
                // CONFIGURACIÓN DE COLUMNAS PARA DATATABLE
                // ===========================================================
                let columnsConfig = [{
                        data: "division",
                        title: "División"
                    },
                    {
                        data: "year_inicio",
                        title: "Año de Inicio"
                    },
                    {
                        data: "year_final",
                        title: "Año Final"
                    },
                    {
                        data: "acciones",
                        title: "Acciones",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let id = data;
                            return `
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit" 
                            title="Editar" data-id="${id}">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="/divisiones/${id}" method="POST" id="form-eliminar-${id}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                title="Eliminar división"
                                onclick="confirmarEliminacion(${id}, 'divisiones')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>`;
                        }
                    }
                ];

                // ===========================================================
                // INICIALIZAR DATATABLE (usa tu helper genérico)
                // ===========================================================
                initDataTable({
                    ajaxUrl: "{{ route('divisiones.index') }}",
                    columns: columnsConfig,
                    tableId: "tabla-divisiones"
                });

                // ===========================================================
                // EVENTO PARA EDITAR REGISTRO (AJAX)
                // ===========================================================
                $(document).on('click', '.btn-edit', function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');

                    $.get(`/divisiones/${id}/datos`, function(data) {
                        $('#editNombreDivision').val(data.division);
                        $('#editYearInicio').val(data.year_inicio);
                        $('#editYearFinal').val(data.year_final);

                        $('#formEditarDivision').attr('action', `/divisiones/${id}`);

                        let modal = new bootstrap.Modal(document.getElementById('modalEditarDivision'));
                        modal.show();
                    }).fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los datos de la división.',
                            confirmButtonColor: '#3085d6'
                        });
                    });
                });

                // ===========================================================
                // CONFIRMAR ELIMINACIÓN (SweetAlert2)
                // ===========================================================
                window.confirmarEliminacion = function(id, tipo) {
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
                                url: `/${tipo}/${id}`,
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    _method: 'DELETE'
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: '¡Eliminado!',
                                        text: 'El registro ha sido eliminado correctamente.',
                                        icon: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function() {
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Ocurrió un error al intentar eliminar el registro.',
                                        icon: 'error',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            });
                        }
                    });
                }


                // ===========================================================
                // FUNCIÓN GENERAL PARA GUARDAR (CREAR O EDITAR)
                // ===========================================================
                function guardarDivision(form, modalId, mensajeExito) {
                    const data = form.serialize();
                    const url = form.attr('action');

                    $.ajax({
                        url: url,
                        method: 'POST', // Laravel entenderá PUT si está el _method en el form
                        data: data,
                        success: function(response) {

                            // Cerrar modal y limpiar
                            $(modalId).modal('hide');
                            form.trigger('reset');

                            // Refrescar tabla inmediatamente
                            if ($.fn.DataTable.isDataTable('#tabla-divisiones')) {
                                $('#tabla-divisiones').DataTable().ajax.reload(null, false);
                            } else {
                                location.reload(); // fallback
                            }

                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: mensajeExito,
                                showConfirmButton: false,
                                timer: 1000
                            });


                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                const html = Object.values(errors)
                                    .flat()
                                    .map(msg => `<div class="mb-1">${msg}</div>`)
                                    .join('');

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de validación',
                                    html: html,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Aceptar'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error inesperado',
                                    text: 'Ocurrió un problema al procesar la división.',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        }
                    });
                }

                // ===========================================================
                // CREAR DIVISIÓN
                // ===========================================================
                $('#formCrearDivision').on('submit', function(e) {
                    e.preventDefault();
                    guardarDivision($(this), '#modalDivision', 'División creada correctamente.');
                });

                // ===========================================================
                // EDITAR DIVISIÓN
                // ===========================================================
                $('#formEditarDivision').on('submit', function(e) {
                    e.preventDefault();
                    guardarDivision($(this), '#modalEditarDivision', 'División actualizada correctamente.');
                });

            });
        </script>
    @endsection


@endsection
