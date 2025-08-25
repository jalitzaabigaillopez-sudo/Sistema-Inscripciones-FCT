$(document).ready(function () {
    // Activar barra de búsqueda en el select
    $('#formSelect').select2({
        placeholder: "Buscar opción...",
        allowClear: true
    });

    $('#formSelect').on('change', function () {
        var opcion = $(this).val();
        var id_academia = $('#id_academia').val();

        // Ocultar todos los formularios
        $('.formulario').addClass('d-none');

        if (opcion) {
            // Mostrar solo el formulario correspondiente
            $('#form-' + opcion).removeClass('d-none');

            // Lanzar AJAX según la opción elegida
            $.ajax({
                url: '/obtenerAtletasPorRol',
                method: 'POST',
                dataType: 'json',
                data: {
                    tipo: opcion,
                    id_academia: id_academia,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    var selectId = '#select-' + opcion;
                    var select = $(selectId);

                    select.empty();
                    select.append('<option value="">Seleccione...</option>');

                    res.forEach(function (item) {
                        select.append('<option value="' + item.nombre + '">' + item.nombre + '</option>');
                    });
                }
            });
        }
    });
});