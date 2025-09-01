/**
 * ❗logic with memory reset❗
 */

$(document).ready(function () {


    $('#modalidades-select').on('change', function () {
        var idSeleccionado = $(this).val();
        // var texto = $(this).find('option:selected').text(); // El texto visible

        $.ajax({
            url: '/obtenerSubModalidades',
            method: 'POST',
            dataType: 'json',
            data: {
                id_modalidad: idSeleccionado,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                // console.log(res);
                // limpiar el select de submodalidades
                var select = $('#subModalidades-select');
                select.empty();

                // agregar opción inicial vacía
                select.append('<option value="">Seleccione una submodalidad</option>');

                // recorrer el arreglo y agregar opciones
                res.forEach(function (item) {
                    select.append('<option value="' + item.id_subModalidad + '">' + item.nombre + '</option>');
                });
            },
            error: function (xhr, status, error) {
                // console.log("Error:", error);
                // console.log("Detalle:", xhr.responseText);
                alert("Ocurrió un error en la petición");
            }
        });
    });


    $('#subModalidades-select').on('change', function () {
        var texto = $(this).find('option:selected').text(); 
        var label = $('#labelSubModalidad');
        label.empty();
        label.append(texto);
    });
});