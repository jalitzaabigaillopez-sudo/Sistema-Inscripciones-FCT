@extends('app')

@section('tituloArriba')
    Administrar Categorías
@endsection

@section('breadcrumb-title', 'Lista de Categorías')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de Categorías</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal"
                data-bs-target="#modalCategoria">
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
                                    <td>{{ $categoria->division->division }}</td>
                                    <td>{{ $categoria->sexo }}</td>
                                    <td>{{ $categoria->peso_min }}</td>
                                    <td>{{ $categoria->peso_max }}</td>
                                    <td class="text-center">
                                        <a href="#"
                                            class="btn btn-sm btn-warning me-1 rounded-pill btn-edit-categoria"
                                            data-id="{{ $categoria->id_categoria }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('categorias.destroy', $categoria) }}" method="POST"
                                            id="form-eliminar-{{ $categoria->id_categoria }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                data-bs-toggle="tooltip" title="Eliminar Modalidad"
                                                onclick="confirmarEliminacion({{ $categoria->id_categoria }})">
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
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form id="formCrearCategoria" method="POST" action="{{ route('categorias.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="division" class="form-label">División <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="division" name="id_division" required>
                                    <option value="" disabled selected>Seleccione...</option>
                                    @foreach ($divisiones as $division)
                                        <option value="{{ $division->id_division }}">{{ $division->division }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="sexo" name="sexo" required>
                                    <option value="" disabled selected>Seleccione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="pesoMinimo" class="form-label">Peso Mínimo <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="peso_min" name="peso_min"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="pesoMaximo" class="form-label">Peso Máximo <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="peso_max" name="peso_max"
                                    required>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar Categoría</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal EDITAR --}}
        <div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-labelledby="modalEditarCategoriaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3"
                            id="modalEditarCategoriaLabel">
                            Editar Categoría
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" id="formEditarCategoria">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="editDivision" class="form-label">División <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="editDivision" name="id_division" required>
                                    <option value="" disabled selected>Seleccione la división...</option>
                                    @foreach ($divisiones as $division)
                                        <option value="{{ $division->id_division }}">{{ $division->division }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editSexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="editSexo" name="sexo" required>
                                    <option value="" disabled selected>Seleccione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editPesoMinimo" class="form-label">Peso Mínimo <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editPesoMinimo"
                                    name="peso_min" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPesoMaximo" class="form-label">Peso Máximo <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editPesoMaximo"
                                    name="peso_max" required>
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


    {{-- Script para editar y eliminar --}}
    <script>
        // Espera a que el DOM (el documento HTML) esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {

            // Selecciona el formulario usando su ID
            const formCrearCategoria = document.getElementById('formCrearCategoria');
            const modalCategoria = document.getElementById('modalCategoria');

            // Verifica que ambos elementos existen antes de añadir el 'listener'
            if (formCrearCategoria && modalCategoria) {

                // Añade un 'listener' para el evento 'submit' del formulario
                formCrearCategoria.addEventListener('submit', function(event) {
                    // Detiene el envío del formulario tradicional para manejarlo con AJAX
                    event.preventDefault();

                    // Deshabilita el botón de enviar para evitar múltiples clics
                    const submitBtn = formCrearCategoria.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;

                    // Prepara los datos del formulario
                    const formData = new FormData(formCrearCategoria);

                    // Realiza la petición AJAX con fetch
                    fetch(formCrearCategoria.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => {
                            // El servidor respondió, ahora procesamos la respuesta
                            // Si la respuesta no es OK (por ejemplo, 409, 422, 500), lanzamos un error
                            if (!response.ok) {
                                return response.json().then(errorData => {
                                    // Concatenamos los mensajes de error para una mejor presentación
                                    let errorMessage = 'Error al procesar la solicitud.';
                                    if (errorData.error) {
                                        errorMessage = errorData.error;
                                    } else if (errorData.errors) {
                                        errorMessage = Object.values(errorData.errors).flat()
                                            .join('<br>');
                                    }
                                    throw new Error(errorMessage);
                                });
                            }
                            // Si la respuesta es OK, devolvemos los datos JSON
                            return response.json();
                        })
                        .then(data => {
                            // Si la promesa se resuelve correctamente (éxito)
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                html: data.success,
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                // Oculta el modal de manera segura
                                const modal = bootstrap.Modal.getInstance(modalCategoria);
                                if (modal) {
                                    modal.hide();
                                }
                                // Recarga la página para mostrar la nueva categoría
                                location.reload();
                            });
                        })
                        .catch(error => {
                            // Si la promesa falla (error)
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: error.message,
                            });
                        })
                        .finally(() => {
                            // Habilita el botón de nuevo, sin importar el resultado
                            submitBtn.disabled = false;
                        });
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Cuando se hace clic en editar
            $(document).on('click', '.btn-edit-categoria', function(e) {
                e.preventDefault();

                let id = $(this).data('id');
                console.log("Editar categoría ID:", id);

                $.get('/categorias/' + id + '/edit', function(data) {
                    console.log("Datos recibidos:", data);

                    // Llenar el formulario con los datos recibidos
                    $('#editDivision').val(data.id_division);
                    $('#editSexo').val(data.sexo);
                    $('#editPesoMinimo').val(data.peso_min);
                    $('#editPesoMaximo').val(data.peso_max);

                    // Cambiar la acción del formulario
                    $('#formEditarCategoria').attr('action', '/categorias/' + id);

                    // Mostrar el modal
                    let modal = new bootstrap.Modal(document.getElementById(
                        'modalEditarCategoria'));
                    modal.show();
                });
            });

            // Enviar el formulario de edición por AJAX
            $('#formEditarCategoria').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let actionUrl = form.attr('action');
                let formData = new FormData(this);


                let pesoMin = parseFloat($('#editPesoMinimo').val());
                let pesoMax = parseFloat($('#editPesoMaximo').val());
                if (pesoMin >= pesoMax) {
                    e.preventDefault();
                    alert('El peso mínimo debe ser menor que el peso máximo.');
                    return false;
                }

                $.ajax({
                    url: actionUrl,
                    type: 'POST', // Laravel espera POST con _method=PUT
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message ||
                                'Categoría actualizada correctamente.',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#modalEditarCategoria').modal('hide');
                            form[0].reset();
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error al actualizar la categoría.';
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
                            confirmButtonText: 'Aceptar'
                        });
                    }
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
