@extends('academia')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-3">Atletas registrados en esta academia</h4>

    {{-- Botón para abrir modal --}}
    <div class="mb-3 text-end">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrear">
            + Nuevo Atleta
        </button>
    </div>

    {{-- Tabla --}}
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>Foto</th>
                <th>Identificación</th>
                <th>Nombre completo</th>
                <th>Sexo</th>
                <th>Fecha nacimiento</th>
                <th>Grado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($atletas as $a)
                <tr>
                    <td>
                        @if($a->imagen)
                            <img src="{{ asset('storage/' . $a->imagen) }}" alt="Foto" width="50" height="50" class="rounded-circle">
                        @else
                            <span class="text-muted">Sin foto</span>
                        @endif
                    </td>
                    <td>{{ $a->identificacion }}</td>
                    <td>{{ $a->nombre }} {{ $a->primer_apellido }} {{ $a->segundo_apellido }}</td>
                    <td>{{ $a->sexo }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->fecha_nacimiento)->format('d/m/Y') }}</td>
                    <td>{{ $a->grado->nombre ?? '' }}</td>
                    <td>
                        <a href="{{ route('registro-atletas.edit', $a->id_atleta) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('registro-atletas.destroy', $a->id_atleta) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No hay atletas registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modal Crear --}}
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('registro-atletas.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_academia" value="{{ $academia->id_academia }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearLabel">Registrar Nuevo Atleta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Campos básicos --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Identificación *</label>
                                <select name="tipo_identificacion" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Nacional">Nacional</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Identificación *</label>
                                <input type="text" name="identificacion" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Primer Apellido *</label>
                                <input type="text" name="primer_apellido" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Segundo Apellido</label>
                                <input type="text" name="segundo_apellido" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sexo *</label>
                                <select name="sexo" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Grado *</label>
                                <select name="id_grado" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($grados as $grado)
                                        <option value="{{ $grado->id_grado }}">{{ $grado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Foto de Perfil</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
