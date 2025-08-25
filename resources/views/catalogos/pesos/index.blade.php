@extends('app')

@section('content')
<a href="{{ route('adminDash') }}" class="btn btn-outline-primary float-end">
    Volver al Dashboard
</a>
<div class="container mt-4">
    <h2 class="mb-4">Listado de Pesos</h2>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalPeso">
        ➕ Nuevo Peso
    </button>

    <div class="modal fade" id="modalPeso" tabindex="-1" aria-labelledby="modalPesoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPesoLabel">Crear Nuevo Peso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="division" class="form-label">División</label>
                            <select class="form-select" id="division">
                                <option value="Cadete">Cadete</option>
                                <option value="Junior">Junior</option>
                                <option value="Senior">Senior</option>
                                <option value="Ejecutivo">Ejecutivo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sexo" class="form-label">Sexo</label>
                            <select class="form-select" id="sexo">
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="peso_min" class="form-label">Peso Mínimo</label>
                            <input type="number" class="form-control" id="peso_min">
                        </div>
                        <div class="mb-3">
                            <label for="peso_max" class="form-label">Peso Máximo</label>
                            <input type="number" class="form-control" id="peso_max">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">💾 Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>División</th>
                <th>Sexo</th>
                <th>Peso Mínimo</th>
                <th>Peso Máximo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de fila -->
            <tr>
                <td>1</td>
                <td>Junior</td>
                <td>Masculino</td>
                <td>60</td>
                <td>70</td>
                <td>
                    <a href="{{ route('pesos.show', 1) }}" class="btn btn-sm btn-info">👁️ Ver</a>
                    <a href="{{ route('pesos.edit', 1) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection


