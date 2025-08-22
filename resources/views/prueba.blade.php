<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Registro</title>
<!-- Opcional: Bootstrap v3.x si ya lo usas en tu proyecto -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
body { background: #f7f7f9; }
.panel { margin-top: 40px; }
.help { font-size: 12px; color: #777; }
.required::after { content: " *"; color: #d9534f; }
</style>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Asignar Categoria</h3>
</div>
<div class="panel-body">
<form id="consultaCedulaForm" onsubmit="return false;">

<div class="form-group">
<label for="sexoSelect">Sexo</label>
<select class="form-control" id="sexoSelect" name="sexo">
<option value="">Seleccione...</option>
<option value="Masculino">Masculino</option>
<option value="Femenino">Femenino</option>
</select>
</div>


<div class="form-group">
<label for="cedulaInput" class="required">Cédula</label>
<input
type="text"
class="form-control"
id="cedulaInput"
name="cedula"
placeholder="Ej: 1-2345-6789"
autocomplete="off"
required
pattern="^[0-9]{1,2}-?[0-9]{3,6}-?[0-9]{1,3}$"
title="Use solo números y guiones (formato típico: 1-2345-6789)"
/>
<p class="help">Formato sugerido CR: 1-2345-6789 (guiones opcionales).</p>
</div>


<div class="form-group">
<label for="nombreInput">Nombre</label>
<input
type="text"
class="form-control"
id="nombreInput"
name="nombre"
placeholder="El nombre aparecerá aquí"
readonly
/>
</div>

<div class="form-group">
  <label for="fechaInput">Fecha de Nacimiento</label>
  <input
    type="text"
    class="form-control"
    id="fechaInput"
    name="fecha_nacimiento"
    placeholder="La fecha aparecerá aquí"
    readonly
  />
</div>

<div class="form-group">
  <label for="divisionInput">Categoria</label>
  <input
    type="text"
    class="form-control"
    id="divisionInput"
    name="division"
    placeholder="La division aparecerá aquí"
    readonly
  />
</div>

<div class="form-group">
<label for="pesoSelect">Seleccione rango de peso</label>
<select class="form-control" id="pesoSelect" name="peso">
<option value="">Seleccione...</option>
<!-- <option value="Masculino">Masculino</option>
<option value="Femenino">Femenino</option> -->
</select>
</div>

<div class="text-right">
<button type="button" id="btnGuardar" class="btn btn-primary">
Guardar
</button>
<button type="reset" class="btn btn-default" id="btnLimpiar">Limpiar</button>
</div>
</form>
</div>
</div>


<div id="mensaje" class="alert" style="display:none;"></div>
</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function mostrarMensaje(tipo, texto) {
    var msg = document.getElementById('mensaje');
    msg.className = 'alert alert-' + tipo;
    msg.textContent = texto;
    msg.style.display = 'block';
  }

  function ocultarMensaje() {
    var msg = document.getElementById('mensaje');
    msg.style.display = 'none';
    msg.textContent = '';
    msg.className = 'alert';
  }

  // Evento: cuando el usuario escribe en el campo de cédula
  $('#cedulaInput').on('input', function() {
    var cedula = $(this).val().trim();
    var sexo = $('#sexoSelect').val();

    var nombreInput = $('#nombreInput');
    var fechaInput = $('#fechaInput');
    var divisionInput = $('#divisionInput');

    if (cedula.length === 9 && sexo !== "") {
      ocultarMensaje();
      nombreInput.val('');
      fechaInput.val('');
      divisionInput.val('');

      $.ajax({
        url: '/buscar-datos',
        method: 'POST',
        dataType: 'json',
        data: {
            cedula: $('#cedulaInput').val(),
            sexo: $('#sexoSelect').val(),
            _token: $('meta[name="csrf-token"]').attr('content') 
        },
        success: function(res) {
          if (res && res.nombre) {
            nombreInput.val(res.nombre + " " + res.primer_apellido+ " " + res.segundo_apellido);
            fechaInput.val(res.fecha_nacimiento);
            divisionInput.val(res.division);

            console.log(res.pesos);
            var select = $('#pesoSelect');
            res.pesos.forEach(function(item) {
            select.append('<option value="' + item.id + '">' + "Peso minimo: " + item.peso_min + " Peso maximo: " + item.peso_max + '</option>');
            });
            mostrarMensaje('success', 'Consulta exitosa.');
          } else {
            mostrarMensaje('info', 'No se encontraron datos para esa cédula.');
          }
        },
        error: function() {
          mostrarMensaje('danger', 'Error en la consulta. Intente de nuevo.');
        }
      });
    } else {
      nombreInput.val('');
      fechaInput.val('');
      divisionInput.val('');
      ocultarMensaje();
    }
  });

  // Botón limpiar
  $('#btnLimpiar').on('click', function() {
    ocultarMensaje();
    $('#nombreInput').val('');
    $('#fechaInput').val('');
    $('#cedulaInput').focus();
  });
</script>
</body>

</html>