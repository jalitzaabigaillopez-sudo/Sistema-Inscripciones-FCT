@extends('app')

@section('content')
<a href="{{ route('adminDash') }}" class="btn btn-outline-primary float-end">
    Volver al Dashboard
</a>
<div class="container mt-4">
    <h2 class="mb-4">Perfil administrador</h2>

    <div class="card">
        <div class="card-header">Ajustes perfil</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf

                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" required>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select class="form-select" id="rol" name="rol">
                        <option value="admin">Administrador</option>
                        <option value="user">Usuario</option>
                        {{-- Agrega más roles si es necesario --}}
                    </select>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-success">Crear</button>
                    <button type="button" class="btn btn-warning">Editar</button>
                    <button type="button" class="btn btn-danger">Eliminar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <p class="mt-3 text-muted">Log in as: <strong>Administrador</strong></p>
</div>
@endsection
