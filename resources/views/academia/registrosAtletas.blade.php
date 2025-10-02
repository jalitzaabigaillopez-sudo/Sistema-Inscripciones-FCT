@extends('academia')

@section('title', 'Gestión de Atletas')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="mb-4 text-black fw-bold">Gestión de Atletas</h3>
</div>

<!-- Barra de búsqueda global y filtros -->
form method="GET" action="{{ route('academia.atletas.listar') }}" class="row g-2 mb-3 align-items-left">
    <div class="col-md-6 d-flex">
        <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control me-2" placeholder="Buscar por cualquier campo...">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i> Buscar / Filtrar
        </button>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('academia.atletas.nuevo') }}" class="btn btn-success">
            <i class="bi bi-person-plus"></i> Nuevo Atleta
        </a>
    </div>

    </div>
</form>

<!-- Tabla de atletas -->
<div class="card shadow">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Sexo</th>
                        <th>Peso</th>
                        <th>Altura</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
               
                   <tbody>
    @forelse($atletas as $atleta)
        <tr>
            <td>{{ $atleta->id }}</td>
            <td>{{ $atleta->nombre }}</td>
            <td>{{ $atleta->apellidos }}</td>
            <td>{{ $atleta->fecha_nacimiento }}</td>
            <td>{{ $atleta->sexo }}</td>
            <td>{{ $atleta->peso }} kg</td>
            <td>{{ $atleta->altura }} m</td>
            <td>{{ $atleta->contacto }}</td>
            <td>{{ $atleta->direccion }}</td>
            <td>
                <div class="btn-group">
                    <a href="{{ route('academia.atletas.editar', $atleta->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('academia.atletas.eliminar', $atleta->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar atleta?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10">No se encontraron atletas</td>
        </tr>
    @endforelse

                            <div class="btn-group">
                               <a href="{{ route('academia.atletas.editar', $atleta->id) }}" class="btn btn-sm btn-outline-primary">
    <i class="bi bi-pencil-square"></i>
</a>
    <form action="{{ route('academia.atletas.eliminar', $atleta->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar atleta?')">
        <i class="bi bi-trash"></i>
    </button>
</form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación estática -->
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link bg-light text-secondary border-0">Anterior</a>
                </li>
                <li class="page-item active">
                    <a class="page-link bg-primary border-0 text-white">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-light border-0 text-primary">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-light border-0 text-primary">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-light text-primary border-0">Siguiente</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
