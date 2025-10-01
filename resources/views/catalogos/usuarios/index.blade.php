@extends('app')

@section('tituloArriba')
    Administrar Usuarios
@endsection

@section('breadcrumb-title', 'Lista de Usuarios')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <div class="container py-4">

        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Lista de Usuarios</h4>
            <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal"
                data-bs-target="#modalUsuario">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
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
                                <th class="text-center">Identificación</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Correo</th>
                                {{-- <th>Contraseña</th> --}}
                                {{-- <th class="text-center">Imagen</th> --}}
                                <th class="text-center">Rol</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal CREAR USUARIO --}}
        <div class="modal fade modal-crear" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalUsuarioLabel">Registrar
                            Nuevo Usuario</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form id="formCrearUsuario">
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información Personal</h6>
                                    <div class="mb-3">
                                        <label for="identificacionUsuario" class="form-label">Identificación <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="identificacionUsuario" name="identificacion" placeholder="Ej. 123456789"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombreUsuario" class="form-label">Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="nombreUsuario"
                                            name="nombre_completo" placeholder="Ej. Juan Pérez" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="correoUsuario" class="form-label">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm" id="correoUsuario"
                                            autocomplete="new-email" name="email" placeholder="Ej. correo@email.com"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contrasenaUsuario" class="form-label">Contraseña <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control" id="contrasenaUsuario"
                                                autocomplete="new-password" name="password"
                                                placeholder="Mínimo 8 caracteres" required>
                                            <button class="btn btn-outline-primary toggle-password" type="button"
                                                data-target="#contrasenaUsuario">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmarContrasena" class="form-label">Confirmar Contraseña <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control" id="confirmarContrasena"
                                                name="password_confirmation" placeholder="Repetir la contraseña" required>
                                            <button class="btn btn-outline-primary toggle-password" type="button"
                                                data-target="#confirmarContrasena">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Rol y Foto de Perfil</h6>
                                    <div class="mb-3">
                                        <label for="rolUsuario" class="form-label">Rol <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="rolUsuario" name="rol"
                                            required>
                                            <option value="" selected disabled>Seleccione el rol</option>
                                            <option value="administrador">Administrador</option>
                                            <option value="academia">Academia</option>
                                            <option value="arbitro">Árbitro</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fotoUsuario" class="form-label">Foto de Perfil</label>
                                        <input class="form-control form-control-sm fotoUsuarioInput" type="file"
                                            id="fotoUsuarioCrear" name="imagen" accept="image/*">
                                    </div>
                                    <div class="mb-3 d-flex flex-column align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                            style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                            <span class="previewText text-muted">Sin foto</span>
                                            <img class="previewImage img-thumbnail rounded-circle" src=""
                                                alt="Vista previa"
                                                style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger removeImageBtn"
                                            style="display: none;"> <i class="bi bi-trash"></i> Eliminar Foto</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill">Guardar Usuario</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal EDITAR USUARIO --}}
        <div class="modal fade modal-editar" id="modalEditarUsuario" tabindex="-1"
            aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarUsuarioLabel">
                            Actualizar Datos de Usuario</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form id="formEditarUsuario">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id_usuario" id="idUsuarioEditar">
                            <input type="hidden" name="remove_imagen" id="removeImagen" value="0">
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información Personal</h6>
                                    <div class="mb-3">
                                        <label for="identificacionUsuarioEditar" class="form-label">Identificación <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="identificacionUsuarioEditar" name="identificacion"
                                            placeholder="Ej. 123456789" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombreUsuarioEditar" class="form-label">Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="nombreUsuarioEditar" name="nombre_completo" placeholder="Ej. Juan Pérez"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="correoUsuarioEditar" class="form-label">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm"
                                            id="correoUsuarioEditar" name="email" placeholder="Ej. correo@email.com"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contrasenaUsuarioEditar" class="form-label">Contraseña</label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control form-control-sm"
                                                id="contrasenaUsuarioEditar" name="password"
                                                placeholder="Mínimo 8 caracteres">
                                            <button class="btn btn-outline-primary toggle-password" type="button"
                                                data-target="#contrasenaUsuarioEditar">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmarContrasenaEditar" class="form-label">Confirmar
                                            Contraseña</label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control form-control-sm"
                                                id="confirmarContrasenaEditar" name="password_confirmation"
                                                placeholder="Repetir la contraseña">
                                            <button class="btn btn-outline-primary toggle-password" type="button"
                                                data-target="#confirmarContrasenaEditar">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Rol y Foto de Perfil</h6>
                                    <div class="mb-3">
                                        <label for="rolUsuarioEditar" class="form-label">Rol <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="rolUsuarioEditar" name="rol"
                                            required>
                                            <option value="" selected disabled>Seleccione el rol</option>
                                            <option value="administrador">Administrador</option>
                                            <option value="academia">Academia</option>
                                            <option value="arbitro">Árbitro</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fotoUsuarioEditar" class="form-label">Foto de Perfil</label>
                                        <input class="form-control form-control-sm fotoUsuarioInput" type="file"
                                            id="fotoUsuarioEditar" name="imagen" accept="image/*">
                                    </div>
                                    <div class="mb-3 d-flex flex-column align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                            style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                            <span class="previewText text-muted">Sin foto</span>
                                            <img class="previewImage img-thumbnail rounded-circle" src=""
                                                alt="Vista previa"
                                                style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger removeImageBtn"
                                            style="display: none;"> <i class="bi bi-trash"></i> Eliminar Foto</button>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label d-block">Estado <span
                                                class="text-danger">*</span></label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="estado"
                                                id="e_estado_activo" value="activo" required>
                                            <label class="form-check-label" for="e_estado_activo">Activo</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="estado"
                                                id="e_estado_inactivo" value="inactivo" required>
                                            <label class="form-check-label" for="e_estado_inactivo">Inactivo</label>
                                        </div>
                                    </div>
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
        $(document).ready(function() {
            let columnsConfig = [{
                    data: "identificacion",
                    title: "ID"
                },
                {
                    data: "nombre_completo",
                    title: "Nombre" // Coincide con 'nombre_completo'
                },
                {
                    data: "email",
                    title: "Correo"
                },
                {
                    data: "rol",
                    title: "Rol"
                },
                {
                    data: "estado",
                    title: "Estado"
                },
                {
                    data: "acciones",
                    title: "Acciones",
                    orderable: false, // Las acciones no se ordenan
                    render: function(data, type, row) {
                        // 'data' es el ID del usuario
                        return `
                            <a href="#" 
                                   class="btn btn-sm btn-warning rounded-pill btn-edit" 
                                   title="Editar"
                                   data-id="${row.id_usuario}" 
                                    data-usuario='${JSON.stringify(row)}'
                                   data-bs-toggle="modal" 
                                   data-bs-target="#modalEditarUsuario">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="/usuarios/${data}" method="POST" id="form-eliminar-${data}" class="d-inline">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="button" 
                                            class="btn btn-sm btn-danger rounded-pill"
                                            data-bs-toggle="tooltip" 
                                            title="Eliminar Usuario"
                                            onclick="confirmarEliminacion(${data})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                        `;
                    }
                }
            ];

            // Pintar headers dinámicamente
            let headersRow = $('#tabla-headers');
            headersRow.empty();
            columnsConfig.forEach(col => {
                headersRow.append(`<th class="text-center">${col.title}</th>`);
            });

            // Inicializar DataTable con tu script genérico
            initDataTable({
                ajaxUrl: "{{ route('usuarios.index') }}",
                columns: columnsConfig
            });
        });
    </script>
@endsection

<script>
    // CREAR
    document.addEventListener("DOMContentLoaded", function() {
        const crearModal = document.getElementById("modalUsuario");
        if (crearModal) {
            setupImagePreview(crearModal);
        }

        const formCrearUsuario = document.getElementById("formCrearUsuario");
        const guardarBtn = document.querySelector("#modalUsuario .btn-success");

        if (formCrearUsuario && guardarBtn) {
            guardarBtn.addEventListener("click", function(e) {
                e.preventDefault();

                const formData = new FormData(formCrearUsuario);
                console.log([...formData]); // Registra datos para depuración

                $.ajax({
                    url: "{{ route('usuarios.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                $('#modalUsuario').modal('hide');
                                formCrearUsuario.reset();
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Ocurrió un error al registrar el usuario.';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors || {};
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } else if (xhr.status === 500) {
                            errorMessage = xhr.responseJSON?.error ||
                                'Error interno del servidor.';
                        }
                        Swal.fire({
                            title: 'Error',
                            html: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        }

        function setupImagePreview(modalElement) {
            const inputFile = modalElement.querySelector(".fotoUsuarioInput");
            const previewImage = modalElement.querySelector(".previewImage");
            const previewText = modalElement.querySelector(".previewText");
            const removeBtn = modalElement.querySelector(".removeImageBtn");

            if (inputFile && previewImage && previewText && removeBtn) {
                inputFile.addEventListener("change", function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewImage.style.display = "block";
                            previewText.style.display = "none";
                            removeBtn.style.display = "inline-block";
                        };
                        reader.readAsDataURL(file);
                    }
                });

                removeBtn.addEventListener("click", function() {
                    previewImage.src = "";
                    previewImage.style.display = "none";
                    previewText.style.display = "block";
                    removeBtn.style.display = "none";
                    inputFile.value = "";
                });
            }
        }
    });

    // EDITAR
    document.addEventListener("DOMContentLoaded", function() {
        const editarModal = document.getElementById("modalEditarUsuario");
        if (editarModal) {
            setupImagePreview(editarModal);
        }

        const formEditarUsuario = document.getElementById("formEditarUsuario");
        if (formEditarUsuario) {
            formEditarUsuario.addEventListener("submit", function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const idUsuario = document.getElementById("idUsuarioEditar").value;

                // Excluir password y password_confirmation si están vacíos
                const password = formEditarUsuario.querySelector('#contrasenaUsuarioEditar').value;
                const passwordConfirmation = formEditarUsuario.querySelector(
                    '#confirmarContrasenaEditar').value;
                if (!password && !passwordConfirmation) {
                    formData.delete('password');
                    formData.delete('password_confirmation');
                }

                console.log('Datos enviados:', [...formData]);

                $.ajax({
                    url: `/usuarios/${idUsuario}`,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                $('#modalEditarUsuario').modal('hide');
                                formEditarUsuario.reset();
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error al actualizar el usuario.';
                        if (xhr.status === 422) {
                            errorMessage = Object.values(xhr.responseJSON?.errors || {})
                                .flat().join('<br>');
                        } else if (xhr.status === 500) {
                            errorMessage = xhr.responseJSON?.error || 'Error interno.';
                        }
                        Swal.fire({
                            title: 'Error',
                            html: errorMessage,
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        }

        $('#modalEditarUsuario').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const usuario = button.data('usuario');
            const modal = $(this);
            modal.find('#idUsuarioEditar').val(usuario.id_usuario);
            modal.find('#identificacionUsuarioEditar').val(usuario.identificacion);
            modal.find('#nombreUsuarioEditar').val(usuario.nombre_completo);
            modal.find('#correoUsuarioEditar').val(usuario.email);
            modal.find('#rolUsuarioEditar').val(usuario.rol);
            modal.find('input[name="estado"][value="' + usuario.estado + '"]').prop('checked', true);
            modal.find('#contrasenaUsuarioEditar').val('');
            modal.find('#confirmarContrasenaEditar').val('');
            modal.find('#removeImagen').val('0');

            const previewImage = modal.find('.previewImage');
            const previewText = modal.find('.previewText');
            const removeBtn = modal.find('.removeImageBtn');
            const inputFile = modal.find('#fotoUsuarioEditar');

            previewText.text('Sin foto'); // Forzar "Sin foto" por defecto
            if (usuario.imagen && usuario.imagen !== '') {
                previewImage.attr('src', '/storage/' + usuario.imagen).css('display', 'block');
                previewText.css('display', 'none');
                removeBtn.css('display', 'inline-block');
            } else {
                previewImage.attr('src', '').css('display', 'none');
                previewText.css('display', 'block');
                removeBtn.css('display', 'none');
            }
            inputFile.val('');
        });

        function setupImagePreview(modalElement) {
            const inputFile = modalElement.querySelector(".fotoUsuarioInput");
            const previewImage = modalElement.querySelector(".previewImage");
            const previewText = modalElement.querySelector(".previewText");
            const removeBtn = modalElement.querySelector(".removeImageBtn");
            const removeImagenInput = modalElement.querySelector("#removeImagen");

            if (inputFile && previewImage && previewText && removeBtn && removeImagenInput) {
                inputFile.addEventListener("change", function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewImage.style.display = "block";
                            previewText.style.display = "none";
                            removeBtn.style.display = "inline-block";
                            removeImagenInput.value = "0";
                        };
                        reader.readAsDataURL(file);
                    }
                });

                removeBtn.addEventListener("click", function() {
                    previewImage.src = "";
                    previewImage.style.display = "none";
                    previewText.style.display = "block";
                    removeBtn.style.display = "none";
                    inputFile.value = "";
                    removeImagenInput.value = "1";
                });
            }
        }
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
                // Envía el formulario usando AJAX para manejar la respuesta
                $.ajax({
                    url: $('#form-eliminar-' + id).attr('action'),
                    method: $('#form-eliminar-' + id).attr('method'),
                    data: $('#form-eliminar-' + id).serialize(),
                    success: function(response) {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'El registro ha sido eliminado correctamente.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            // Recarga la página o actualiza la tabla
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al intentar eliminar el registro.',
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
