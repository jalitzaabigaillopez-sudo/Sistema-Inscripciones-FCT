@extends('app')

@section('tituloArriba')
    Administrar Atletas
@endsection

@section('breadcrumb-title', 'Administración del Perfil')

@section('title', 'Administración de Perfil')

@section('content')
    {{-- <a href="{{ route('adminDash') }}" class="btn btn-outline-primary float-end">
        <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
    </a> --}}

    <div class="container py-4">

        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Administración del Perfil</h4>
        </div>
        <hr>


        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow border-1 rounded-3">
                    <!-- Encabezado -->
                    <div
                        class="card-header bg-white fw-semibold fs-5 d-flex flex-column flex-sm-row justify-content-between align-items-center border-0 gap-3 text-center text-sm-start">
                        <!-- Título -->
                        <span class="text-primary">
                            <i class="bi bi-person-badge me-2"></i> Perfil del Usuario
                        </span>
                        <!-- Botón editar -->
                        <button
                            class="btn btn-sm btn-warning text-light rounded-pill d-flex align-items-center justify-content-center "
                            data-bs-toggle="modal" data-bs-target="#modalEditarPerfilAdmin"
                            data-usuario='@json($usuario)'>
                            <i class="bi bi-pencil-square me-2"></i>
                            Editar Perfil
                        </button>
                    </div>

                    <!-- Cuerpo -->
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold text-muted small">Número de Identificación</label>
                                <input type="text" class="form-control bg-light border-1 shadow-sm"
                                    value="{{ $usuario->identificacion }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Nombre completo</label>
                                <input type="text" class="form-control bg-light border-1 shadow-sm"
                                    value="{{ $usuario->nombre_completo }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold text-muted small">Correo electrónico</label>
                                <input type="email" class="form-control bg-light border-1 shadow-sm"
                                    value="{{ $usuario->email }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Rol</label>
                                <input type="text" class="form-control bg-light border-1 shadow-sm"
                                    value="{{ ucfirst($usuario->rol) }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold text-muted small">Contraseña</label>
                                <input type="password" class="form-control bg-light border-1 shadow-sm" value="••••••••"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Estado</label>
                                <div class="form-control border-0">
                                    <span
                                        class="badge {{ $usuario->estado == 'activo' ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2 shadow-sm">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <label class="form-label fw-bold text-muted small mb-2">Imagen de perfil</label>
                                <div class="d-flex flex-column align-items-center">
                                    @php
                                        $imagenPerfil = $usuario->imagen
                                            ? asset('storage/' . $usuario->imagen)
                                            : ($usuario->rol === 'administrador'
                                                ? asset('images/fct_logo.jpg')
                                                : asset('images/Logotipo.png'));
                                    @endphp

                                    <img src="{{ $imagenPerfil }}" alt="Foto de perfil"
                                        class="rounded-circle shadow-sm border"
                                        style="width: 130px; height: 130px; object-fit: cover;">

                                    {{-- <span class="text-muted small mt-2 fw-semibold">
                                        {{ $usuario->nombre_completo }}
                                    </span> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal de edición -->
            <div class="modal fade modal-editar" id="modalEditarPerfilAdmin" tabindex="-1"
                aria-labelledby="modalEditarPerfilAdminLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                        <div class="modal-header border-bottom-0 pb-2">
                            <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3"
                                id="modalEditarPerfilAdminLabel">
                                Actualizar Perfil
                            </h5>
                            <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body p-0">
                            <form id="formEditarPerfilAdmin" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id_usuario" id="idUsuarioPerfil">
                                <input type="hidden" name="remove_imagen" id="removeImagenPerfil" value="0">

                                <div class="row g-4">
                                    {{-- Columna izquierda --}}
                                    <div class="col-md-6 border-end pe-md-4">
                                        <h6 class="text-secondary mb-3">Información Personal</h6>

                                        <div class="mb-3">
                                            <label for="identificacionPerfilEditar" class="form-label">Identificación <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="identificacionPerfilEditar" name="identificacion"
                                                value="{{ $usuario->identificacion }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="nombrePerfilEditar" class="form-label">Nombre completo <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="nombrePerfilEditar" name="nombre_completo"
                                                value="{{ $usuario->nombre_completo }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="correoPerfilEditar" class="form-label">Correo electrónico <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control form-control-sm"
                                                id="correoPerfilEditar" name="email" value="{{ $usuario->email }}"
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="contrasenaPerfilEditar" class="form-label">Contraseña</label>
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control form-control-sm"
                                                    id="contrasenaPerfilEditar" name="password"
                                                    placeholder="Dejar vacío si no desea cambiarla">
                                                <button class="btn btn-outline-primary toggle-password" type="button"
                                                    data-target="#contrasenaPerfilEditar">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="confirmarContrasenaPerfil" class="form-label">Confirmar
                                                contraseña</label>
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control form-control-sm"
                                                    id="confirmarContrasenaPerfil" name="password_confirmation"
                                                    placeholder="Repetir la contraseña">
                                                <button class="btn btn-outline-primary toggle-password" type="button"
                                                    data-target="#confirmarContrasenaPerfil">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Columna derecha --}}
                                    <div class="col-md-6 ps-md-4">
                                        <h6 class="text-secondary mb-3">Rol y Foto de Perfil</h6>

                                        <div class="mb-3">
                                            <label for="rolPerfilEditar" class="form-label">Rol <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="rolPerfilEditar"
                                                name="rol" required disabled>
                                                <option value="administrador"
                                                    {{ $usuario->rol == 'administrador' ? 'selected' : '' }}>Administrador
                                                </option>
                                                <option value="academia"
                                                    {{ $usuario->rol == 'academia' ? 'selected' : '' }}>Academia</option>
                                                <option value="arbitro"
                                                    {{ $usuario->rol == 'arbitro' ? 'selected' : '' }}>Árbitro</option>
                                            </select>

                                            {{-- Campo oculto --}}
                                            <input type="hidden" name="rol" id="rolPerfilHidden"
                                                value="{{ $usuario->rol }}">

                                        </div>

                                        <div class="mb-3">
                                            <label for="fotoPerfilEditar" class="form-label">Foto de perfil</label>
                                            <input class="form-control form-control-sm fotoUsuarioInput" type="file"
                                                id="fotoPerfilEditar" name="imagen" accept="image/*">
                                        </div>

                                        <div class="mb-3 d-flex flex-column align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                                style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                                <span class="previewText text-muted">Sin foto</span>
                                                @php
                                                    $imagenModal = $usuario->imagen
                                                        ? asset('storage/' . $usuario->imagen)
                                                        : ($usuario->rol === 'administrador'
                                                            ? asset('images/fct_logo.jpg')
                                                            : asset('images/Logotipo.png'));
                                                @endphp

                                                <img class="previewImage img-thumbnail rounded-circle"
                                                    src="{{ $imagenModal }}" alt="Vista previa"
                                                    style="width: 150px; height: 150px; object-fit: cover; {{ $imagenModal ? 'display:block;' : 'display:none;' }}">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger removeImageBtn"
                                                style="{{ $usuario->imagen ? 'display:inline-block;' : 'display:none;' }}">
                                                <i class="bi bi-trash"></i> Eliminar Foto
                                            </button>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label d-block">Estado <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="estado_dummy"
                                                    id="perfil_estado_activo_dummy" value="activo"
                                                    {{ $usuario->estado == 'activo' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label"
                                                    for="perfil_estado_activo_dummy">Activo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="estado_dummy"
                                                    id="perfil_estado_inactivo_dummy" value="inactivo"
                                                    {{ $usuario->estado == 'inactivo' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label"
                                                    for="perfil_estado_inactivo_dummy">Inactivo</label>
                                            </div>
                                            {{-- Campo oculto estado --}}
                                            <input type="hidden" name="estado" id="estadoPerfilHidden"
                                                value="{{ $usuario->estado }}">

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


        @endsection

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const editarPerfilModal = document.getElementById("modalEditarPerfilAdmin");

                if (editarPerfilModal) {
                    setupImagePreviewPerfil(editarPerfilModal);
                }

                const formEditarPerfil = document.getElementById("formEditarPerfilAdmin");
                if (formEditarPerfil) {
                    formEditarPerfil.addEventListener("submit", function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        const idUsuario = document.getElementById("idUsuarioPerfil").value;

                        // Excluir password si están vacíos
                        const password = formEditarPerfil.querySelector('#contrasenaPerfilEditar').value;
                        const passwordConfirmation = formEditarPerfil.querySelector(
                            '#confirmarContrasenaPerfil').value;
                        if (!password && !passwordConfirmation) {
                            formData.delete('password');
                            formData.delete('password_confirmation');
                        }

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
                                        $('#modalEditarPerfilAdmin').modal('hide');
                                        formEditarPerfil.reset();

                                        // Actualizar imagen del perfil
                                        if (response.newImagePath) {
                                            const perfilImg = document.querySelector(
                                                '.card-body img.rounded-circle');
                                            if (perfilImg) {
                                                perfilImg.src = response.newImagePath +
                                                    '?t=' + new Date().getTime();
                                            }
                                        }

                                        // Actualizar navbar
                                        const dropdown = document.getElementById(
                                            'userDropdown');
                                        if (dropdown && response.newImagePath) {
                                            const img = dropdown.querySelector('img');
                                            const icon = dropdown.querySelector(
                                                '.user-icon');

                                            if (img) {
                                                img.src = response.newImagePath + '?t=' +
                                                    new Date().getTime();
                                            } else if (icon) {
                                                icon.remove();
                                                const newImg = document.createElement(
                                                    'img');
                                                newImg.src = response.newImagePath + '?t=' +
                                                    new Date().getTime();
                                                newImg.alt = "Foto de perfil";
                                                newImg.style.cssText =
                                                    "height:35px;width:35px;border-radius:50%;object-fit:cover;";
                                                dropdown.prepend(newImg);
                                            }
                                        }

                                        // Si cambió contraseña → redirigir
                                        if (response.logout) {
                                            window.location.href = "{{ route('login') }}";
                                        } else {
                                            // Opcional: recarga ligera
                                            setTimeout(() => location.reload(), 300);
                                        }
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Error al actualizar el perfil.';
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

                $('#modalEditarPerfilAdmin').on('show.bs.modal', function(event) {
                    const button = $(event.relatedTarget); // Botón que abre el modal
                    const usuario = button.data('usuario'); // Datos pasados con data-usuario
                    const modal = $(this);

                    modal.find('#idUsuarioPerfil').val(usuario.id_usuario);
                    modal.find('#identificacionPerfilEditar').val(usuario.identificacion);
                    modal.find('#nombrePerfilEditar').val(usuario.nombre_completo);
                    modal.find('#correoPerfilEditar').val(usuario.email);
                    modal.find('#rolPerfilEditar').val(usuario.rol);
                    modal.find('input[name="estado"][value="' + usuario.estado + '"]').prop('checked', true);

                    modal.find('#contrasenaPerfilEditar').val('');
                    modal.find('#confirmarContrasenaPerfil').val('');
                    modal.find('#removeImagenPerfil').val('0');

                    const previewImage = modal.find('.previewImage');
                    const previewText = modal.find('.previewText');
                    const removeBtn = modal.find('.removeImageBtn');
                    const inputFile = modal.find('#fotoPerfilEditar');

                    previewText.text('Sin foto');
                    if (usuario.imagen && usuario.imagen !== '') {
                        // Tiene imagen personalizada
                        previewImage.attr('src', '/storage/' + usuario.imagen).show();
                        previewText.hide();
                        removeBtn.show();
                    } else if (usuario.rol === 'administrador') {
                        // Es admin pero no tiene imagen → mostrar logo FCT
                        previewImage.attr('src', '/images/fct_logo.jpg').show();
                        previewText.hide();
                        removeBtn.hide();
                    } else {
                        // Usuario sin imagen (academia/arbitro)
                        previewImage.hide();
                        previewText.show();
                        removeBtn.hide();
                    }


                    inputFile.val('');
                });

                // Setup de preview de imagen para Perfil Admin
                function setupImagePreviewPerfil(modalElement) {
                    const inputFile = modalElement.querySelector("#fotoPerfilEditar");
                    const previewImage = modalElement.querySelector(".previewImage");
                    const previewText = modalElement.querySelector(".previewText");
                    const removeBtn = modalElement.querySelector(".removeImageBtn");
                    const removeImagenInput = modalElement.querySelector("#removeImagenPerfil");

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
        </script>
