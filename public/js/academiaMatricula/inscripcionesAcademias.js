$(document).ready(function () {

    // VARIABLES DE CONTROL


    //=========================== SELECT DE EVENTOS ===========================
    $('#evento-select').on('change', function () {
        var id_evento = $(this).val();

        $.ajax({
            url: '/obtenerModalidades',
            method: 'POST',
            dataType: 'json',
            data: {
                id_evento: id_evento,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                var select = $('#modalidades-select');
                select.empty();

                select.append('<option value="">Seleccione una modalidad</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_modalidad + '">' + item.nombre + '</option>');
                });
            },
            error: function (xhr, status, error) {
                alert("Lo sentimos, al parecer a ocurrido un error al cargar las modalidades.");
            }
        });
    });

    //=========================== SELECT DE MODALIDADES ===========================
    $('#modalidades-select').on('change', function () {
        var id_modalidad = $(this).val();

        $.ajax({
            url: '/obtenerSubModalidades',
            method: 'POST',
            dataType: 'json',
            data: {
                id_modalidad: id_modalidad,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                var select = $('#submodalidades-select');
                select.empty();

                select.append('<option value="">Seleccione una submodalidad</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_subModalidad + '" data-cantidad-atletas="' + item.cantidad_atletas + '">' + item.nombre + '</option>');
                });
            },
            error: function (xhr, status, error) {
                alert("Lo sentimos, al parecer a ocurrido un error al cargar las modalidades.");
            }
        });
    });


    //=========================== SELECT DE SUB-MODALIDADES ===========================
    // Delegación de eventos para los selects de atletas
    $(document).on('change', '#atletas-select', function () {
        var selected = $(this).find('option:selected');
        var panel = $(this).closest('.card-body');

        var sexo = selected.data('sexo') || '';
        var fecha = selected.data('fecha_nacimiento') || '';
        var rol = selected.data('rol') || '';

        // Calcular edad
        var edad = '';
        if (fecha) {
            var anio = parseInt(fecha.split('-')[0]);
            var currentYear = new Date().getFullYear();
            edad = currentYear - anio;
        }

        panel.find('#inputSexo').val(sexo);
        panel.find('#inputEdad').val(edad);
        panel.find('#inputRol').val(rol);

        $.ajax({
            url: '/obtenerCategorias',
            method: 'POST',
            dataType: 'json',
            data: {
                id_atleta: id_atleta,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                var select = $('#categorias-select');
                select.empty();

                select.append('<option value="">Seleccione una categoria</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_categoria + '">' + 'Peso minimo: ' + item.peso_min + ' - Peso maximo: ' + item.peso_max + '</option>');
                });
            },
            error: function (xhr, status, error) {
                alert("Lo sentimos, al parecer a ocurrido un error al cargar las categorias.");
            }
        });
    });

    // Delegación de eventos para los selects de submodalidades
    $(document).on('change', '#submodalidades-select', function () {
        var opcion = $(this).find('option:selected');
        var cantidad_atletas = opcion.data('cantidad-atletas') || 1;

        var panelOriginal = $('#panelRegistro'); // primer panel visible
        var contenedor = $('#contenedor');

        // Limpiar copias previas
        contenedor.empty();

        // Crear N-1 copias limpias
        for (let i = 1; i < cantidad_atletas; i++) {
            var nuevaCard = panelOriginal.clone().removeAttr('id');

            // Limpiar inputs y selects
            nuevaCard.find('input').val('');

            var selectOriginal = panelOriginal.find('#modalidades-select');
            var selectCopia = nuevaCard.find('#modalidades-select');
            selectCopia.val(selectOriginal.val());//cargar valor original
            selectCopia.prop('disabled', true);//desabilitar

            var selectOriginal = panelOriginal.find('#submodalidades-select');
            var selectCopia = nuevaCard.find('#submodalidades-select');
            selectCopia.val(selectOriginal.val());//cargar valor original
            selectCopia.prop('disabled', true);//desabilitar

            contenedor.append(nuevaCard);
        }

        permitirClonar = false;
    });


    //=========================== SELECT DE ATLETAS ===========================
    $('#atletas-select').on('change', function () {
        // Obtiene la opción seleccionada
        var opcionSeleccionada = $(this).find('option:selected');

        // Obtiene el data-rol
        var sexo = opcionSeleccionada.data('sexo');
        var fecha_nacimiento = opcionSeleccionada.data('fecha_nacimiento');
        var rol = opcionSeleccionada.data('rol');
        var id_atleta = opcionSeleccionada.data('id_atleta');

        $('#inputSexo').val(sexo);
        $('#inputEdad').val(calcularEdad(fecha_nacimiento) + " años");
        $('#inputRol').val(rol);

        $.ajax({
            url: '/obtenerCategorias',
            method: 'POST',
            dataType: 'json',
            data: {
                id_atleta: id_atleta,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                var select = $('#categorias-select');
                select.empty();

                select.append('<option value="">Seleccione una categoria</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_categoria + '">' + 'Peso minimo: ' + item.peso_min + ' - Peso maximo: ' + item.peso_max + '</option>');
                });
            },
            error: function (xhr, status, error) {
                alert("Lo sentimos, al parecer a ocurrido un error al cargar las categorias.");
            }
        });
    });

    function calcularEdad(fechaNacimiento) {

        var yearNacimiento = parseInt(fechaNacimiento.split('-')[0]);

        // Obtener el año actual
        var yearActual = new Date().getFullYear();

        var edad = yearActual - yearNacimiento;

        return edad;
    }
});