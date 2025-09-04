@extends('app')

@section('tituloArriba')
    Administrar Divisiones
@endsection

@section('breadcrumb-title', 'Lista de Divisiones')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-bold mb-0">Lista de Divisiones</h4>
        <button type="button" class="btn btn-success btn-md rounded-pill ms-auto" data-bs-toggle="modal" data-bs-target="#modalDivision">
            <i class="bi bi-plus-circle me-1"></i> Nueva División
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
                            <th class="text-center">División</th>
                            <th class="text-center">Year_Inicio</th>
                            <th class="text-center">Year_Final</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
    <tbody>
    @forelse($divisiones as $division)
        <tr>
            <td>{{ $division->division }}</td>
            <td>{{ $division->year_inicio }}</td>
            <td>{{ $division->year_final }}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary rounded-pill me-1"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditarDivision"
                        data-id="{{ $division->id }}"
                        data-nombre="{{ $division->division }}"
                        data-inicio="{{ $division->year_inicio }}"
                        data-final="{{ $division->year_final }}">
                    <i class="bi bi-pencil-square"></i>
                </button>

                <form action="#" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('¿Estás seguro de que deseas eliminar esta división?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-muted">No hay divisiones registradas.</td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal CREAR --}}
    <div class="modal fade" id="modalDivision" tabindex="-1" aria-labelledby="modalDivisionLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-center fw-bold text-success w-100 mb-3" id="modalDivisionLabel">
                        Crear Nueva División
                    </h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="#">
                        @csrf
                        <div class="mb-3">
                            <label for="division" class="form-label">Division<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="division" name="division" required>
                        </div>
                        <div class="mb-3">
                            <label for="year_inicio" class="form-label">Year_Inicio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="year_inicio" name="year_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="year_final" class="form-label">Year_Final <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="year_final" name="year_final" required>
                        </div>

                        <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success rounded-pill">Guardar División</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal EDITAR --}}
    <div class="modal fade" id="modalEditarDivision" tabindex="-1" aria-labelledby="modalEditarDivisionLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title text-center fw-bold text-primary w-100 mb-3" id="modalEditarDivisionLabel">
                        Editar División
                    </h5>
                    <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="#">
                        @csrf
                        <div class="mb-3">
                            <label for="division" class="form-label">División<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="division" name="division" required>
                        </div>
                        <div class="mb-3">
                            <label for="year_inicio" class="form-label">Year_Inicio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="year_inicio" name="year_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="year_final" class="form-label">Year_Final <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="year_final" name="year_final" required>
                        </div>

                        <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-pill">Actualizar División</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Editar --}}
    <script>
document.getElementById('modalEditarDivision').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const modal = this;

    modal.querySelector('#division').value = button.getAttribute('data-division');
    modal.querySelector('#year_inicio').value = button.getAttribute('data-inicio');
    modal.querySelector('#year_final').value = button.getAttribute('data-final');
    modal.querySelector('#formEditarDivision').action = `/divisiones/${button.getAttribute('data-id')}`;
});
</script>

@endsection