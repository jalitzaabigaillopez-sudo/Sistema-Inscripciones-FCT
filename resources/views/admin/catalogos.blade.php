@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">Catálogos Principales</h2>

  {{-- Navegación por pestañas --}}
  <ul class="nav nav-tabs mb-3" id="catalogoTabs" role="tablist">
    @foreach(['Eventos','Usuarios','Academias','Grados','Pesos','Modalidades','Atletas','Inscripciones'] as $index => $cat)
    <li class="nav-item" role="presentation">
      <button class="nav-link @if($index === 0) active @endif" id="{{ strtolower($cat) }}-tab" data-bs-toggle="tab" data-bs-target="#{{ strtolower($cat) }}" type="button" role="tab">
        {{ $cat }}
      </button>
    </li>
    @endforeach
  </ul>

  {{-- Contenido de cada catálogo --}}
  <div class="tab-content" id="catalogoTabsContent">
    @foreach(['Eventos','Usuarios','Academias','Grados','Pesos','Modalidades','Atletas','Inscripciones'] as $index => $cat)
    <div class="tab-pane fade @if($index === 0) show active @endif" id="{{ strtolower($cat) }}" role="tabpanel">
      <div class="card mb-4">
        <div class="card-header bg-light">
          Catálogo de {{ $cat }}
        </div>
        <div class="card-body">
          {{-- Buscador --}}
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Buscar en {{ $cat }}">
            <button class="btn btn-outline-primary">Buscar</button>
          </div>

          {{-- Tabla de ejemplo --}}
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {{-- Datos dinámicos --}}
              <tr>
                <td>1</td>
                <td>{{ $cat }} de ejemplo</td>
                <td>Descripción breve</td>
                <td><span class="badge bg-success">Activo</span></td>
                <td>
                  <button class="btn btn-sm btn-warning">Editar</button>
                  <button class="btn btn-sm btn-danger">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>

          {{-- Botón para agregar nuevo --}}
          <div class="text-end mt-3">
            <button class="btn btn-success">Agregar nuevo {{ strtolower($cat) }}</button>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection
