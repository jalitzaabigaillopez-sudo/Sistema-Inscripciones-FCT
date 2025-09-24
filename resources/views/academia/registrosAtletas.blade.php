@extends('academia')

@section('title', 'Registros de Atletas')
@section('content')
    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary float-end mb-3">
        <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
    </a>

    <h3 class="mb-5 text-black fw-bold">Mis Atletas Registrados</h3>

    <div class="mb-1 col-md-4 align-text-end">
        <button id="bNuevoAtleta" class="btn btn-outline-success w-100">
            <i class="bi bi-plus-circle"></i> Nuevo Atleta
        </button>
    </div>

    <div class="card table-card shadow">
        <div class="card-body p-3">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-striped table-hover table-bordered text-center border">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Identificación</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Sexo</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($atletas as $atleta)
                            <tr>
                                <td>#</td>
                                <td>{{$atleta->identificacion}}</td>
                                <td>{{$atleta->nombre}}</td>
                                <td>{{ $atleta->primer_apellido }} {{ $atleta->segundo_apellido }}</td>
                                <td>{{ $atleta->fecha_nacimiento }}</td>
                                <td>{{ $atleta->sexo }}</td>
                                <td>{{ $atleta->rol }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill"
                                            type="button" data-bs-toggle="dropdown">

                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item btn-edit"
                                                    href="#">
                                                    <i class="bi bi-pencil-square"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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