@extends('app')

@section('tituloArriba')
    Administrar Usuarios
@endsection

@section('breadcrumb-title', 'Lista de Usuarios')

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
                                <th class="text-center">Imagen</th>
                                <th class="text-center">Rol</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($data as $item)
                                <tr class="text-center">
                                    <td>{{ $item->identificacion }}</td>
                                    <td>{{ $item->nombre_completo }}</td>
                                    <td>{{ $item->email }}</td>
                                    {{-- <td>{{ $item->password }}</td> --}}
                                    <td>
                                        @if ($item->imagen)
                                            {{-- <img src="{{  $item->imagen }}" alt="Imagen"
                                                width="100"> --}}
                                        @else
                                            No hay imagen
                                        @endif
                                    </td>
                                    <td>{{ $item->rol }}</td>
                                    <td>
                                        @if ($item->estado === 'activo')
                                            <span class="badge rounded-pill bg-success">
                                                {{ ucfirst($item->estado) }}
                                            </span>
                                        @elseif($item->estado === 'inactivo')
                                            <span class="badge rounded-pill bg-danger">
                                                {{ ucfirst($item->estado) }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary">
                                                {{ ucfirst($item->estado) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-warning me-1 rounded-pill" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('usuarios.destroy', $item) }}" method="POST"
                                            id="form-eliminar-{{ $item->id_usuario }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                data-bs-toggle="tooltip" title="Eliminar Usuario"
                                                onclick="confirmarEliminacion({{ $item->id_usuario }})"
                                                onclick="return confirm('¿Eliminar esta academia?')">
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

        {{-- Modal CREAR USUARIO --}}
        <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalUsuarioLabel">Registrar
                            Nuevo Usuario</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form>
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información Personal</h6>
                                    <div class="mb-3">
                                        <label for="identificacionUsuario" class="form-label">Identificación <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="identificacionUsuario" placeholder="Ej. 123456789" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombreUsuario" class="form-label">Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="nombreUsuario"
                                            placeholder="Ej. Juan Pérez" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="correoUsuario" class="form-label">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm" id="correoUsuario"
                                            placeholder="Ej. correo@email.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contrasenaUsuario" class="form-label">Contraseña <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control form-control-sm" id="contrasenaUsuario"
                                            placeholder="Mínimo 8 caracteres" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmarContrasena" class="form-label">Confirmar Contraseña <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control form-control-sm"
                                            id="confirmarContrasena" placeholder="Repetir la contraseña" required>
                                    </div>
                                </div>

                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Rol y Foto de Perfil</h6>
                                    <div class="mb-3">
                                        <label for="rolUsuario" class="form-label">Rol <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="rolUsuario" required>
                                            <option value="" selected disabled>Seleccione el rol</option>
                                            <option value="administrador">Administrador</option>
                                            <option value="academia">Academia</option>
                                            <option value="arbitro">Árbitro</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fotoUsuario" class="form-label">Foto de Perfil</label>
                                        <input class="form-control form-control-sm" type="file" id="fotoUsuario"
                                            accept="image/*">
                                    </div>

                                    <div class="mb-3 d-flex flex-column align-items-center">
                                        <div id="previewContainer"
                                            class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                            style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                            <span id="previewText" class="text-muted">Sin foto</span>
                                            <img id="previewImage" src="" alt="Vista previa"
                                                class="img-thumbnail rounded-circle"
                                                style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                        </div>
                                        <button type="button" id="removeImage" class="btn btn-sm btn-danger"
                                            style="display: none;"> <i class="bi bi-trash"></i></button>
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
        <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarUsuarioLabel">
                            Actualizar Datos de Usuario</h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form>
                            <div class="row g-4">
                                <div class="col-md-6 border-end pe-md-4">
                                    <h6 class="text-secondary mb-3">Información Personal</h6>
                                    <div class="mb-3">
                                        <label for="identificacionUsuario" class="form-label">Identificación <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="identificacionUsuario" placeholder="Ej. 123456789" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombreUsuario" class="form-label">Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="nombreUsuario"
                                            placeholder="Ej. Juan Pérez" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="correoUsuario" class="form-label">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm" id="correoUsuario"
                                            placeholder="Ej. correo@email.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contrasenaUsuario" class="form-label">Contraseña <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control form-control-sm"
                                            id="contrasenaUsuario" placeholder="Mínimo 8 caracteres" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmarContrasena" class="form-label">Confirmar Contraseña <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control form-control-sm"
                                            id="confirmarContrasena" placeholder="Repetir la contraseña" required>
                                    </div>
                                </div>

                                <div class="col-md-6 ps-md-4">
                                    <h6 class="text-secondary mb-3">Rol y Foto de Perfil</h6>
                                    <div class="mb-3">
                                        <label for="rolUsuario" class="form-label">Rol <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="rolUsuario" required>
                                            <option value="" selected disabled>Seleccione el rol</option>
                                            <option value="administrador">Administrador</option>
                                            <option value="academia">Academia</option>
                                            <option value="arbitro">Árbitro</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fotoUsuario" class="form-label">Foto de Perfil</label>
                                        <input class="form-control form-control-sm" type="file" id="fotoUsuario"
                                            accept="image/*">
                                    </div>

                                    <div class="mb-3 d-flex flex-column align-items-center">
                                        <div id="previewContainer"
                                            class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                            style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px dashed #ccc; position: relative; overflow: hidden;">
                                            <span id="previewText" class="text-muted">Sin foto</span>
                                            <img id="previewImage" src="" alt="Vista previa"
                                                class="img-thumbnail rounded-circle"
                                                style="width: 150px; height: 150px; object-fit: cover; display: none;">
                                        </div>
                                        <button type="button" id="removeImage" class="btn btn-sm btn-danger"
                                            style="display: none;"> <i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const inputFile = document.getElementById("fotoUsuario");
        const previewImage = document.getElementById("previewImage");
        const previewText = document.getElementById("previewText");
        const removeBtn = document.getElementById("removeImage");

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
            inputFile.value = ""; // resetea el input file
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
