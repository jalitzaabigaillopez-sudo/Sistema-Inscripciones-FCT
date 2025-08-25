@extends('app')

@section('content')
<a href="{{ route('adminDash') }}" class="btn btn-outline-primary float-end">
    Volver al Dashboard
</a>

<div class="container mt-4">
    <h2 class="mb-4">Listado de Academias</h2>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAcademia">
    ➕ Nueva Academia</button>
    <div class="modal fade" id="modalAcademia" tabindex="-1" aria-labelledby="modalAcademiaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAcademiaLabel">Crear Nueva Academia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="nombreAcademia" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombreAcademia" placeholder="Academia Central">
          </div>
          <div class="mb-3">
            <label for="direccionAcademia" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccionAcademia" placeholder="Siquirres, Limón">
          </div>
          <div class="mb-3">
            <label for="ProfesorAcademia" class="form-label">Profesor encargado</label>
            <input type="text" class="form-control" id="ProfesorAcademia" placeholder="Nombre del profesor encargado">
          </div>
          <div class="mb-3">
            <label for="telefonoAcademia" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefonoAcademia" placeholder="+506 8888-8888">
          </div>
        </form>
      </div>
      <div class="mb-3">
  <label for="correoAcademia" class="form-label">Correo electrónico</label>
  <input type="email" class="form-control" id="correoAcademia" placeholder="academia@email.com">
</div>

<div class="mb-3">
  <label for="estadoAcademia" class="form-label">Estado</label>
  <select class="form-select" id="estadoAcademia">
    <option selected disabled>Selecciona el estado</option>
    <option value="activo">Activo</option>
    <option value="inactivo">Inactivo</option>
    <option value="pendiente">Pendiente</option>
  </select>
</div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Guardar Academia</button>
      </div>
    </div>
  </div>
</div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Profesor encargado</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Academia Central</td>
                    <td>Siquirres</td>
                    <td>Profesor 1</td>
                    <td>+506 8888-8888</td>
                    <td>academia@email.com</td>
                    <td>Activo</td>
                    <td>
                        <a href="{{ route('academias.show') }}" class="btn btn-sm btn-info">👁️ Ver</a>
                        <a href="{{ route('academias.edit') }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                    </td>
                </tr>
                 <tr>
                    <td>1</td>
                    <td>Academia Central</td>
                    <td>Siquirres</td>
                    <td>Profesor 1</td>
                    <td>+506 8888-8888</td>
                    <td>academia@email.com</td>
                    <td>Activo</td>
                    <td>
                        <a href="{{ route('academias.show') }}" class="btn btn-sm btn-info">👁️ Ver</a>
                        <a href="{{ route('academias.edit') }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                    </td>
                </tr>
                 <tr>
                    <td>1</td>
                    <td>Academia Central</td>
                    <td>Siquirres</td>
                    <td>Profesor 1</td>
                    <td>+506 8888-8888</td>
                    <td>academia@email.com</td>
                    <td>Activo</td>
                    <td>
                        <a href="{{ route('academias.show') }}" class="btn btn-sm btn-info">👁️ Ver</a>
                        <a href="{{ route('academias.edit') }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
