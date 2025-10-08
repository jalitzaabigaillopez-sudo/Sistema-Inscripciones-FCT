@extends('app')

@section('tituloArriba', 'Editar Atleta')
@section('breadcrumb-title', 'Editar Atleta')

@section('content')
<div class="container py-4">
  <h4 class="mb-3">Editar Atleta</h4>

  <form action="{{ route('atletas.update', $atleta) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" name="nombre" value="{{ $atleta->nombre }}" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Primer Apellido</label>
      <input type="text" name="primer_apellido" value="{{ $atleta->primer_apellido }}" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Sexo</label>
      <select name="sexo" class="form-select" required>
        <option value="Masculino" @selected($atleta->sexo=='Masculino')>Masculino</option>
        <option value="Femenino" @selected($atleta->sexo=='Femenino')>Femenino</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Edad</label>
      <input type="number" name="edad" value="{{ $atleta->edad }}" class="form-control" required>
    </div>
    {{-- agrega los demás campos --}}
    <button type="submit" class="btn btn-warning">Actualizar</button>
    <a href="{{ route('atletas.index') }}" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
@endsection
