@extends('app')

@section('tituloArriba')
    Administrar Tipos de Eventos
@endsection

@section('breadcrumb-title', 'Lista de Tipos de Eventos')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de Tipos de Eventos</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal"
                data-bs-target="#modalTipoEvento">
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
                            {{-- @foreach ($data as $tipoEvento)
                            <tr class="text-center">
                                <td class="small">{{ $tipoEvento->nombre }}</td>
                                <td class="small">{{ $tipoEvento->descripcion }}</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill btn-edit"
                                        data-id="{{ $tipoEvento->id_tipo_evento }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('tiposEventos.destroy', $tipoEvento) }}" method="POST"
                                        id="form-eliminar-{{ $tipoEvento->id_tipo_evento }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                            onclick="confirmarEliminacion({{ $tipoEvento->id_tipo_evento }})">
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
        <div class="modal fade" id="modalTipoEvento" tabindex="-1" aria-labelledby="modalTipoEventoLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3">Crear Nuevo Tipo de Evento</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form id="formCrearTipoEvento" method="POST" action="{{ route('tiposEventos.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="nombreTipoEvento" class="form-label">Nombre <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="nombreTipoEvento" name="nombre"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcionTipoEvento" class="form-label">Descripción</label>
                                <textarea class="form-control form-control-sm" id="descripcionTipoEvento" name="descripcion"
                                    rows="3"></textarea>
                            </div>

                            <input type="hidden" name="modo" value="1">
                            <div class="mb-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="modo" value="0" {{ old('modo') === '0' ? 'checked' : '' }}>
                                        ¿Este evento es un curso o seminario?
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar Tipo de Evento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal EDITAR --}}
        <div class="modal fade" id="modalEditarTipoEvento" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3">Editar Tipo de Evento</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form id="formEditarTipoEvento" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="editNombreTipoEvento" class="form-label">Nombre <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editNombreTipoEvento"
                                    name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDescripcionTipoEvento" class="form-label">Descripción</label>
                                <textarea class="form-control form-control-sm" id="editDescripcionTipoEvento"
                                    name="descripcion" rows="3"></textarea>
                            </div>

                            <input type="hidden" name="modo" value="1">
                            <div class="mb-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="modo" value="0" {{ old('modo') === '0' ? 'checked' : '' }}>
                                        ¿Este evento es un curso o seminario?
                                    </label>
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

    @section('scripts')

        <script>
            $(document).ready(function () {
                let columnsConfig = [{
                    data: 'nombre',
                    title: 'Nombre'
                },
                {
                    data: 'descripcion',
                    title: 'Descripción'
                },
                {
                    data: 'acciones',
                    title: 'Acciones',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        return `
                                                <div class="d-flex justify-content-center">
                                                    <button class="btn btn-warning btn-sm me-1 rounded-pill btn-edit" data-id="${data}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm rounded-pill btn-delete" data-id="${data}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>`;
                    }
                }
                ];

                // INICIALIZAR DATATABLE USANDO TU HELPER
                const tabla = initDataTable({
                    ajaxUrl: "{{ route('tiposEventos.index') }}",
                    columns: columnsConfig,
                    tableId: "tabla"
                });

                // Crear
                $('#formCrearTipoEvento').on('submit', function (e) {
                    e.preventDefault();
                    const form = $(this);
                    $.post(form.attr('action'), form.serialize())
                        .done(function (res) {
                            $('#modalTipoEvento').modal('hide');
                            form.trigger('reset');
                            tabla.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Tipo de evento creado correctamente.',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            });
                        })
                        .fail(function (xhr) {
                            const errors = xhr.responseJSON.errors;
                            const html = Object.values(errors).flat().map(msg => `<div>${msg}</div>`).join(
                                '');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de validación',
                                html: html,
                                confirmButtonColor: '#3085d6'
                            });
                        });
                });

                // Editar (abrir modal)
               $(document).on('click', '.btn-edit', function () {
                const id = $(this).data('id');

                $.get(`/tiposEventos/${id}/datos`, function (data) {
                    $('#editNombreTipoEvento').val(data.nombre);
                    $('#editDescripcionTipoEvento').val(data.descripcion);
                    $('#formEditarTipoEvento').attr('action', `/tiposEventos/${id}`);

                    const checkboxModo = $('#formEditarTipoEvento input[type="checkbox"][name="modo"]');
                    checkboxModo.prop('checked', Number(data.modo) === 0);

                    new bootstrap.Modal('#modalEditarTipoEvento').show();
                });
            });

            $('#modalEditarTipoEvento').on('hidden.bs.modal', function () {
                const form = $('#formEditarTipoEvento');

                form[0].reset();
                form.attr('action', '');

                // asegurar estado inicial correcto
                form.find('input[type="checkbox"][name="modo"]').prop('checked', false);
            });
            
                // Guardar edición
                $('#formEditarTipoEvento').on('submit', function (e) {
                    e.preventDefault();
                    const form = $(this);
                    const url = form.attr('action');
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: form.serialize(),
                        success: function (res) {
                            $('#modalEditarTipoEvento').modal('hide');
                            tabla.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Tipo de evento actualizado correctamente.',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            });
                        },
                        error: function (xhr) {
                            const errors = xhr.responseJSON.errors;
                            const html = Object.values(errors).flat().map(msg =>
                                `<div>${msg}</div>`).join('');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de validación',
                                html: html,
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                });

                // Eliminar
                $(document).on('click', '.btn-delete', function () {
                    const id = $(this).data('id');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then(result => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/tiposEventos/${id}`,
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    _method: 'DELETE'
                                },
                                success: function (res) {
                                    tabla.ajax.reload(null, false);
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Eliminado!',
                                        text: 'Tipo de evento eliminado correctamente.',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>

    @endsection

@endsection