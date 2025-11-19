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
                                <th class="text-center">Academia</th>
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
                                    <!-- Requisitos (CREAR) -->
                                    <div id="passwordRequirementsCrear" class="mt-2 text-muted small"
                                        style="display:none;">
                                        <p class="mb-1 fw-semibold text-dark">
                                            <i class="bi bi-shield-lock me-1 text-primary"></i> Requisitos de la
                                            contraseña:
                                        </p>
                                        <ul class="list-unstyled ms-3 mb-0">
                                            <li id="reqLengthCrear"><i class="bi bi-x-circle text-danger me-1"></i> Entre
                                                8 y 11 caracteres</li>
                                            <li id="reqUpperCrear"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                menos una letra mayúscula</li>
                                            <li id="reqLowerCrear"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                menos una letra minúscula</li>
                                            <li id="reqNumberCrear"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                menos un número</li>
                                            <li id="reqSpecialCrear"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                menos un carácter especial (!@#$%^&*_-.,;:?)</li>
                                            <li id="reqMatchCrear"><i class="bi bi-x-circle text-danger me-1"></i> Las
                                                contraseñas coinciden</li>
                                        </ul>
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
                                                placeholder="Mínimo 8 caracteres" autocomplete="new-password"
                                                autocapitalize="off" spellcheck="false">
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
                                                placeholder="Repetir la contraseña" autocomplete="new-password"
                                                autocapitalize="off" spellcheck="false">
                                            <button class="btn btn-outline-primary toggle-password" type="button"
                                                data-target="#confirmarContrasenaEditar">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Requisitos (EDITAR) -->
                                    <div id="passwordRequirementsEditar" class="mt-2 text-muted small"
                                        style="display:none;">
                                        <p class="mb-1 fw-semibold text-dark">
                                            <i class="bi bi-shield-lock me-1 text-primary"></i> Requisitos de la
                                            contraseña:
                                        </p>
                                        <ul class="list-unstyled ms-3 mb-0">
                                            <li id="reqLengthEditar"><i class="bi bi-x-circle text-danger me-1"></i> Entre
                                                8 y 11 caracteres</li>
                                            <li id="reqUpperEditar"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                menos una letra mayúscula</li>
                                            <li id="reqLowerEditar"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                menos una letra minúscula</li>
                                            <li id="reqNumberEditar"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                menos un número</li>
                                            <li id="reqSpecialEditar"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                menos un carácter especial (!@#$%^&*_-.,;:?)</li>
                                            <li id="reqMatchEditar"><i class="bi bi-x-circle text-danger me-1"></i> Las
                                                contraseñas coinciden</li>
                                        </ul>
                                    </div>

                                    <div class="d-grid gap-2 col-12 mx-auto">
                                        <button type="button" class="btn btn-link rounded-pill me-2"
                                            id="btnResetPassword">
                                            <i class="bi bi-arrow-repeat me-1"></i> Restablecer Contraseña
                                        </button>
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
                                        <label for="academiaUsuarioEditar" class="form-label">Academia</label>
                                        <input class="form-control form-control-sm academiaUsuarioInput" type="text"
                                            id="academiaUsuarioEditar" name="academia" readonly>
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
                    data: "academia",
                    title: "Academia asignada"
                },
                {
                    data: "rol",
                    title: "Rol"
                },
                {
                    data: "estado",
                    title: "Estado",
                    render: function(data) {
                        let badgeClass =
                            data === 'activo' ? 'success' :
                            data === 'inactivo' ? 'danger' :
                            'secondary';
                        return `<span class="badge bg-${badgeClass} rounded-pill text-capitalize">${data}</span>`;
                    }
                },
                {
                    data: "acciones",
                    title: "Acciones",
                    orderable: false,
                    render: function(data, type, row) {
                        // Si es el usuario actual, mostrar el botón gris
                        if (row.usuario_actual) {
                            return `
                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <button class="btn btn-sm btn-secondary rounded-pill d-flex align-items-center justify-content-center px-3" 
                            title="Usuario actual" disabled >
                        <i class="bi bi-person-lock"></i>
                    </button>
                </div>`;
                        }

                        // Si es otro usuario
                        return `
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <a href="#" 
                            class="btn btn-sm btn-warning rounded-pill d-flex align-items-center justify-content-center btn-edit"
                            data-id="${row.id_usuario}" 
                            data-usuario='${JSON.stringify(row)}'
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEditarUsuario"
                            title="Editar Usuario"
                            >
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button class="btn btn-sm btn-danger rounded-pill d-flex align-items-center justify-content-center"
                                title="Eliminar Usuario" 
                                onclick="confirmarEliminacion(${row.id_usuario})"
                            >
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>`;
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

        /* ===============================
         VALIDACIÓN DE CONTRASEÑA
        =============================== */
        const password = document.getElementById('contrasenaUsuario');
        const confirm = document.getElementById('confirmarContrasena');
        const panel = document.getElementById('passwordRequirementsCrear');

        if (password && confirm && panel) {
            const req = {
                length: document.getElementById('reqLengthCrear'),
                upper: document.getElementById('reqUpperCrear'),
                lower: document.getElementById('reqLowerCrear'),
                number: document.getElementById('reqNumberCrear'),
                special: document.getElementById('reqSpecialCrear'),
                match: document.getElementById('reqMatchCrear')
            };

            function setIcon(li, ok) {
                const i = li.querySelector('i');
                i.classList.toggle('bi-check-circle', ok);
                i.classList.toggle('text-success', ok);
                i.classList.toggle('bi-x-circle', !ok);
                i.classList.toggle('text-danger', !ok);
            }

            function update() {
                const p = password.value || '';
                const c = confirm.value || '';
                panel.style.display = (p || c) ? 'block' : 'none';
                setIcon(req.length, p.length >= 8 && p.length <= 11);
                setIcon(req.upper, /[A-Z]/.test(p));
                setIcon(req.lower, /[a-z]/.test(p));
                setIcon(req.number, /\d/.test(p));
                setIcon(req.special, /[^A-Za-z0-9]/.test(p));
                setIcon(req.match, p && c && p === c);
            }

            password.addEventListener('input', update);
            confirm.addEventListener('input', update);

            $('#modalUsuario').on('hidden.bs.modal', function() {
                panel.style.display = 'none';
                password.value = '';
                confirm.value = '';
                update();
            });
        }

        // Validación final antes del envío
        function validarContraseña() {
            const p = password.value || '';
            const c = confirm.value || '';
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,11}$/;
            if (!regex.test(p)) {
                Swal.fire('Contraseña no segura',
                    'Debe incluir mayúscula, minúscula, número y carácter especial (8 a 11 caracteres).',
                    'warning');
                return false;
            }
            if (p !== c) {
                Swal.fire('Contraseña no coincide', 'Ambas contraseñas deben ser iguales.', 'warning');
                return false;
            }
            return true;
        }

        /* ===============================
         ENVÍO AJAX
        =============================== */
        if (formCrearUsuario && guardarBtn) {
            guardarBtn.addEventListener("click", function(e) {
                e.preventDefault();

                // Validar antes de enviar
                if (!validarContraseña()) return;

                const formData = new FormData(formCrearUsuario);

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

        /* ===============================
         PREVISUALIZAR IMAGEN
        =============================== */
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
        if (editarModal) setupImagePreview(editarModal);

        const formEditarUsuario = document.getElementById("formEditarUsuario");
        const passwordInput = document.getElementById('contrasenaUsuarioEditar');
        const confirmInput = document.getElementById('confirmarContrasenaEditar');
        const panel = document.getElementById('passwordRequirementsEditar');

        // ============================
        // Validación en tiempo real (sin parpadeo)
        // ============================
        if (passwordInput && confirmInput && panel) {
            const req = {
                length: document.getElementById('reqLengthEditar'),
                upper: document.getElementById('reqUpperEditar'),
                lower: document.getElementById('reqLowerEditar'),
                number: document.getElementById('reqNumberEditar'),
                special: document.getElementById('reqSpecialEditar'),
                match: document.getElementById('reqMatchEditar')
            };

            function setIcon(li, ok) {
                const i = li.querySelector('i');
                i.classList.toggle('bi-check-circle', ok);
                i.classList.toggle('text-success', ok);
                i.classList.toggle('bi-x-circle', !ok);
                i.classList.toggle('text-danger', !ok);
            }

            function update() {
                const p = passwordInput.value || '';
                const c = confirmInput.value || '';
                const typing = p || c;

                if (typing) {
                    if (panel.style.display === "none" || panel.style.display === "") {
                        $(panel).fadeIn(200);
                    }
                } else {
                    $(panel).fadeOut(200);
                }

                setIcon(req.length, !typing || (p.length >= 8 && p.length <= 11));
                setIcon(req.upper, !typing || /[A-Z]/.test(p));
                setIcon(req.lower, !typing || /[a-z]/.test(p));
                setIcon(req.number, !typing || /\d/.test(p));
                setIcon(req.special, !typing || /[^A-Za-z0-9]/.test(p));
                setIcon(req.match, !typing || (p && c && p === c));
            }

            passwordInput.addEventListener('input', update);
            confirmInput.addEventListener('input', update);

            // Cuando se abre el modal
            $('#modalEditarUsuario').on('show.bs.modal', function() {
                passwordInput.value = '';
                confirmInput.value = '';
                panel.style.display = 'none'; // se mantiene oculto hasta escribir
                update();
            });

            // Cuando se cierra el modal
            $('#modalEditarUsuario').on('hidden.bs.modal', function() {
                passwordInput.value = '';
                confirmInput.value = '';
                panel.style.display = 'none';
            });
        }

        // ============================
        // Validación previa al submit
        // ============================
        function validarContraseñaEditar() {
            const p = passwordInput.value || '';
            const c = confirmInput.value || '';
            // Permitir vacío si no se cambia
            if (!p && !c) return true;

            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,11}$/;
            if (!regex.test(p)) {
                Swal.fire('Contraseña no segura',
                    'Debe incluir mayúscula, minúscula, número y carácter especial (8 a 11 caracteres).',
                    'warning');
                return false;
            }
            if (p !== c) {
                Swal.fire('Contraseña no coincide', 'Ambas contraseñas deben ser iguales.', 'warning');
                return false;
            }
            return true;
        }

        // ============================
        // Envío AJAX
        // ============================
        if (formEditarUsuario) {
            formEditarUsuario.addEventListener("submit", function(e) {
                e.preventDefault();

                if (!validarContraseñaEditar()) return;

                const formData = new FormData(this);
                const idUsuario = document.getElementById("idUsuarioEditar").value;

                const pwdVal = formEditarUsuario.querySelector('#contrasenaUsuarioEditar').value;
                const pwdConfVal = formEditarUsuario.querySelector('#confirmarContrasenaEditar').value;
                if (!pwdVal && !pwdConfVal) {
                    formData.delete('password');
                    formData.delete('password_confirmation');
                }

                $.ajax({
                    url: `/usuarios${idUsuario}`,
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

        // ============================
        //  Cargar datos e imagen al abrir modal
        // ============================
        $('#modalEditarUsuario').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const usuario = button.data('usuario');
            const modal = $(this);

            modal.find('#idUsuarioEditar').val(usuario.id_usuario);
            modal.find('#identificacionUsuarioEditar').val(usuario.identificacion);
            modal.find('#nombreUsuarioEditar').val(usuario.nombre_completo);
            modal.find('#correoUsuarioEditar').val(usuario.email);
            modal.find('#rolUsuarioEditar').val(usuario.rol);
            modal.find('#academiaUsuarioEditar').val(usuario.academia ?? 'Sin academia asignada');
            modal.find('input[name="estado"][value="' + usuario.estado + '"]').prop('checked', true);
            modal.find('#contrasenaUsuarioEditar').val('');
            modal.find('#confirmarContrasenaEditar').val('');
            modal.find('#removeImagen').val('0');

            const previewImage = modal.find('.previewImage');
            const previewText = modal.find('.previewText');
            const removeBtn = modal.find('.removeImageBtn');
            const inputFile = modal.find('#fotoUsuarioEditar');

            previewText.text('Sin foto');
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

        //  Previsualizar imagen

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

        // ======================================================
        // RESTABLECER CONTRASEÑA DESDE EL ADMIN 
        // ======================================================
        const btnResetPassword = document.getElementById("btnResetPassword");

        if (btnResetPassword) {
            btnResetPassword.addEventListener("click", async function() {
                const correo = document.getElementById("correoUsuarioEditar").value;

                if (!correo) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Correo no válido',
                        text: 'El usuario no tiene un correo registrado.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                const confirm = await Swal.fire({
                    title: '¿Restablecer contraseña?',
                    html: `Se enviará una contraseña temporal al correo:<br><b>${correo}</b>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, enviar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                });

                if (!confirm.isConfirmed) return;

                Swal.fire({
                    title: 'Enviando correo...',
                    text: 'Por favor, espere unos segundos.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch(`/recuperar-contraseña`, {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            correo
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Correo enviado!',
                            text: data.message,
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo enviar el correo.',
                            confirmButtonColor: '#d33'
                        });
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'No se pudo conectar con el servidor.',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }

    });

    // ELIMINAR
    window.confirmarEliminacion = function(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: `/usuarios/${id}`,
                type: 'POST', // si tu ruta es DELETE puro, usa type: 'DELETE'
                data: {
                    _method: 'DELETE'
                }, // method spoofing si tu server espera DELETE
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(resp) {
                    Swal.fire('Eliminado', resp.message || 'Usuario eliminado.', 'success')
                        .then(() => {
                            // refresca sin perder página actual
                            $('#tabla').DataTable().ajax.reload(null, false);
                        });
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'No se pudo eliminar el usuario.';
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    }
</script>


@endsection
