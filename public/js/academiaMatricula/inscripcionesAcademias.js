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
    // ⬅️Delegación de eventos para los selects de atletas
    $(document).on('change', '#atletas-select', function () {
        var selected = $(this).find('option:selected');
        var panel = $(this).closest('.card-body');

        var sexo = selected.data('sexo') || '';
        var fecha = selected.data('fecha_nacimiento') || '';
        var rol = selected.data('rol') || '';
        var id_atleta = selected.data('id_atleta');

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
                var select = panel.find('#categorias-select'); // 🔹 busca solo dentro del panel actual
                select.empty();

                select.append('<option value="">Seleccione una categoria</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_categoria + '" data-min="' + item.peso_min + '" data-max="' + item.peso_max + '">' + 'Peso minimo: ' + item.peso_min + ' - Peso maximo: ' + item.peso_max + '</option>');
                });
            },
            error: function (xhr, status, error) {
                alert("Lo sentimos, al parecer a ocurrido un error al cargar las categorias.");
            }
        });
    });

    // ⬅️Delegación de eventos para los selects de submodalidades
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

            // Limpiar inputs
            nuevaCard.find('input').val('');

            // Modalidades: copiar opciones del padre, asignar valor y deshabilitar
            var selectModalidadesOriginal = panelOriginal.find('#modalidades-select');
            var selectModalidadesCopia = nuevaCard.find('#modalidades-select');
            selectModalidadesCopia.html(selectModalidadesOriginal.html()); // copiar todas las opciones
            selectModalidadesCopia.val(selectModalidadesOriginal.val()).prop('disabled', true);

            // Submodalidades: igual
            var selectSubModalidadesOriginal = panelOriginal.find('#submodalidades-select');
            var selectSubModalidadesCopia = nuevaCard.find('#submodalidades-select');
            selectSubModalidadesCopia.html(selectSubModalidadesOriginal.html());
            selectSubModalidadesCopia.val(selectSubModalidadesOriginal.val()).prop('disabled', true);

            var selectCategorias = nuevaCard.find('#categorias-select');
            selectCategorias.empty(); // limpia opciones
            selectCategorias.append('<option value="">Seleccione una categoría</option>');

            // Agregar al contenedor
            contenedor.append(nuevaCard);
        }

        permitirClonar = false;
    });

    // ⬅️ Delegación de eventos para todos los selects de categorias
    $(document).on('change', '#categorias-select', function () {
        // Buscar el panel contenedor de este select (puede ser el padre o un clon)
        var panel = $(this).closest('.card-body');

        // Opción seleccionada
        var opcionSeleccionada = $(this).find('option:selected');

        // Tomar el input de peso solo dentro del panel
        var peso = panel.find('#inputPeso').val();
        var peso_min = opcionSeleccionada.data('min');
        var peso_max = opcionSeleccionada.data('max');

        if (!(peso >= peso_min && peso <= peso_max)) {
            alert("Verifique que su peso esté en el rango seleccionado");

            // Resetear solo el select actual
            $(this).prop("selectedIndex", 0);
        }
    });


    // //=========================== SELECT DE ATLETAS ===========================
    // $('#atletas-select').on('change', function () {
    //     // Obtiene la opción seleccionada
    //     var opcionSeleccionada = $(this).find('option:selected');

    //     // Obtiene el data-rol
    //     var sexo = opcionSeleccionada.data('sexo');
    //     var fecha_nacimiento = opcionSeleccionada.data('fecha_nacimiento');
    //     var rol = opcionSeleccionada.data('rol');
    //     var id_atleta = opcionSeleccionada.data('id_atleta');

    //     $('#inputSexo').val(sexo);
    //     $('#inputEdad').val(calcularEdad(fecha_nacimiento) + " años");
    //     $('#inputRol').val(rol);

    //     $.ajax({
    //         url: '/obtenerCategorias',
    //         method: 'POST',
    //         dataType: 'json',
    //         data: {
    //             id_atleta: id_atleta,
    //             _token: $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function (res) {
    //             var select = $('#categorias-select');
    //             select.empty();

    //             select.append('<option value="">Seleccione una categoria</option>');

    //             res.forEach(function (item) {
    //                 select.append('<option value="' + item.id_categoria + '" data-min="' + item.peso_min + '" data-max="' + item.peso_max + '">' + 'Peso minimo: ' + item.peso_min + ' - Peso maximo: ' + item.peso_max + '</option>');
    //             });
    //         },
    //         error: function (xhr, status, error) {
    //             alert("Lo sentimos, al parecer a ocurrido un error al cargar las categorias.");
    //         }
    //     });
    // });

    //=========================== SELECT DE CATEGORIAS ===========================
    /*$('#categorias-select').on('change', function () {
        // Obtiene la opción seleccionada
        var opcionSeleccionada = $(this).find('option:selected');
        var peso = $('#inputPeso').val();
        var peso_min = opcionSeleccionada.data('min');
        var peso_max = opcionSeleccionada.data('max');
        if (!(peso >= peso_min && peso <= peso_max)) {
            alert("Verifique que su peso este en el rango seleccionado");
            $("#categorias-select").prop("selectedIndex", 0);
        }

    });*/


    //=========================== FUNCIONES INDEPENDIENTES ===========================
    function calcularEdad(fechaNacimiento) {
        var yearNacimiento = parseInt(fechaNacimiento.split('-')[0]);
        // Obtener el año actual
        var yearActual = new Date().getFullYear();
        var edad = yearActual - yearNacimiento;
        return edad;
    }
});