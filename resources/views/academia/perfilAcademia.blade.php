@extends('academia')

@section('title', 'Administración de Perfil')

@section('content')
    {{-- <a href="{{ route('dashboard') }}" class="btn btn-outline-primary float-end">
        <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
    </a> --}}

    <div class="container py-4">
        <h3 class="mb-4 text-black fw-bold">Administración de Perfil</h3>

        <hr>

        <div class="row justify-content-center">

            {{-- VISUALIZACIÓN DE ACADEMIA (100% RESPONSIVA) --}}
            <div class="col-12 col-lg-6">
                <div class="card shadow border-0 rounded-3 mb-4">
                    <!-- Encabezado -->
                    <div
                        class="card-header bg-white fw-semibold fs-5 d-flex flex-wrap justify-content-between align-items-center border-0">
                        <span class="text-primary mb-2 mb-sm-0">
                            <i class="bi bi-building me-2"></i> Información de la Academia
                        </span>
                        <button class="btn btn-sm btn-warning rounded-pill text-light mt-2 mt-sm-0" data-bs-toggle="modal"
                            data-bs-target="#modalEditarAcademia" data-academia='@json($academia)'>
                            <i class="bi bi-pencil-square"></i> Editar Academia
                        </button>
                    </div>

                    <!-- Cuerpo -->
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Nombre de la Academia</label>
                                <div class="p-2 border rounded bg-light">{{ $academia->nombre }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Profesor Encargado</label>
                                <div class="p-2 border rounded bg-light">{{ $academia->profesor_encargado }}</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Teléfono</label>
                                <div class="p-2 border rounded bg-light">{{ $academia->telefono }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Correo</label>
                                <div class="p-2 border rounded bg-light">{{ $academia->correo }}</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Dirección</label>
                                <div class="p-2 border rounded bg-light">{{ $academia->direccion }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Ubicación Geográfica</label>
                                <div class="p-2 border rounded bg-light">
                                    {{ $academia->distrito->canton->provincia->nombre ?? '—' }},
                                    {{ $academia->distrito->canton->nombre ?? '—' }},
                                    {{ $academia->distrito->nombre ?? '—' }}
                                </div>
                            </div>

                            <!-- Imagen -->
                            <div class="col-12 mt-3 text-center">
                                <label class="form-label fw-bold text-muted small mb-2">Logo de la Academia</label>
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $academia->imagen ? asset('storage/' . $academia->imagen) : asset('images/default-academia.png') }}"
                                        alt="Imagen de la academia" class="rounded-circle shadow-sm border img-fluid"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================
                                    MODAL DE EDICIÓN DE ACADEMIA
                                  ================================ -->
            <div class="modal fade" id="modalEditarAcademia" tabindex="-1" aria-labelledby="modalEditarAcademiaLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                        <div class="modal-header border-bottom-0 pb-2">
                            <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3"
                                id="modalEditarAcademiaLabel">
                                Editar Academia
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body p-0">
                            <form id="formEditarAcademia" method="POST"
                                action="{{ route('academias.update', $academia->id_academia) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="remove_imagen" id="removeImagen" value="0">
                                <input type="hidden" name="id_academia" id="idAcademia">

                                <div class="row g-4">
                                    <!-- Columna izquierda -->
                                    <div class="col-md-7 border-end pe-md-4">
                                        <h6 class="text-secondary mb-3">Información General</h6>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="nombreAcademiaEditar" class="form-label">Nombre <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="nombreAcademiaEditar" name="nombre" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="telefonoAcademiaEditar" class="form-label">Teléfono <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="telefonoAcademiaEditar" name="telefono" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="profesorAcademiaEditar" class="form-label">Profesor Encargado
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="profesorAcademiaEditar" name="profesor_encargado" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="correoAcademiaEditar" class="form-label">Correo Electrónico
                                                    <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control form-control-sm"
                                                    id="correoAcademiaEditar" name="correo" required>
                                            </div>
                                        </div>

                                        <div class="mb-3 mt-3">
                                            <label for="fotoAcademiaEditar" class="form-label">Foto de Perfil</label>
                                            <input class="form-control form-control-sm fotoAcademiaInput" type="file"
                                                id="fotoAcademiaEditar" name="imagen" accept="image/*">
                                        </div>

                                        <div class="mb-3 d-flex flex-column align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                                style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; overflow: hidden;">
                                                <span class="previewText text-muted">Sin foto</span>
                                                <img id="previewImagenAcademia"
                                                    class="previewImage img-thumbnail rounded-circle" src=""
                                                    alt="Vista previa"
                                                    style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                            </div>
                                            <button id="removeImagenAcademiaBtn" type="button"
                                                class="btn btn-sm btn-danger removeImageBtn" style="display: none;">
                                                <i class="bi bi-trash"></i> Eliminar Foto
                                            </button>
                                        </div>
                                    </div>

                                    <!--  Columna derecha -->
                                    <div class="col-md-5 ps-md-4">
                                        <h6 class="text-secondary mb-3">Ubicación</h6>

                                        <div class="mb-3">
                                            <label for="provinciaAcademiaEditar" class="form-label">Provincia <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="provinciaAcademiaEditar"
                                                name="provincia" required>
                                                <option value="" disabled selected>Seleccione una provincia...
                                                </option>
                                                @foreach ($provincias as $provincia)
                                                    <option value="{{ $provincia->id_provincia }}">
                                                        {{ $provincia->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="cantonAcademiaEditar" class="form-label">Cantón <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="cantonAcademiaEditar"
                                                name="canton" required>
                                                <option value="" disabled selected>Seleccione un cantón...</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="distritoAcademiaEditar" class="form-label">Distrito <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="distritoAcademiaEditar"
                                                name="distrito" required>
                                                <option value="" disabled selected>Seleccione un distrito...</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="direccionAcademiaEditar" class="form-label">Dirección <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="direccionAcademiaEditar" name="direccion" required>
                                        </div>

                                        <!--  Estado visible pero no editable -->
                                        <div class="mb-3">
                                            <label class="form-label">Estado actual</label>
                                            <!-- Campo visible (solo muestra, no se envía al servidor) -->
                                            <input type="text"
                                                class="form-control form-control-sm bg-light text-center fw-semibold rounded-pill"
                                                id="estadoAcademiaMostrar" readonly style="cursor: not-allowed;">

                                            <!-- Campo oculto (este sí se envía al servidor con el valor válido) -->
                                            <input type="hidden" name="estado" id="estadoAcademiaHidden">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" form="formEditarAcademia" class="btn btn-primary rounded-pill">
                                Guardar cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VISUALIZACIÓN DE USUARIO (100% RESPONSIVO) --}}
            <div class="col-12 col-lg-6">
                <div class="card shadow border-0 rounded-3 mb-4">
                    <!-- Encabezado -->
                    <div
                        class="card-header bg-white fw-semibold fs-5 d-flex flex-wrap justify-content-between align-items-center border-0">
                        <span class="text-primary mb-2 mb-sm-0">
                            <i class="bi bi-person-badge me-2"></i> Perfil del Usuario
                        </span>
                        <button class="btn btn-sm btn-warning rounded-pill text-light mt-2 mt-sm-0" data-bs-toggle="modal"
                            data-bs-target="#modalEditarPerfilAcademia" data-usuario='@json($usuario)'>
                            <i class="bi bi-pencil-square"></i> Editar Perfil
                        </button>
                    </div>

                    <!-- Cuerpo -->
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Identificación -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Número de Identificación</label>
                                <div class="p-2 border rounded bg-light">
                                    {{ $usuario->identificacion }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Rol</label>
                                <div class="p-2 border rounded bg-light">
                                    {{ ucfirst($usuario->rol) }}
                                </div>
                            </div>

                            <!-- Nombre y Estado -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Nombre completo</label>
                                <div class="p-2 border rounded bg-light">
                                    {{ $usuario->nombre_completo }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Estado</label>
                                <div class=" mt-1">
                                    <span
                                        class="badge {{ $usuario->estado == 'activo' ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </div>
                            </div>
                            <!-- Correo -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Correo electrónico</label>
                                <div class="p-2 border rounded bg-light">
                                    {{ $usuario->email }}
                                </div>
                            </div>


                            <!-- Contraseña -->

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-muted small">Contraseña</label>
                                <div class="p-2 border rounded bg-light">
                                    ••••••••
                                </div>
                            </div>


                            <!-- Imagen de perfil -->
                            {{-- <div class="col-12 mt-3 text-center">
                                <label class="form-label fw-bold text-muted small mb-2">Imagen de perfil</label>
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $usuario->imagen ? asset('storage/' . $usuario->imagen) : asset('images/default.png') }}"
                                        alt="Foto de perfil" class="rounded-circle shadow-sm border img-fluid"
                                        style="width: 130px; height: 130px; object-fit: cover;">
                                    <span class="text-muted small mt-2">{{ $usuario->nombre_completo }}</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de edición USUARIO-->
            <div class="modal fade modal-editar" id="modalEditarPerfilAcademia" tabindex="-1"
                aria-labelledby="modalEditarPerfilAcademiaLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                        <div class="modal-header border-bottom-0 pb-2">
                            <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3"
                                id="modalEditarPerfilAcademianLabel">
                                Actualizar Perfil
                            </h5>
                            <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body p-0">
                            <form id="formEditarPerfilAcademia" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id_usuario" id="idUsuarioPerfil">
                                <input type="hidden" name="remove_imagen" id="removeImagenPerfil" value="0">

                                <div class="row g-4">
                                    {{-- Columna izquierda --}}
                                    <div class="col-md-6 border-end pe-md-4">
                                        <h6 class="text-secondary mb-3">Información Personal</h6>

                                        <div class="mb-3">
                                            <label for="identificacionPerfilEditar" class="form-label">Identificación
                                                <span class="text-danger">*</span></label>
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

                                        <!-- Requisitos de contraseña -->
                                        <div id="passwordRequirementsPerfil" class="mt-2 text-muted small"
                                            style="display:none;">
                                            <p class="mb-1 fw-semibold text-dark">
                                                <i class="bi bi-shield-lock me-1 text-primary"></i> Requisitos de la
                                                contraseña:
                                            </p>
                                            <ul class="list-unstyled ms-3 mb-0">
                                                <li id="reqLengthPerfil"><i class="bi bi-x-circle text-danger me-1"></i>
                                                    Entre 8 y 11 caracteres</li>
                                                <li id="reqUpperPerfil"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                    menos una letra mayúscula</li>
                                                <li id="reqLowerPerfil"><i class="bi bi-x-circle text-danger me-1"></i> Al
                                                    menos una letra minúscula</li>
                                                <li id="reqNumberPerfil"><i class="bi bi-x-circle text-danger me-1"></i>
                                                    Al menos un número</li>
                                                <li id="reqSpecialPerfil"><i class="bi bi-x-circle text-danger me-1"></i>
                                                    Al menos un carácter especial (!@#$%^&*_-.,;:?)</li>
                                                <li id="reqMatchPerfil"><i class="bi bi-x-circle text-danger me-1"></i>
                                                    Las contraseñas coinciden</li>
                                            </ul>
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

                                        {{-- <div class="mb-3">
                                            <label for="fotoPerfilEditar" class="form-label">Foto de perfil</label>
                                            <input class="form-control form-control-sm fotoUsuarioInput" type="file"
                                                id="fotoPerfilEditar" name="imagen" accept="image/*">
                                        </div>

                                        <div class="mb-3 d-flex flex-column align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                                style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                                <span class="previewText text-muted">Sin foto</span>
                                                <img class="previewImage img-thumbnail rounded-circle"
                                                    src="{{ $usuario->imagen ? asset('storage/' . $usuario->imagen) : '' }}"
                                                    alt="Vista previa"
                                                    style="width: 150px; height: 150px; object-fit: cover; {{ $usuario->imagen ? 'display:block;' : 'display:none;' }}">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger removeImageBtn"
                                                style="{{ $usuario->imagen ? 'display:inline-block;' : 'display:none;' }}">
                                                <i class="bi bi-trash"></i> Eliminar Foto
                                            </button>
                                        </div> --}}

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

                /* ======================================================
                    PERFIL DE USUARIO
                ====================================================== */
                const editarPerfilModal = document.getElementById("modalEditarPerfilAcademia");
                if (editarPerfilModal) setupImagePreviewPerfil(editarPerfilModal);

                const formEditarPerfil = document.getElementById("formEditarPerfilAcademia");
                if (formEditarPerfil) {
                    formEditarPerfil.addEventListener("submit", function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        const idUsuario = document.getElementById("idUsuarioPerfil").value;

                        const password = formEditarPerfil.querySelector('#contrasenaPerfilEditar').value;
                        const passwordConfirmation = formEditarPerfil.querySelector(
                            '#confirmarContrasenaPerfil').value;
                        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,11}$/;

                        // Validación antes de enviar
                        if (password || passwordConfirmation) {
                            if (!regex.test(password)) {
                                Swal.fire({
                                    title: "Contraseña no válida",
                                    text: "Debe tener entre 8 y 11 caracteres, incluir mayúscula, minúscula, número y carácter especial.",
                                    icon: "warning",
                                    confirmButtonColor: "#3085d6"
                                });
                                return;
                            }
                            if (password !== passwordConfirmation) {
                                Swal.fire({
                                    title: "Contraseñas no coinciden",
                                    text: "Asegúrese de que ambas contraseñas sean iguales.",
                                    icon: "warning",
                                    confirmButtonColor: "#3085d6"
                                });
                                return;
                            }
                        } else {
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
                                        confirmButtonColor: '#3085d6'
                                    }).then(() => {
                                        $('#modalEditarPerfilAcademia').modal('hide');
                                        location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                let msg = 'Error al actualizar el perfil.';
                                if (xhr.status === 422)
                                    msg = Object.values(xhr.responseJSON?.errors || {}).flat().join(
                                        '<br>');
                                else if (xhr.status === 500)
                                    msg = xhr.responseJSON?.error || 'Error interno.';
                                Swal.fire({
                                    title: 'Error',
                                    html: msg,
                                    icon: 'error'
                                });
                            }
                        });
                    });
                }

                // ✅ VALIDACIÓN VISUAL DE CONTRASEÑA (ya existe en HTML)
                const passwordInput = document.getElementById("contrasenaPerfilEditar");
                const confirmInput = document.getElementById("confirmarContrasenaPerfil");
                const panel = document.getElementById("passwordRequirementsPerfil");

                if (passwordInput && confirmInput && panel) {
                    const req = {
                        length: document.getElementById("reqLengthPerfil"),
                        upper: document.getElementById("reqUpperPerfil"),
                        lower: document.getElementById("reqLowerPerfil"),
                        number: document.getElementById("reqNumberPerfil"),
                        special: document.getElementById("reqSpecialPerfil"),
                        match: document.getElementById("reqMatchPerfil")
                    };

                    function setIcon(li, ok) {
                        const i = li.querySelector("i");
                        i.className = ok ? "bi bi-check-circle text-success me-1" : "bi bi-x-circle text-danger me-1";
                    }

                    function updateValidation() {
                        const p = passwordInput.value;
                        const c = confirmInput.value;
                        const typing = p || c;
                        panel.style.display = typing ? "block" : "none";
                        setIcon(req.length, p.length >= 8 && p.length <= 11);
                        setIcon(req.upper, /[A-Z]/.test(p));
                        setIcon(req.lower, /[a-z]/.test(p));
                        setIcon(req.number, /\d/.test(p));
                        setIcon(req.special, /[^A-Za-z0-9]/.test(p));
                        setIcon(req.match, p && c && p === c);
                    }

                    passwordInput.addEventListener("input", updateValidation);
                    confirmInput.addEventListener("input", updateValidation);

                    $("#modalEditarPerfilAcademia").on("show.bs.modal hidden.bs.modal", function() {
                        passwordInput.value = "";
                        confirmInput.value = "";
                        panel.style.display = "none";
                    });
                }

                $('#modalEditarPerfilAcademia').on('show.bs.modal', function(event) {
                    const button = $(event.relatedTarget);
                    const usuario = button.data('usuario');
                    const modal = $(this);

                    modal.find('#idUsuarioPerfil').val(usuario.id_usuario);
                    modal.find('#identificacionPerfilEditar').val(usuario.identificacion);
                    modal.find('#nombrePerfilEditar').val(usuario.nombre_completo);
                    modal.find('#correoPerfilEditar').val(usuario.email);
                    modal.find('#rolPerfilEditar').val(usuario.rol);
                    modal.find(`input[name="estado"][value="${usuario.estado}"]`).prop('checked', true);

                    modal.find('#contrasenaPerfilEditar, #confirmarContrasenaPerfil').val('');
                    modal.find('#removeImagenPerfil').val('0');

                    const previewImage = modal.find('.previewImage');
                    const previewText = modal.find('.previewText');
                    const removeBtn = modal.find('.removeImageBtn');
                    const inputFile = modal.find('#fotoPerfilEditar');

                    if (usuario.imagen) {
                        previewImage.attr('src', '/storage/' + usuario.imagen).show();
                        previewText.hide();
                        removeBtn.show();
                    } else {
                        previewImage.hide();
                        previewText.show();
                        removeBtn.hide();
                    }
                    inputFile.val('');
                });

                function setupImagePreviewPerfil(modal) {
                    const inputFile = modal.querySelector("#fotoPerfilEditar");
                    const previewImage = modal.querySelector(".previewImage");
                    const previewText = modal.querySelector(".previewText");
                    const removeBtn = modal.querySelector(".removeImageBtn");
                    const removeInput = modal.querySelector("#removeImagenPerfil");

                    if (!inputFile) return;
                    inputFile.addEventListener("change", () => {
                        const file = inputFile.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = e => {
                                previewImage.src = e.target.result;
                                previewImage.style.display = "block";
                                previewText.style.display = "none";
                                removeBtn.style.display = "inline-block";
                                removeInput.value = "0";
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                    removeBtn.addEventListener("click", () => {
                        previewImage.src = "";
                        previewImage.style.display = "none";
                        previewText.style.display = "block";
                        removeBtn.style.display = "none";
                        inputFile.value = "";
                        removeInput.value = "1";
                    });
                }

                /* ======================================================
                    PERFIL DE ACADEMIA (con preselección de ubicación)
                ====================================================== */
                const modalAcademia = document.getElementById("modalEditarAcademia");
                const formAcademia = document.getElementById("formEditarAcademia");
                const preview = document.getElementById("previewImagenAcademia");
                const previewText = modalAcademia.querySelector(".previewText");
                const removeBtn = document.getElementById("removeImagenAcademiaBtn");
                const inputFile = document.getElementById("fotoAcademiaEditar");
                const removeInput = document.getElementById("removeImagen");

                const provinciaSel = document.getElementById("provinciaAcademiaEditar");
                const cantonSel = document.getElementById("cantonAcademiaEditar");
                const distritoSel = document.getElementById("distritoAcademiaEditar");

                async function cargarCantones(provinciaId) {
                    cantonSel.innerHTML = `<option value="">Cargando cantones...</option>`;
                    const resp = await fetch(`/cantones/${provinciaId}`);
                    const cantones = await resp.json();
                    cantonSel.innerHTML = `<option value="">Seleccione un cantón...</option>`;
                    cantones.forEach(c => cantonSel.innerHTML +=
                        `<option value="${c.id_canton}">${c.nombre}</option>`);
                }

                async function cargarDistritos(cantonId) {
                    distritoSel.innerHTML = `<option value="">Cargando distritos...</option>`;
                    const resp = await fetch(`/distritos/${cantonId}`);
                    const distritos = await resp.json();
                    distritoSel.innerHTML = `<option value="">Seleccione un distrito...</option>`;
                    distritos.forEach(d => distritoSel.innerHTML +=
                        `<option value="${d.id_distrito}">${d.nombre}</option>`);
                }

                $('#modalEditarAcademia').on('show.bs.modal', async function(event) {
                    const button = $(event.relatedTarget);
                    const data = button.data('academia');
                    if (!data) return;

                    $('#idAcademia').val(data.id_academia);
                    $('#nombreAcademiaEditar').val(data.nombre);
                    $('#profesorAcademiaEditar').val(data.profesor_encargado);
                    $('#telefonoAcademiaEditar').val(data.telefono);
                    $('#correoAcademiaEditar').val(data.correo);
                    $('#direccionAcademiaEditar').val(data.direccion);

                    $('#estadoAcademiaMostrar').val(data.estado === 'activo' ? 'Activo' : 'Inactivo');
                    $('#estadoAcademiaHidden').val(data.estado);

                    if (data.imagen) {
                        preview.src = `/storage/${data.imagen}`;
                        preview.style.display = "block";
                        previewText.style.display = "none";
                        removeBtn.style.display = "inline-block";
                    } else {
                        preview.src = "";
                        preview.style.display = "none";
                        previewText.style.display = "block";
                        removeBtn.style.display = "none";
                    }

                    inputFile.value = "";
                    removeInput.value = "0";

                    const provinciaId = data?.distrito?.canton?.provincia?.id_provincia || '';
                    const cantonId = data?.distrito?.canton?.id_canton || '';
                    const distritoId = data?.distrito?.id_distrito || '';

                    if (provinciaId) {
                        provinciaSel.value = provinciaId;
                        await cargarCantones(provinciaId);
                        if (cantonId) {
                            cantonSel.value = cantonId;
                            await cargarDistritos(cantonId);
                            if (distritoId) distritoSel.value = distritoId;
                        }
                    } else {
                        cantonSel.innerHTML = `<option value="">Seleccione un cantón...</option>`;
                        distritoSel.innerHTML = `<option value="">Seleccione un distrito...</option>`;
                    }
                });

                provinciaSel.addEventListener("change", async function() {
                    const provinciaId = this.value;
                    if (provinciaId) {
                        await cargarCantones(provinciaId);
                        distritoSel.innerHTML = `<option value="">Seleccione un distrito...</option>`;
                    }
                });

                cantonSel.addEventListener("change", async function() {
                    const cantonId = this.value;
                    if (cantonId) await cargarDistritos(cantonId);
                });

                inputFile.addEventListener("change", function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            preview.src = e.target.result;
                            preview.style.display = "block";
                            previewText.style.display = "none";
                            removeBtn.style.display = "inline-block";
                            removeInput.value = "0";
                        };
                        reader.readAsDataURL(file);
                    }
                });

                removeBtn.addEventListener("click", () => {
                    preview.src = "";
                    preview.style.display = "none";
                    previewText.style.display = "block";
                    removeBtn.style.display = "none";
                    inputFile.value = "";
                    removeInput.value = "1";
                });

                if (formAcademia) {
                    formAcademia.addEventListener("submit", async function(e) {
                        e.preventDefault();

                        const id = document.getElementById("idAcademia").value;
                        const formData = new FormData(formAcademia);

                        //  Confirmación previa
                        const confirm = await Swal.fire({
                            title: '¿Guardar cambios?',
                            text: 'Se actualizará la información de la academia.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, guardar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33'
                        });

                        if (!confirm.isConfirmed) return;

                        //  Mostrar loading
                        Swal.fire({
                            title: 'Actualizando...',
                            text: 'Por favor, espere mientras se guardan los cambios.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        try {
                            const response = await fetch(`/academias/${id}`, {
                                method: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                },
                                body: formData
                            });

                            const result = await response.json();

                            //  Validaciones del backend
                            if (response.status === 422) {
                                const errores = Object.values(result.errors || {})
                                    .flat()
                                    .join('<br>');
                                Swal.fire({
                                    title: 'Campos inválidos',
                                    html: errores,
                                    icon: 'warning',
                                    confirmButtonColor: '#f8bb86'
                                });
                                return;
                            }

                            //  Errores internos
                            if (!response.ok) {
                                Swal.fire({
                                    title: 'Error',
                                    text: result.error ||
                                        'Ocurrió un error al actualizar la academia.',
                                    icon: 'error',
                                    confirmButtonColor: '#d33'
                                });
                                return;
                            }

                            //  Éxito
                            if (result.success) {
                                Swal.fire({
                                    title: '¡Actualización exitosa!',
                                    text: result.message ||
                                        'La academia fue actualizada correctamente.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    $('#modalEditarAcademia').modal('hide');
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Aviso',
                                    text: result.message ||
                                        'No se realizaron cambios en la academia.',
                                    icon: 'info',
                                    confirmButtonColor: '#3085d6'
                                });
                            }

                        } catch (error) {
                            console.error(' Error inesperado al actualizar:', error);
                            Swal.fire({
                                title: 'Error inesperado',
                                text: 'No se pudo conectar con el servidor. Intente nuevamente.',
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }

            });
        </script>
