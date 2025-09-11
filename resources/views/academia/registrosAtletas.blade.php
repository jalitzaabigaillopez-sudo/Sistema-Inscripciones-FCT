@extends('academia')

@section('title', 'Registros de Atletas')
@section('content')
<a href="{{ route('dashboard.academias') }}" class="btn btn-outline-primary float-end mb-3">
    <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
</a>

<h3 class="mb-4 text-black fw-bold">Mis Atletas Registrados</h3>

<div class="card table-card shadow">
    <div class="card-body p-3">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-striped table-hover table-bordered text-center border">
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
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Juan</td>
                        <td>Pérez Gómez</td>
                        <td>2005-03-12</td>
                        <td>Masculino</td>
                        <td>70kg</td>
                        <td>1.75m</td>
                        <td>8888-1234</td>
                        <td>San José, Costa Rica</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Ana</td>
                        <td>Gómez Ruiz</td>
                        <td>2007-07-25</td>
                        <td>Femenino</td>
                        <td>60kg</td>
                        <td>1.65m</td>
                        <td>8888-5678</td>
                        <td>Alajuela, Costa Rica</td>
                    </tr>
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