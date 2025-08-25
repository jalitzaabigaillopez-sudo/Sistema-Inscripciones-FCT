@extends('app')

@section('content')

<!-- Modal -->
<div class="modal fade" id="crearModalidad" tabindex="-1" aria-labelledby="crearModalidadLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title" id="crearModalidadLabel">Crear Nueva Modalidad</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" required>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" id="descripcion" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" class="form-select" id="estado" required>
              <option selected disabled>Seleccionar...</option>
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success">💾 Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
