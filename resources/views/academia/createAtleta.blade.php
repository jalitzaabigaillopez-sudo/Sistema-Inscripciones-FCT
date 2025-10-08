@extends('academia')

@section('tituloArriba', 'Nuevo Atleta')
@section('breadcrumb-title', 'Registrar Atleta')

@section('content')
<div class="container py-4">
  <h4 class="mb-3">Registrar Nuevo Atleta</h4>

  <form action="{{ route('atletas.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Primer Apellido</label>
      <input type="text" name="primer_apellido" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Sexo</label>
      <select name="sexo" class="form-select" required>
        <option value="">Seleccione...</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Edad</label>
      <input type="number" name="edad" class="form-control" required>
    </div>
    {{-- agrega los demás campos --}}
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="{{ route('atletas.index') }}" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
@endsection
