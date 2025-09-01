@extends('academia')

@section('title', 'Administración de Perfil')

@section('content')
<a href="{{ route('dashboard.academias') }}" class="btn btn-outline-primary float-end">
    <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
</a>

<div class="container py-4">
    <h3 class="mb-4 text-black fw-bold">Administración de Perfil</h3>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold fs-5 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-person-badge me-2"></i> Datos del Usuario</span>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarPerfil">
                        <i class="bi bi-pencil-square"></i> Editar Perfil
                    </button>
                </div>
               <div class="card-body">
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tipo de Identificación</label>
            <p class="form-control-plaintext">Nacional</p>
        </div>
        <div class="col-md-8">
            <label class="form-label fw-semibold">Número de Identificación</label>
            <p class="form-control-plaintext">1-2345-6789</p>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Nombre completo</label>
            <p class="form-control-plaintext">Diana Castro</p>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Rol</label>
            <p class="form-control-plaintext">Academia</p>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Estado</label>
            <p class="form-control-plaintext">Activo</p>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Correo electrónico</label>
            <p class="form-control-plaintext">usuario@fct.cr</p>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Contraseña</label>
            <p class="form-control-plaintext">••••••••</p>
        </div>
    </div>

   <div class="row g-3 mt-3">
    <div class="col-md-12 text-center">
        <label class="form-label fw-semibold">Imagen de perfil</label>
        <div>
            <img src="https://randomuser.me/api/portraits/women/44.jpg" 
                 alt="Imagen de perfil" 
                 class="rounded-circle shadow-sm" 
                 style="width: 180px; height: 180px; object-fit: cover;">
        </div>
    </div>
</div>

</div>

            </div>
        </div>
    </div>
</div>

<!-- Modal de edición -->
<div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-labelledby="modalEditarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarPerfilLabel">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo de Identificación</label>
                        <select class="form-select">
                            <option selected>Nacional</option>
                            <option>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Número de Identificación</label>
                        <input type="text" class="form-control" value="1-2345-6789">
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" value="Diana Castro">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Rol</label>
                        <select class="form-select">
                            <option selected>Academia</option>
                            <option>Árbitro</option>
                            <option>Administrador</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select">
                            <option selected>Activo</option>
                            <option>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" value="usuario@fct.cr">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" value="••••••••">
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-12">
                        <label class="form-label">Imagen de perfil</label>
                        <input type="file" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
