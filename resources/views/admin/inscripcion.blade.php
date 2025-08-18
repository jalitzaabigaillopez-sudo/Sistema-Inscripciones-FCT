@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">Formulario de Inscripción a Eventos</h2>

  {{-- Selección de Evento --}}
  <div class="mb-4">
    <label for="eventoSelect" class="form-label">Selecciona un evento</label>
    <select id="eventoSelect" class="form-select">
      <option selected disabled>-- Selecciona --</option>
      {{-- @foreach($eventos as $evento) --}}
      {{-- <option value="{{ $evento->id }}">{{ $evento->nombre }}</option> --}}
      {{-- @endforeach --}}
    </select>
  </div>

  {{-- Datos de la Academia --}}
  <div class="card mb-4">
    <div class="card-header">Datos de la Academia</div>
    <div class="card-body">
      <input type="text" class="form-control mb-3" placeholder="Nombre de la Academia">
      <input type="text" class="form-control" placeholder="Nombre del Encargado">
    </div>
  </div>

  {{-- Entrenadores --}}
  <div class="card mb-4">
    <div class="card-header">Entrenadores</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="Cédula">
        </div>
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="Nombre completo">
        </div>
      </div>
      <button class="btn btn-sm btn-outline-primary mt-3">Agregar otro entrenador</button>
    </div>
  </div>

  {{-- Atletas --}}
  <div class="card mb-4">
    <div class="card-header">Atletas</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <input type="text" class="form-control" placeholder="Cédula">
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control" placeholder="Nombre completo">
        </div>
        <div class="col-md-4">
          <input type="number" class="form-control" placeholder="Edad">
        </div>
      </div>

      <div class="row g-3 mt-3">
        <div class="col-md-3">
          <select class="form-select">
            <option selected disabled>Categoría</option>
            {{-- Opciones dinámicas --}}
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select">
            <option selected disabled>Cinturón</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select">
            <option selected disabled>Género</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="number" class="form-control" placeholder="Peso (kg)">
        </div>
      </div>

      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <label class="form-label">Modalidad</label>
          <select class="form-select">
            <option selected disabled>Selecciona modalidad</option>
            <option value="combate">Combate</option>
            <option value="tk13">TK13</option>
            <option value="poomsae">Poomsae</option>
            <option value="freestyle">Freestyle</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Tipo de participación</label>
          <select class="form-select">
            <option selected disabled>Selecciona tipo</option>
            <option value="individual">Individual</option>
            <option value="pareja">Pareja</option>
            <option value="equipo">Equipo</option>
          </select>
        </div>
      </div>

      <button class="btn btn-sm btn-outline-success mt-3">Agregar otro atleta</button>
    </div>
  </div>

  {{-- Categorías por peso --}}
  <div class="card mb-4">
    <div class="card-header">Categorías por Peso</div>
    <div class="card-body">
      <select class="form-select">
        <option selected disabled>Selecciona categoría</option>
        {{-- Categorías dinámicas con rangos de peso --}}
      </select>
      <div class="form-text mt-2">El sistema verificará automáticamente si el peso está dentro del rango permitido.</div>
    </div>
  </div>

  {{-- Estado y fecha límite --}}
  <div class="mb-4">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="estadoActivo" checked>
      <label class="form-check-label" for="estadoActivo">Inscripción activa</label>
    </div>
    <div class="alert alert-warning mt-3">
      <strong>Fecha límite:</strong> 25 de agosto de 2025. Después de esta fecha no se permitirán modificaciones.
    </div>
  </div>

  {{-- Botones --}}
  <div class="d-flex justify-content-end">
    <button class="btn btn-primary me-2">Guardar inscripción</button>
    <button class="btn btn-secondary">Cancelar</button>
  </div>
</div>
@endsection
