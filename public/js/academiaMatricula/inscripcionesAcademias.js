/*
   ╔════════════════════════════════════════════════════════════════╗
   ║   Proyecto: FCT                                                ║ 
   ║   Archivo: inscripcionesAcademias.js                           ║ 
   ║   Descripción: Contiene la lógica principal de inscripciones   ║
   ║   Version: 1                                                   ║ 
   ║   Autor: John Chaves Canales                                   ║ 
   ║   Fecha: 2025-09-04                                            ║
   ╚════════════════════════════════════════════════════════════════╝
*/

$(document).ready(function () {
    /**
     * VARIABLES DE CONTROL
     */
    let editMode = false;

    //================================== MEMORY ACCESS =======================================
    let listaAtletas = [];
    let gruposAtletas = [];
    let atletasModificar = []
    let tempx = [];


    let btn_edit_code = "";

    let edicionGrupo_modo_p = false;
    let edicionGrupal_modo_ = false;
    let edicionIndividual_modo_ = false;

    $('.atletas-select').select2({
        placeholder: "Selecciona un atleta",
        // allowClear: true,
        width: '100%' // mantiene el ancho como .form-control
    });

    //=========================== SELECT DE EVENTOS ===========================
    $('#evento-select').on('change', function () {
        if ($(this).val() !== "") {
            $("#panelRegistro").show();
            $("#containerButton").show();
        } else {
            $("#panelRegistro").hide();
            $("#containerButton").hide();
        }

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
                var select = $('.modalidades-select');
                select.empty();

                select.append('<option value="">Seleccione una modalidad</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_modalidad + '" data-nombre="' + item.nombre + '">' + item.nombre + '</option>');
                });
            },
            error: function (xhr, status, error) {
                mostrarAlerta("Lo sentimos, al parecer a ocurrido un error al cargar las modalidades", "Aviso", "⚠️");
            }
        });
    });

    //=========================== SELECT DE MODALIDADES ===========================

    // ⬅️Delegación de eventos sobre el select del panel principal
    $(document).on('change', '.modalidades-select', function () {
        var card = $(this).closest('.card');
        var select = card.find('.submodalidades-select');
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
                select.empty();
                select.append('<option value="">Seleccione una submodalidad</option>');

                res.forEach(function (item) {
                    select.append(
                        '<option value="' + item.id_subModalidad + '" ' +
                        'data-nombre="' + item.nombre + '" ' +
                        'data-cantidad-atletas="' + item.cantidad_atletas + '" ' +
                        'data-sexo-mixto="' + item.sexo_mixto + '">' + item.nombre + '</option>'
                    );
                });

                // Selección automática
                var nombreSub = card.data('nombre');
                if (nombreSub) {
                    let $opcion = select.find('option').filter(function () {
                        return $(this).data('nombre') == nombreSub;
                    });
                    if ($opcion.length) {
                        select.val($opcion.val()).trigger("change");
                    }
                }
            },
            error: function () {
                mostrarAlerta("Lo sentimos, al parecer ocurrió un error al cargar las submodalidades", "Aviso", "⚠️");
            }
        });
    });

    //=========================== SELECT DE SUB-MODALIDADES ===========================

    // ⬅️Delegación de eventos sobre el select del panel principal
    $(document).on('change', '#panelRegistro #atletas-select', function () {
        var textoSeleccionado = $(this).find('option:selected').text().toLowerCase().trim();

        // si contiene "entrenador" o "asistente"
        if (textoSeleccionado.includes('entrenador') || textoSeleccionado.includes('asistente')) {
            $('#contenedor').empty();
        }

        // Desbloquear selects y inputs
        $("#modalidades-select").prop("disabled", false);
        $("#submodalidades-select").prop("disabled", false);
        $(".categorias-select").prop("disabled", false);
        $("input[id='inputPeso']").prop("disabled", false);
    });

    // ⬅️Delegación de eventos para los selects de atletas
    $(document).on('change', '.atletas-select', function () {
        var selected = $(this).find('option:selected');
        var panel = $(this).closest('.card-body');

        var sexo = selected.data('sexo') || '';
        var fecha = selected.data('fecha_nacimiento') || '';
        var id_division = selected.data('id_division');

        // Calcular edad
        var edad = '';
        if (fecha) {
            var anio = parseInt(fecha.split('-')[0]);
            var currentYear = new Date().getFullYear();
            edad = currentYear - anio;
        }

        panel.find('.inputSexo').val(sexo);
        panel.find('.inputEdad').val(edad + " años");

        $.ajax({
            url: '/obtenerCategorias',
            method: 'POST',
            dataType: 'json',
            data: {
                id_division: id_division,
                sexo: sexo,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                var select = panel.find('.categorias-select'); // 🔹 busca solo dentro del panel actual🚩
                select.empty();

                select.append('<option value="">Seleccione una categoria</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_categoria + '" data-min="' + item.peso_min + '" data-max="' + item.peso_max + '">' + 'Peso minimo: ' + item.peso_min + ' - Peso maximo: ' + item.peso_max + '</option>');
                });
            },
            error: function (xhr, status, error) {
                mostrarAlerta("Lo sentimos, al parecer a ocurrido un error al cargar las categorias", "Aviso", "⚠️");
            }
        });
    });

    //=========================== SELECT DE ROL ===========================

    // ⬅️Delegación de rol sobre el select del panel principal
    $(document).on('change', '.rol-select', function () {
        var selected = $(this).find('option:selected').val();
        if (selected == 'entrenador' || selected == 'asistente') {
            $('.modalidades-select').prop("selectedIndex", 0);
            $('.submodalidades-select').prop("selectedIndex", 0);

            $('.baseCard, .clonEdit').each(function () {
                $(this).find('.modalidades-select').hide();
                $(this).find('.submodalidades-select').hide();
                $(this).find('.categorias-select').hide();
                $(this).find('#pesoInput').hide();
            });

            // 🔹 Eliminar paneles clones 👁️SOLO panelRegistro
            if ($(this).closest('#panelRegistro').length) {
                $("#contenedor .baseCard").remove();
                $("#contenedor .clonEdit").remove();
            }
        } else {
            // Mostrar de nuevo si no es rol especial
            $('.baseCard, .clonEdit').each(function () {
                $(this).find('.modalidades-select').show();
                $(this).find('.submodalidades-select').show();
                $(this).find('.categorias-select').show();
                $(this).find('#pesoInput').show();
            });
        }
    });

    // ⬅️Delegación de eventos para los selects de submodalidades
    $(document).on('change', '.submodalidades-select', function () {//@audit submodalidades-select
        var opcion = $(this).find('option:selected');
        var cantidad_atletas = opcion.data('cantidad-atletas') || 1;

        console.log("edicionGrupal_modo_: ", edicionGrupal_modo_);

        var panelOriginal = $('#panelRegistro');
        var contenedor = $('#contenedor');

        if (edicionIndividual_modo_ === false) {
            contenedor.find('.baseCard').not('.clonEdit').remove();
        } else {
            contenedor.find('.clonEdit').not(':first').remove();
        }


        // Eliminar Select2
        const select = panelOriginal.find('.atletas-select');
        if (select.data('select2')) select.select2('destroy');

        // Crear N-1 copias limpias
        for (let i = 1; i < cantidad_atletas; i++) {

            let nuevaCard;

            if (edicionIndividual_modo_ === false && edicionGrupal_modo_ === false) {
                nuevaCard = panelOriginal.clone().removeAttr('id');
                // 🔹 Limpiar inputs editables
                nuevaCard.find('input').val('');
                ddd(nuevaCard, panelOriginal, contenedor)
                console.log("_1");
            } else if (edicionGrupal_modo_ === false) {
                nuevaCard = panelOriginal.clone();
                nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                    "data-code": "",
                    "data-grupo": ""
                });
                // 🔹 Limpiar inputs editables
                nuevaCard.find('input').val('');
                ddd(nuevaCard, panelOriginal, contenedor)
                console.log("_2");
            }


        }

        panelOriginal.find('.atletas-select').select2({
            placeholder: "Selecciona un atleta",
            width: '100%'
        });

        // actualizarAtletasEnClones();
        // //Hace que solo aparescan atletas en los cards clones
        // $("#contenedor .baseCard .atletas-select").each(function () {
        //     actualizarAtletasEnClones();
        // });

        permitirClonar = false;
    });

    function ddd(nuevaCard, panelOriginal, contenedor) {
        var selectRolCopia = nuevaCard.find('.rol-select');
        selectRolCopia.empty();
        selectRolCopia.append(
            $('<option>', {
                value: 'atleta',
                text: 'Atleta'
            })
        );
        selectRolCopia.prop('disabled', true);

        var selectRolOriginal = panelOriginal.find('.rol-select');
        selectRolOriginal.prop('selectedIndex', 1);


        if (edicionIndividual_modo_ === false) {
            // 🔹 Modalidades (heredadas y bloqueadas)
            var selectModalidadesOriginal = panelOriginal.find('.modalidades-select');
            var selectModalidadesCopia = nuevaCard.find('.modalidades-select');
            selectModalidadesCopia.html(selectModalidadesOriginal.html());
            selectModalidadesCopia.val(selectModalidadesOriginal.val());
            selectModalidadesCopia.prop('disabled', true);

            // 🔹 Submodalidades (heredadas y bloqueadas)
            var selectSubModalidadesOriginal = panelOriginal.find('.submodalidades-select');
            var selectSubModalidadesCopia = nuevaCard.find('.submodalidades-select');
            selectSubModalidadesCopia.html(selectSubModalidadesOriginal.html());
            selectSubModalidadesCopia.val(selectSubModalidadesOriginal.val());
            selectSubModalidadesCopia.prop('disabled', true);
        } else {
            var primerCard = contenedor.find('.clonEdit').first();

            var selectModalidadesOriginal = primerCard.find('.modalidades-select');
            var selectModalidadesCopia = nuevaCard.find('.modalidades-select');
            selectModalidadesCopia.html(selectModalidadesOriginal.html());
            selectModalidadesCopia.val(selectModalidadesOriginal.val());
            selectModalidadesCopia.prop('disabled', true);

            var selectSubModalidadesOriginal = primerCard.find('.submodalidades-select');
            var selectSubModalidadesCopia = nuevaCard.find('.submodalidades-select');
            selectSubModalidadesCopia.html(selectSubModalidadesOriginal.html());
            selectSubModalidadesCopia.val(selectSubModalidadesOriginal.val());
            selectSubModalidadesCopia.prop('disabled', true);
        }



        // 🔹 Categorías (vacías y editables por el usuario)
        var selectCategorias = nuevaCard.find('.categorias-select');
        selectCategorias.empty();
        selectCategorias.append('<option value="">Seleccione una categoría</option>');
        selectCategorias.prop('disabled', false);

        nuevaCard.show();
        contenedor.append(nuevaCard);

        // Inicializar Select2
        nuevaCard.find('.atletas-select').select2({ placeholder: "Selecciona un atleta", width: '100%' });
    }

    // ⬅️ Delegación de eventos para todos los selects de categorias
    $(document).on('change', '.categorias-select', function () {
        // Buscar el panel contenedor de este select (puede ser el padre o clon)
        var panel = $(this).closest('.card-body');

        var opcionSeleccionada = $(this).find('option:selected');

        // Tomar el input de peso solo dentro del panel
        var peso = panel.find('.inputPeso').val();
        var peso_min = opcionSeleccionada.data('min');
        var peso_max = opcionSeleccionada.data('max');

        let modalidad = $(this).find(".modalidades-select option:selected").text();

        if (modalidad === "Combate") {
            if (!(peso >= peso_min && peso <= peso_max)) {
                mostrarAlerta("Verifique que los pesos esten en el rango seleccionado", "Aviso", "⚠️");

                // Resetear solo el select actual
                $(this).prop("selectedIndex", 0);
            }
        }
    });


    //☠️☠️☠️☠️☠️🚩🚩🚩🚩🚩☠️☠️☠️☠️☠️🚩🚩🚩🚩🚩☠️☠️☠️☠️☠️🚩🚩🚩🚩🚩 //@audit bInscribir
    //=========================== BOTON INSCRIBIRSE ===========================
    $(document).on("click", "#bInscribir", function () {
        let totalCards = $(".baseCard").length;
        let codigoGrupo = generarCodigoGrupo(totalCards);

        var id_academia = $('#idAcademia').val();

        let atletasGrupoTemporal = [];
        let sexos = [];
        let contAlertas1 = 0;

        let submodalidad;

        let proceder = false;

        $('.baseCard').each(function () {

            let atleta = $(this).find(".atletas-select option:selected").val();
            let sexo = $(this).find(".atletas-select option:selected").data("sexo");
            let edad = $(this).find(".inputEdad").val();
            let peso = $(this).find(".inputPeso").val();
            let rol = $(this).find(".rol-select option:selected").val();
            let modalidad = $(this).find(".modalidades-select option:selected").text();
            submodalidad = $(this).find(".submodalidades-select option:selected").text();
            let pesoMin = $(this).find(".categorias-select option:selected").data('min');
            let pesoMax = $(this).find(".categorias-select option:selected").data('max');

            let cantidad_atletas = $(this).find(".submodalidades-select option:selected").data('cantidad-atletas');

            //ids
            var id_evento = $("#evento-select option:selected").val();
            var id_atleta = $(this).find(".atletas-select option:selected").data("id");
            var id_division = $(this).find(".atletas-select option:selected").data("id_division");
            var id_modalidad = $(this).find(".modalidades-select option:selected").val();
            var id_subModalidad = $(this).find(".submodalidades-select option:selected").val();
            var id_categoria = $(this).find(".categorias-select option:selected").val();

            //otros
            contAlertas1++;
            sexos.push(sexo);

            // Solo si es atleta se toma medidas de revision
            if (validarCampos(rol) === false) {

                // Evita que la alerta se dispare el numero de cards en ese momento
                if (totalCards > 1) {
                    if (contAlertas1 === totalCards) {
                        mostrarAlerta("Verifique que no hayan campos vacíos en su formulario.", "Aviso", "⚠️");
                    }
                } else {
                    mostrarAlerta("Verifique que no hayan campos vacíos en su formulario.", "Aviso", "⚠️");
                }
                return;
            } else {

                if (rol === 'atleta') {

                    // Validar que el usuario no este en la misma modalida y submodalidad dos veces
                    if (verificarInscripcionRepetida(recortarNombre(atleta), modalidad, submodalidad, rol) === true) {
                        mostrarAlerta("Un atleta no puede matricularse en una submodalidad dos veces.", "Aviso", "⚠️");
                        return;
                    }
                    else if (atletaRepetidoGrupo() === false) {
                        // Evita que la alerta se dispare el numero de cards en ese momento
                        if (totalCards > 1) {
                            if (contAlertas1 === totalCards) {
                                mostrarAlerta("VUno o mas atletas estan repetidos en su formulario.", "Aviso", "⚠️");
                            }
                        }
                        return;

                    }

                    if (modalidad === "Combate") {
                        if (!(peso >= pesoMin && peso <= pesoMax) && rol === 'atleta') {
                            mostrarAlerta("Verifique que los pesos esten en el rango seleccionado.", "Aviso", "⚠️");
                            return;
                        }
                    }

                    obj = {
                        atleta: recortarNombre(atleta),
                        sexo: sexo,
                        edad: edad,
                        rol: rol,
                        peso: peso,
                        modalidad: modalidad,
                        submodalidad: submodalidad,
                        categoria: pesoMin + " - " + pesoMax,
                        grupo: codigoGrupo,
                        tr_code: crypto.randomUUID(),
                        //ids
                        id_evento: id_evento,
                        id_atleta: id_atleta,
                        id_division: id_division,
                        id_modalidad: id_modalidad,
                        id_subModalidad: id_subModalidad,
                        id_categoria: id_categoria,
                        id_academia: id_academia
                    };

                } else if (rol === 'asistente') {
                    if (validarCantidadRol(rol) === false) {// Validacion para cantidad maxima segun el rol (1 entrenador, 2 asistentes x cada 10 atletas)
                        return;
                    }

                    if (verificarInscripcionRepetida(recortarNombre(atleta), modalidad, submodalidad, rol) === true) {
                        mostrarAlerta("Al parecer este asistente ya se encuentra en lista.", "Aviso", "⚠️");
                        return;
                    }
                    obj = {
                        atleta: recortarNombre(atleta),
                        sexo: sexo,
                        edad: edad,
                        rol: rol,
                        peso: '—',
                        modalidad: '—',
                        submodalidad: '—',
                        categoria: '—',
                        grupo: '—',
                        tr_code: crypto.randomUUID(),
                        //ids
                        id_evento: id_evento,
                        id_atleta: id_atleta,
                        id_division: null,
                        id_modalidad: null,
                        id_subModalidad: null,
                        id_categoria: null,
                        id_academia: id_academia
                    };
                } else {
                    if (validarCantidadRol(rol) === false) {// Validacion para cantidad maxima segun el rol (1 entrenador, 2 asistentes x cada 10 atletas)
                        return;
                    }
                    obj = {
                        atleta: recortarNombre(atleta),
                        sexo: sexo,
                        edad: edad,
                        rol: rol,
                        peso: '—',
                        modalidad: '—',
                        submodalidad: '—',
                        categoria: '—',
                        grupo: '—',
                        tr_code: crypto.randomUUID(),
                        //ids
                        id_evento: id_evento,
                        id_atleta: id_atleta,
                        id_division: null,
                        id_modalidad: null,
                        id_subModalidad: null,
                        id_categoria: null,
                        id_academia: id_academia
                    };
                }
            }

            //🤪
            // UPDATE (Aqui se actualiza la tabla) SI es un atleta🔄️
            if (totalCards > 1) {
                atletasGrupoTemporal.push(obj);

            } else {
                listaAtletas.push(obj);
                actualizarTablaInscripciones(obj);
                guardarAtletaInscrito(obj);
                limpiarCards();
                mostrarAlerta("Se ha añadido un atleta a su lista. Ahora puedes verlo en la seccion de " + "Mis inscripciones" + "", "Éxito", "✅");
                proceder = true;
            }
        });

        // VALIDAR SI EL GRUPO PUEDE SER DE SEXO MIXTO O NO
        if (contAlertas1 === totalCards) {
            let sexo_mixto = $("#contenedor .submodalidades-select")
                .first()
                .find("option:selected")
                .data("sexo-mixto");
            if (validarSexos(sexo_mixto, sexos) === true) {
                return;
            }


            // UPDATE (Aqui se actualiza la tabla) SI es un grupo de atletas🔄️
            if (atletasGrupoTemporal.length === totalCards) {
                for (let atleta of atletasGrupoTemporal) {
                    actualizarTablaInscripciones(atleta);
                    gruposAtletas.push(atleta);
                    guardarAtletaInscrito(atleta);
                }
                limpiarCards();
                mostrarAlerta("Se han añadido atletas a su lista. Ahora puedes verlo en la seccion de " + "Mis inscripciones" + "", "Éxito", "✅");
                proceder = true;
            }

            if (proceder === true) {
                $("#contenedor .baseCard").remove();
                $("#panelRegistro").show();

                $('#panelRegistro').find('select.atletas-select').val('').trigger('change');

                $("#panelRegistro").find('.modalidades-select').show();
                $("#panelRegistro").find('.submodalidades-select').show();
                $("#panelRegistro").find('.categorias-select').show();
                $("#panelRegistro").find('#pesoInput').show();

                $("#containerButton").html(`
            <button id="bInscribir" class="btn btn-outline-success w-100">
                <i class="bi bi-plus-circle"></i> Inscribir
            </button>
            `);
                proceder = false;
            }
        }

    });


    //=========================== FUNCIONES INDEPENDIENTES ===========================

    /**
     * FUNCION QUE GUARDA EN TABLA INSCIPCIONES EL ATLETA PERO EN ESTADO INACTIVO
     * @param {*} atleta 
     */
    function guardarAtletaInscrito(atleta) {

        console.log("AQUI: ", atleta);

        $.ajax({
            url: '/inscripcionAtleta',
            method: 'POST',
            dataType: 'json',
            data: {
                atleta: atleta,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                console.log(res);

            },
            error: function (xhr, status, error) {
                mostrarAlerta("Lo sentimos, ha ocurrido un promebla al procesar la inscripcion.", "Aviso", "⚠️");
            }
        });
    }

    function modificarAtletaInscrito(obj) {
        console.log("🚩");

        $.ajax({
            url: '/modificarInscripcionAtleta',
            method: 'POST',
            dataType: 'json',
            data: {
                atletasModificar: atletasModificar,
                datosNuevos: obj,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                mostrarAlerta("Se han actualizado los datos correctamente.", "Éxito", "✅");
                atletasModificar = [];
            },
            error: function (xhr, status, error) {
                atletasModificar = [];
                mostrarAlerta("Lo sentimos, ha ocurrido un promebla al procesar la inscripcion.", "Aviso", "⚠️");
            }
        });
    }

    function modificarAtletaInscrito_p(obj, temp) {
        console.log("🏴");
        console.log("se reemplaza esto: ", temp, " POR ESTO: ", obj);

        $.ajax({
            url: '/modificarInscripcionAtleta',
            method: 'POST',
            dataType: 'json',
            data: {
                atletasModificar: temp,
                datosNuevos: obj,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                mostrarAlerta("Se han actualizado los datos correctamente.", "Éxito", "✅");
                tempx = [];
            },
            error: function (xhr, status, error) {
                tempx = [];
                mostrarAlerta("Lo sentimos, ha ocurrido un promebla al procesar la inscripcion.", "Aviso", "⚠️");
            }
        });
    }

    function eliminarAtletaInscrito() {
        console.log(atletasModificar);

        $.ajax({
            url: '/eliminarInscripcionAtleta',
            method: 'POST',
            dataType: 'json',
            data: {
                atletasModificar: atletasModificar,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                atletasModificar = [];
            },
            error: function (xhr, status, error) {
                mostrarAlerta("Lo sentimos, ha ocurrido un promebla al procesar la inscripcion.", "Aviso", "⚠️");
            }
        });
    }

    /**
     * 
     * @param {*} submodalidad 
     * @param {*} busqueda 
     * @param {*} sexosTemporal 
     * @returns 
     */
    function validarSexos(sexo_mixto, sexos) {
        console.log("sexos: ", sexos);
        console.log("sexo_mixto: ", sexo_mixto);


        let salida = false

        if (sexo_mixto === true) {
            if (sexos.every(e => e === sexos[0]) === true) {
                console.log("1-NOT CONTINUE");

                mostrarAlerta("No se permiten atletas del mismo sexo en esta submodalidad.", "Aviso", "⚠️");
                salida = true;
            }
        } else {
            if (sexos.every(e => e === sexos[0]) !== true) {
                console.log("2-NOT CONTINUE");
                mostrarAlerta("Solo se permiten atletas del mismo sexo en esta submodalidad.", "Aviso", "⚠️");
                salida = true;
            }
        }

        return salida;
    }


    // Evista que al inscribir varios atletas en un grupo se puede inscribir mas de una vez.  
    function atletaRepetidoGrupo() {
        let valores = [];
        let duplicados = false;

        // Recorre todos los selects de atletas (incluye clonados)
        $(".baseCard .atletas-select").each(function () {
            let valor = $(this).val();

            if (valor) {
                if (valores.includes(valor)) {
                    // Si ya existe → hay duplicado
                    duplicados = true;
                    return false;
                }
                valores.push(valor);
            }
        });
        return !duplicados; // true si NO hay duplicados, false si los hay
    }


    // Permite que en los cards clones solo aparescan atletas y elimina el que esta selecionado en el panel padre
    function actualizarAtletasEnClones() {
        var $padre = $('#panelRegistro .atletas-select');
        var atletaVal = $padre.val();
        var atletaText = $padre.find('option:selected').text().trim().toLowerCase();

        // recorrer selects dentro de los clones
        $('#contenedor .baseCard .atletas-select').each(function () {
            var $sel = $(this);

            // 1) eliminar opciones que contengan "entrenador" o "asistente"
            $sel.find('option').filter(function () {
                var t = $(this).text().toLowerCase();
                return t.includes('entrenador') || t.includes('asistente');
            }).remove();

            // 2) Para que el atleta seleccionado en el card padre, no aparesca en los clones
            if (atletaVal) {
                // intentar por value primero
                var encontrado = false;
                $sel.find('option').each(function () {
                    // comparar value o texto (insensible a mayúsculas)
                    var optVal = $(this).val();
                    var optText = $(this).text().trim().toLowerCase();

                    if (optVal == atletaVal || optText === atletaText) {
                        $(this).remove();
                        encontrado = true;
                        return false;
                    }
                });

                if (!encontrado) {

                }
            }
            $sel.prop('selectedIndex', 0);
        });
    }

    /**
     * Verifica que no haya atletas inscritos en una submodalidad de modalidad dos veces
     * @param {*} nombre 
     * @param {*} modalidad 
     * @param {*} submodalidad 
     * @param {*} rol 
     * @returns true si un atleta ya esta en una submodalidad de modalidad
     */
    function verificarInscripcionRepetida(nombre, modalidad, submodalidad, rol) {
        let salida = false
        let atletas = listaAtletas.concat(gruposAtletas);

        if (rol === 'atleta') {
            for (let atleta of atletas) {
                if (atleta.atleta === nombre) {
                    if (atleta.modalidad === modalidad) {
                        if (atleta.submodalidad === submodalidad) {
                            salida = true;
                        }
                    }
                }
            }
        }
        return salida;
    }

    // RESET (Borra paneles clon y limpia selects y inputs) 
    function limpiarCards() {
        let card = $("#panelRegistro");
        card.find("select").each(function () {
            this.selectedIndex = 0;
        });
        card.find("input[type='text'], input[type='number']").val('');
        $("#contenedor").empty();
    }


    /**
     * Genera una clave aleatoria para asignarse a los grupos de atletas
     * @param {*} sizeGroup Tamaño maximo de participantes segun la submodalidad
     * @returns clave random
     * 👁️FALTA VERIFICAR QUE CODIGO NO EXISTA EN LA BD❗ 
     */
    function generarCodigoGrupo(sizeGroup) {
        const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        if (sizeGroup === 1) {
            return '—';
        }
        let clave = '#' + sizeGroup;
        for (let i = 0; i < 6; i++) {
            const indice = Math.floor(Math.random() * caracteres.length);
            clave += caracteres[indice];
        }
        return clave;
    }

    /**
     * Funcion para añadir un atleta a la tabla "lista de inscritos".
     * @param {*} obj Objeto "atleta" que se añade  en fila a la tabla.
     */
    function actualizarTablaInscripciones(obj) {
        var tbody = $("#tabla-inscripcion tbody");

        var fila = `
        <tr data-id="${obj.id_atleta}" data-grupo="${obj.grupo}" data-code="${obj.tr_code}">
            <td>${obj.atleta}</td>
            <td>${obj.sexo}</td>
            <td>${obj.edad}</td>
            <td>${obj.rol}</td>
            <td>${obj.modalidad}</td>
            <td>${obj.submodalidad}</td>
            <td>${obj.categoria} (kg)</td>
            <td>${obj.grupo}</td>
            <td class="text-center">
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary rounded-pill bEditar" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger rounded-pill bEliminar" title="Eliminar" value="${obj.id_atleta}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;

        tbody.append(fila);
    }

    /**
     * Funcion para añadir un atleta a la tabla "lista de inscritos".
     * @param {*} obj Objeto "atleta" que se añade  en fila a la tabla.
     */
    function actualizarTablaInscripciones2(obj) {
        var tbody = $("#tabla-inscripcion tbody");

        var fila = `
        <tr data-id="${obj.id_atleta}" data-grupo="${obj.grupo}" data-code="${obj.tr_code}">
            <td>${obj.atleta}</td>
            <td>${obj.sexo}</td>
            <td>${obj.edad}</td>
            <td>${obj.rol}</td>
            <td>${obj.modalidad}</td>
            <td data-id-submodalidad="${obj.id_subModalidad}">${obj.submodalidad}</td>
            <td>${obj.categoria} (kg)</td>
            <td>${obj.grupo}</td>
            <td class="text-center">
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary rounded-pill bEditar2" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger rounded-pill bEliminar" title="Eliminar" value="${obj.id_atleta}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;

        tbody.append(fila);
    }

    /**
     * 
     * @param {*} obj Objeto "atleta" que se añade  en fila tr a la tabla.
     * @param {*} id  Id del tr "id_atleta" que se edita.
     * @param {*} tr_code  codigo unico que vincula el atleta a un tr en la tabla.
     */
    function actualizarTrTablaInscripciones(obj, tr_code) {
        let fila = $('tr[data-code="' + tr_code + '"]');

        if (fila.length === 0) {
            // console.warn("⚠️ No se encontró fila con data-code:", tr_code);
            return;
        }

        fila.find('td:eq(0)').text(obj.atleta);
        fila.find('td:eq(1)').text(obj.sexo);
        fila.find('td:eq(2)').text(obj.edad);
        fila.find('td:eq(3)').text(obj.rol);
        fila.find('td:eq(4)').text(obj.modalidad);
        fila.find('td:eq(5)').text(obj.submodalidad);
        fila.find('td:eq(6)').text(obj.categoria + " (kg)");
        fila.find('td:eq(7)').text(obj.grupo);

        // Cambiar atributos al nuevo code y id
        fila.attr({
            "data-code": obj.tr_code,
            "data-id": obj.id_atleta,
            "data-grupo": obj.grupo
        });

        fila.data("code", obj.tr_code);
        fila.data("id", obj.id_atleta);
    }

    function marcadorAmarillo() {
        $('#tabla-inscripcion tbody tr').each(function () {
            let td7 = $(this).find('td:eq(7)');
            let texto = td7.text().trim();

            if (texto.slice(-2) === "-p") {
                td7.css('background-color', 'yellow');
            } else {
                td7.css('background-color', 'white');
            }
        });
    }

    /**
     * Elimina el espacio de rol en el nombre proveniente de los selects, ejm "entrenador -". Deja el nombre + cedula + -
     * @param {*} nombre Nombre del atleta a recortar
     * @returns Nombre recortado
     */
    function recortarNombre(nombre) {
        let palabras = nombre.split(" ");
        palabras.pop();// elimina la última
        let recortado = palabras.join(" ");
        return recortado;
    }

    function validarCampos(rol) {
        let valido = true;

        const selector = editMode === false ? ".baseCard" : ".clonEdit";

        if (!rol) return false;

        if (rol === 'atleta') {
            $(selector).each(function (index, card) {
                $(card).find("input, select").each(function () {
                    const valor = $(this).val();

                    // Validar selects con Bootstrap‑Select o Select2
                    if ($(this).is("select")) {
                        if (!valor || valor.length === 0 || valor === null) {
                            valido = false;
                        }
                    }
                    // Validar inputs normales
                    else if ($(this).is("input")) {
                        if (!valor || valor.trim() === "") {
                            valido = false;
                        }
                    }
                });
            });
        }

        return valido;
    }

    /**
     * Valida: 1 entrenador y 2 asistentes x cada 10 atletas en una inscripcion.
     * @param {*} rol Rol del atleta "asistente, atleta o entrenador".
     * @returns false cuando no se cumple la condicion.
     */
    function validarCantidadRol(rol) {
        if (rol === 'entrenador') {
            for (let atleta of listaAtletas) {
                if (atleta.rol === 'entrenador') {
                    mostrarAlerta("Solo puede haber un entrenador matriculado por inscripcion.", "Aviso", "⚠️");
                    return false;
                }
            }
        } else if (rol === 'asistente') {
            let asistentes = listaAtletas.filter(a => a.rol === 'asistente').length;

            let a1 = listaAtletas.filter(a => a.rol === 'atleta').length;
            let a2 = gruposAtletas.filter(a => a.rol === 'atleta').length;
            let atletas = a1 + a2

            // Si no hay atletas, no se permite ningún asistente
            if (atletas === 0) {
                mostrarAlerta("No se permiten asistentes sin atletas.", "Aviso", "⚠️");
                return false;
            }

            // De 1–10 atletas = 1 asistente, 11–20 = 2, ...
            let maxAsistentes = Math.floor((atletas - 1) / 10) + 1;

            if (asistentes >= maxAsistentes) {
                mostrarAlerta("Solo se permite " + maxAsistentes + " asistente(s) para " + atletas + " atleta(s).", "Aviso", "⚠️");
                return false;
            }
        }
    }

    function actualizarListas(listaCompleta) {
        listaAtletas = listaCompleta.filter(a => !a.grupo.startsWith("#"));
        gruposAtletas = listaCompleta.filter(a => a.grupo.startsWith("#"));
    }

    function validarEnvioInscripcion() {
        let salida = true;

        let listaCompleta = listaAtletas.concat(gruposAtletas);
        console.log("listaCompleta: ", listaCompleta);


        return salida;
    }

    //=========================== ========================= ===========================


    /*
          ___       __   ________  ___       __   ________     
         |\  \     |\  \|\   __  \|\  \     |\  \|\   __  \    
         \ \  \    \ \  \ \  \|\  \ \  \    \ \  \ \  \|\  \   
          \ \  \  __\ \  \ \  \\\  \ \  \  __\ \  \ \   __  \  
           \ \  \|\__\_\  \ \  \\\  \ \  \|\__\_\  \ \  \ \  \ 
            \ \____________\ \_______\ \____________\ \__\ \__\
             \|____________|\|_______|\|____________|\|__|\|__|
                                                                 
    ███████████████████████████████████████████████████████████
    █                                                         █
    █  [DELETE] 🗑️    - Removes element                       █
    █  [EDIT]   ✏️    - Editor for modification               █
    █                                                         █
    ███████████████████████████████████████████████████████████
    */

    // ☑️ Delegación
    $(document).on("click", ".bEliminar", function () {
        let $fila = $(this).closest("tr");
        let id = $fila.data("id");
        let tr_code = $fila.data("code");
        let grupo = $fila.find("td:eq(7)").text().trim();

        $("#contenedor .clonEdit").remove();
        $("#contenedor .baseCard").remove();
        $("#panelRegistro").show();

        $("#containerButton").html(`
            <button id="bInscribir" class="btn btn-outline-success w-100">
                    <i class="bi bi-plus-circle"></i> Inscribir
            </button>
        `);

        //Eliminar inscripcion
        let listaCompleta = listaAtletas.concat(gruposAtletas);

        for (let atleta of listaCompleta) {
            if (atleta.grupo === grupo) {
                atletasModificar.push(atleta);

            } else if (atleta.id_atleta === id) {
                atletasModificar.push(atleta);
            }
        }

        eliminarAtletaInscrito(atletasModificar);

        listaCompleta = listaCompleta.filter(a =>
            !atletasModificar.some(b =>
                b.id_atleta === a.id_atleta && b.grupo === a.grupo
            )
        );

        actualizarListas(listaCompleta);

        if (grupo.includes("#")) {// tiene grupo
            if (confirm("⚠️ Aviso! Este atleta esta en grupo, si lo elimina eliminara a los atletas de ese grupo. ¿Desea continuar?")) {
                gruposAtletas = gruposAtletas.filter(atleta => atleta.grupo !== grupo);
                $("#tabla-inscripcion tbody tr[data-grupo='" + grupo + "']").remove();
            } else {

            }

        } else {// no tiene grupo
            if (confirm("⚠️ Aviso! ¿Esta seguro que quieres eliminar este atleta?")) {
                listaAtletas = listaAtletas.filter(atleta => atleta.tr_code !== tr_code);
                $fila.remove();
            } else {

            }
        }

        let panelOriginal = $("#panelRegistro");

        // Verificar si Select2 ya está inicializado antes de destruirlo                     
        const select = panelOriginal.find('.atletas-select');
        if (select.data('select2')) {
            select.select2('destroy');
        }

        var newSelect = panelOriginal.find('.atletas-select');

        // Inicializar Select2
        newSelect.select2({
            placeholder: "Selecciona un atleta",
            width: '100%'
        });
    });

    // ☑️ Delegación
    $(document).on("click", ".bEditar", function () {//@audit bEditar
        let $fila = $(this).closest("tr");
        let tr_code = $fila.attr("data-code");
        let grupo = $fila.find("td:eq(7)").text().trim();
        let trRol = $fila.find("td:eq(3)").text().trim();
        let cantidadMiembrosActuales = 0;

        if (btn_edit_code != tr_code) {
            atletasModificar = [];
            editMode = true;
            $("#panelRegistro").hide();

            // Eliminar todos los clones en el contenedor
            $("#contenedor .baseCard").remove();
            $("#contenedor .clonEdit").remove();

            if (!grupo.includes("#")) { // ATLETA INDIVIDUAL
                edicionIndividual_modo_ = true;

                for (let item of listaAtletas) {
                    if (item.tr_code === tr_code) {

                        let atleta = item;
                        atletasModificar.push(atleta);

                        let panelOriginal = $("#panelRegistro");
                        let contenedor = $("#contenedor");

                        // Destruir Select2 si existe
                        const select = panelOriginal.find('.atletas-select');
                        if (select.data('select2')) select.select2('destroy');

                        // Clonar panel
                        let nuevaCard = panelOriginal.clone();
                        nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                            "data-code": atleta.tr_code,
                            "data-grupo": atleta.grupo
                        });

                        // Copiar contenido de selects
                        nuevaCard.find(".rol-select").html(panelOriginal.find(".rol-select").html());
                        nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html());
                        nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html());
                        nuevaCard.find(".categorias-select").html(panelOriginal.find(".categorias-select").html());

                        // Inicializar Select2
                        nuevaCard.find('.atletas-select').select2({ placeholder: "Selecciona un atleta", width: '100%' });
                        // Seleccionar atleta correcto
                        let optionAtleta = nuevaCard.find(".atletas-select option").filter(function () {
                            return $(this).data("id") == atleta.id_atleta;
                        });
                        if (optionAtleta.length) nuevaCard.find(".atletas-select").val(optionAtleta.val()).trigger("change");


                        // Seleccionar rol y modalidad
                        nuevaCard.find(".rol-select option").filter(function () {
                            return $(this).val() == atleta.rol;
                        }).prop("selected", true);
                        nuevaCard.find(".modalidades-select option").filter(function () {
                            return $(this).data("nombre") == atleta.modalidad;
                        }).prop("selected", true);
                        // Llenar submodalidades y seleccionar la correcta
                        let selectSub = nuevaCard.find(".submodalidades-select");
                        $.ajax({
                            url: '/obtenerSubModalidades',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_modalidad: atleta.id_modalidad,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                selectSub.empty();
                                selectSub.append('<option value="">Seleccione una submodalidad</option>');

                                res.forEach(function (item) {
                                    selectSub.append(
                                        '<option value="' + item.id_subModalidad + '" ' +
                                        'data-nombre="' + item.nombre + '" ' +
                                        'data-cantidad-atletas="' + item.cantidad_atletas + '" ' +
                                        'data-sexo-mixto="' + item.sexo_mixto + '">' + item.nombre + '</option>'
                                    );
                                });

                                // Selección automática
                                let opcionSub = selectSub.find('option').filter(function () {
                                    return $(this).data('nombre') == atleta.submodalidad;
                                });
                                if (opcionSub.length) selectSub.val(opcionSub.val()).trigger('change');
                            },
                            error: function () {
                                mostrarAlerta("Error al cargar submodalidades.", "Aviso", "⚠️");
                            }
                        });
                        // Llenar categorías
                        let selectCat = nuevaCard.find('.categorias-select');
                        $.ajax({
                            url: '/obtenerCategorias',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_division: atleta.id_division,
                                sexo: atleta.sexo,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                selectCat.empty();
                                selectCat.append('<option value="">Seleccione una categoria</option>');
                                res.forEach(function (item) {
                                    selectCat.append(
                                        '<option value="' + item.id_categoria + '" data-min="' + item.peso_min + '" data-max="' + item.peso_max + '">' +
                                        'Peso mínimo: ' + item.peso_min + ' - Peso máximo: ' + item.peso_max +
                                        '</option>'
                                    );
                                });
                            },
                            error: function () {
                                mostrarAlerta("Error al cargar categorías.", "Aviso", "⚠️");
                            }
                        });

                        // Rellenar datos del atleta
                        nuevaCard.find(".inputSexo").val(atleta.sexo);
                        nuevaCard.find(".inputEdad").val(atleta.edad);
                        nuevaCard.find(".inputPeso").val(atleta.peso);

                        // Mostrar clon
                        nuevaCard.show();
                        contenedor.append(nuevaCard);
                    }
                }

                // Ajustar visibilidad según rol
                $('.clonEdit').each(function () {
                    if (trRol === 'entrenador' || trRol === 'asistente') {
                        $(this).find('.modalidades-select, .submodalidades-select, .categorias-select, #pesoInput').hide();
                    } else {
                        $(this).find('.modalidades-select, .submodalidades-select, .categorias-select, #pesoInput').show();
                    }
                });


            } else { // ATLETAS EN GRUPO 
                let panelOriginal = $("#panelRegistro");
                let contenedor = $("#contenedor");
                let atletaReferencia;
                edicionGrupal_modo_ = true;

                for (let item of gruposAtletas) {
                    if (item.grupo === grupo) {
                        cantidadMiembrosActuales++;

                        let datos = {
                            id_atleta: item.id_atleta,
                            id_academia: item.id_academia,
                            id_evento: item.id_evento,
                            id_modalidad: item.id_modalidad,
                            id_subModalidad: item.id_subModalidad,
                            id_categoria: item.id_categoria,
                            grupo: item.grupo,
                            rol: item.rol
                        };
                        atletasModificar.push(datos);

                        // Buscar atleta por tr_code
                        let atleta = gruposAtletas.find(a => a.tr_code == item.tr_code);
                        atletaReferencia = atleta;

                        // Destruir Select2 si existe
                        const select = panelOriginal.find('.atletas-select');
                        if (select.data('select2')) select.select2('destroy');

                        // Clonar panel
                        let nuevaCard = panelOriginal.clone();
                        nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                            "data-code": atleta.tr_code,
                            "data-grupo": atleta.grupo
                        });

                        // 🔹 Copiar selects del padre
                        nuevaCard.find(".rol-select").html(panelOriginal.find(".rol-select").html()).prop("disabled", true);
                        let indicator = grupo.slice(-2);
                        if (indicator === "-p") {
                            nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html()).prop("disabled", true);
                            nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html()).prop("disabled", true);
                        } else {
                            nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html());
                            nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html());
                        }
                        nuevaCard.find(".categorias-select").html(panelOriginal.find(".categorias-select").html());

                        // Inicializar Select2
                        let newSelect = nuevaCard.find('.atletas-select');
                        newSelect.select2({ placeholder: "Selecciona un atleta", width: '100%' });

                        // Seleccionar atleta
                        let option = nuevaCard.find(".atletas-select option").filter(function () {
                            return $(this).data("id") == atleta.id_atleta;
                        });
                        if (option.length) newSelect.val(option.val()).trigger("change");

                        // Seleccionar rol y modalidad
                        nuevaCard.find(".rol-select option").filter(function () { return $(this).val() == atleta.rol; }).prop("selected", true);
                        nuevaCard.find(".modalidades-select option").filter(function () { return $(this).data("nombre") == atleta.modalidad; }).prop("selected", true);

                        // 🔹 Cargar submodalidades vía AJAX y seleccionar la correcta
                        let selectSub = nuevaCard.find('.submodalidades-select');
                        $.ajax({
                            url: '/obtenerSubModalidades',
                            method: 'POST',
                            dataType: 'json',
                            data: { id_modalidad: atleta.id_modalidad, _token: $('meta[name="csrf-token"]').attr('content') },
                            success: function (res) {
                                selectSub.empty();
                                selectSub.append('<option value="">Seleccione una submodalidad</option>');
                                res.forEach(function (item) {
                                    selectSub.append(
                                        '<option value="' + item.id_subModalidad + '" ' +
                                        'data-nombre="' + item.nombre + '" ' +
                                        'data-cantidad-atletas="' + item.cantidad_atletas + '" ' +
                                        'data-sexo-mixto="' + item.sexo_mixto + '">' + item.nombre + '</option>'
                                    );
                                });
                                // Selección automática
                                let opcionSub = selectSub.find('option').filter(function () { return $(this).data('nombre') == atleta.submodalidad; });
                                if (opcionSub.length) selectSub.val(opcionSub.val()).trigger('change');
                            },
                            error: function () { mostrarAlerta("Error al cargar submodalidades.", "Aviso", "⚠️"); }
                        });

                        // 🔹 Cargar categorías vía AJAX
                        let selectCat = nuevaCard.find('.categorias-select');
                        $.ajax({
                            url: '/obtenerCategorias',
                            method: 'POST',
                            dataType: 'json',
                            data: { id_division: atleta.id_division, sexo: atleta.sexo, _token: $('meta[name="csrf-token"]').attr('content') },
                            success: function (res) {
                                selectCat.empty();
                                selectCat.append('<option value="">Seleccione una categoria</option>');
                                res.forEach(function (item) {
                                    selectCat.append(
                                        '<option value="' + item.id_categoria + '" data-min="' + item.peso_min + '" data-max="' + item.peso_max + '">' +
                                        'Peso mínimo: ' + item.peso_min + ' - Peso máximo: ' + item.peso_max +
                                        '</option>'
                                    );
                                });
                            },
                            error: function () { mostrarAlerta("Error al cargar categorías.", "Aviso", "⚠️"); }
                        });

                        // Rellenar datos del atleta
                        nuevaCard.find(".inputSexo").val(atleta.sexo);
                        nuevaCard.find(".inputEdad").val(atleta.edad);
                        nuevaCard.find(".inputPeso").val(atleta.peso);

                        // Mostrar clon
                        nuevaCard.show();
                        contenedor.append(nuevaCard);
                    }
                }



                //================================== COMPROBADOR DE FALTANTES =========================================\\
                let indicator = grupo.slice(-2);
                if (indicator === "-p") {
                    console.log("EENTRA");

                    edicionGrupo_modo_p = true;

                    let numeroFaltantes = grupo[1] - cantidadMiembrosActuales;
                    console.log("Faltan miembros: ", numeroFaltantes);

                    for (let i = 0; i < numeroFaltantes; i++) {
                        // Crear clon
                        let nuevaCard = panelOriginal.clone();

                        // Quitar id duplicado y marcarlo como clon
                        nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                            "data-code": "",
                            "data-grupo": ""
                        });

                        // 🔹 Copiar selects del padre
                        // nuevaCard.find(".atletas-select").html(panelOriginal.find(".atletas-select").html());
                        nuevaCard.find(".rol-select").html(panelOriginal.find(".rol-select").html()).prop("disabled", true);
                        nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html()).prop("disabled", true);
                        nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html()).prop("disabled", true);
                        nuevaCard.find(".categorias-select").html(panelOriginal.find(".categorias-select").html());

                        // 🔹 Limpiar todos los selects y inputs del clon
                        nuevaCard.find("select").val('').trigger('change');
                        nuevaCard.find("input[type='text'], input[type='number'], input[type='hidden']").val(''); // limpia inputs de texto/número

                        var newSelect = nuevaCard.find('.atletas-select');
                        // Inicializar Select2
                        newSelect.select2({
                            placeholder: "Selecciona un atleta",
                            width: '100%'
                        });

                        nuevaCard.find(".rol-select option").filter(function () {
                            return $(this).val() == atletaReferencia.rol;
                        }).prop("selected", true);
                        nuevaCard.find(".modalidades-select option").filter(function () {
                            return $(this).data("nombre") == atletaReferencia.modalidad;
                        }).prop("selected", true);
                        nuevaCard.find(".submodalidades-select option").filter(function () {
                            return $(this).data("nombre") == atletaReferencia.submodalidad;
                        }).prop("selected", true);

                        // Mostrar clon aunque el original esté oculto
                        nuevaCard.show();
                        contenedor.append(nuevaCard);
                    }
                }
                //================================== COMPROBADOR DE FALTANTES =========================================\\



                // Ocultar campos segun el rol
                if (trRol === 'entrenador' || trRol === 'asistente') {
                    // Ocultar los elementos específicos dentro de los clones
                    $('.clonEdit').each(function () {
                        $(this).find('.modalidades-select').hide();
                        $(this).find('.submodalidades-select').hide();
                        $(this).find('.categorias-select').hide();
                        $(this).find('#pesoInput').hide();
                    });
                } else {
                    // Mostrar de nuevo si no es rol especial
                    $('.clonEdit').each(function () {
                        $(this).find('.modalidades-select').show();
                        $(this).find('.submodalidades-select').show();
                        $(this).find('.categorias-select').show();
                        $(this).find('#pesoInput').show();
                    });
                }
            }

            $("#containerButton").html(`
                 <button id="bGuardar" class="btn btn-outline-success w-100">
                     <i class="bi bi-plus-circle"></i> Guardar
                 </button>
            `);

            $('html, body').animate({
                scrollTop: $("#contenedor").offset().top
            }, 500);

            btn_edit_code = tr_code;
        }
    });

    // ☑️ Delegación
    $(document).on("click", "#bGuardar", function () {//@audit bGuargar
        let contAlertas = 0;
        let totalCards = $(".clonEdit").length;
        var id_academia = $('#idAcademia').val();
        let obj;

        let atletasCambios = [];
        let sexos = [];
        let modoDesintegrarGrupo = false;
        let listaCompleta;
        let continuar = true;

        let grupoEdicion_p = generarCodigoGrupo(totalCards);

        $(".clonEdit").not(".baseCard").each(function () {
            let modalidad = $(this).find(".modalidades-select option:selected").text();
            let peso = $(this).find(".inputPeso").val();
            let rol = $(this).find(".rol-select option:selected").val();
            let pesoMin = $(this).find(".categorias-select option:selected").data('min');
            let pesoMax = $(this).find(".categorias-select option:selected").data('max');
            let sexo = $(this).find(".atletas-select option:selected").data("sexo");

            contAlertas++;
            sexos.push(sexo);

            if (modalidad === "Combate") {

                if (!(peso >= pesoMin && peso <= pesoMax) && rol === 'atleta') {
                    continuar = false
                    mostrarAlerta("Verifique que los pesos esten en el rango seleccionado.", "Aviso", "⚠️");
                }
            }

            if (validarCampos(rol) === false) {
                if (contAlertas === totalCards) {
                    mostrarAlerta("Verifique que no hayan campos vacíos en su formulario.", "Aviso", "⚠️");
                    contAlertas = 0;
                }
                continuar = false
            } else if (atletaRepetidoGrupo() === false) {
                if (totalCards > 1) {
                    if (contAlertas === totalCards) {
                        mostrarAlerta("Uno o mas atletas estan repetidos en su formulario.", "Aviso", "⚠️");
                    }
                }
                continuar = false
            }
        });

        // let sexo_mixto = $("#contenedor .submodalidades-select").first().find("option:selected").data("sexo-mixto");
        // if (validarSexos(sexo_mixto, sexos) === true) {
        //     continuar = false;
        // }

        if (continuar === true) {
            contAlertas = 0;

            if (totalCards > 1 && edicionIndividual_modo_ === true) {
                listaAtletas = listaAtletas.filter(item => item.id_atleta !== atletasModificar[0].id_atleta);
                eliminarAtletaInscrito(atletasModificar);
            }

            $(".clonEdit").not(".baseCard").each(function () {
                let nombre = $(this).find(".atletas-select option:selected").val();
                let sexo = $(this).find(".atletas-select option:selected").data("sexo");
                let edad = $(this).find(".inputEdad").val();
                let peso = $(this).find(".inputPeso").val();
                let rol = $(this).find(".rol-select option:selected").val();
                let modalidad = $(this).find(".modalidades-select option:selected").text();

                submodalidad = $(this).find(".submodalidades-select option:selected").text();
                cantidad_atletas_subModalidad = $(this).find(".submodalidades-select option:selected").data('cantidad-atletas');

                console.log("cantidad_atletas_subModalidad: ", cantidad_atletas_subModalidad);

                let pesoMin = $(this).find(".categorias-select option:selected").data('min');
                let pesoMax = $(this).find(".categorias-select option:selected").data('max');

                //ids
                var id_evento = $("#evento-select option:selected").val();
                var id_atleta = $(this).find(".atletas-select option:selected").data("id");
                var id_division = $(this).find(".atletas-select option:selected").data("id_division");
                var id_modalidad = $(this).find(".modalidades-select option:selected").val();
                var id_subModalidad = $(this).find(".submodalidades-select option:selected").val();
                var id_categoria = $(this).find(".categorias-select option:selected").val();

                let tr_code = $(this).data("code");
                let grupo = $(this).data("grupo");

                contAlertas++;

                /**
                 * Esta seccion es solo para cuando se edita un atleta sin grupo y se desea hacerle uno.
                 */
                if (totalCards > 1 && edicionIndividual_modo_ === true) {
                    $("#tabla-inscripcion tbody tr[data-code='" + tr_code + "']").remove();

                    let nuevo_tr_code = crypto.randomUUID();
                    obj = {
                        atleta: recortarNombre(nombre),
                        sexo: sexo,
                        edad: edad,
                        rol: rol,
                        peso: peso,
                        modalidad: modalidad,
                        submodalidad: submodalidad,
                        categoria: pesoMin + " - " + pesoMax,
                        grupo: grupoEdicion_p,//reutilizando variable
                        tr_code: nuevo_tr_code,
                        //ids
                        id_evento: id_evento,
                        id_atleta: id_atleta,
                        id_division: id_division,
                        id_modalidad: id_modalidad,
                        id_subModalidad: id_subModalidad,
                        id_categoria: id_categoria,
                        id_academia: id_academia
                    };
                    gruposAtletas.push(obj);
                    actualizarTablaInscripciones(obj);
                    guardarAtletaInscrito(obj);

                } else {

                    /**
                     * Esta seccion es para el flujo normar de edicion sin cambios que afecten el flujo
                     * Tabien se marca con un indicador(modoDesintegrarGrupo) si el flujo se rompe (si un grupo es desintegrado)
                     */
                    listaCompleta = listaAtletas.concat(gruposAtletas);
                    for (let i = 0; i < listaCompleta.length; i++) {

                        if (tr_code === listaCompleta[i].tr_code) {
                            let nuevo_tr_code = crypto.randomUUID();

                            if (rol === 'atleta') {
                                // 1

                                if (grupo.includes("#") && cantidad_atletas_subModalidad === 1) {
                                    grupo = '—';
                                    modoDesintegrarGrupo = true;
                                }

                                obj = {
                                    atleta: recortarNombre(nombre),
                                    sexo: sexo,
                                    edad: edad,
                                    rol: rol,
                                    peso: peso,
                                    modalidad: modalidad,
                                    submodalidad: submodalidad,
                                    categoria: pesoMin + " - " + pesoMax,
                                    grupo: edicionGrupo_modo_p === true ? grupoEdicion_p : grupo,
                                    tr_code: nuevo_tr_code,
                                    //ids
                                    id_evento: id_evento,
                                    id_atleta: id_atleta,
                                    id_division: id_division,
                                    id_modalidad: id_modalidad,
                                    id_subModalidad: id_subModalidad,
                                    id_categoria: id_categoria,
                                    id_academia: id_academia
                                };
                            } else {
                                obj = {
                                    atleta: recortarNombre(nombre),
                                    sexo: sexo,
                                    edad: edad,
                                    rol: rol,
                                    peso: '—',
                                    modalidad: '—',
                                    submodalidad: '—',
                                    categoria: '—',
                                    grupo: '—',
                                    tr_code: crypto.randomUUID(),
                                    //ids
                                    id_evento: id_evento,
                                    id_atleta: id_atleta,
                                    id_division: null,
                                    id_modalidad: null,
                                    id_subModalidad: null,
                                    id_categoria: null,
                                    id_academia: id_academia
                                }
                            }
                            atletasCambios.push({
                                obj,
                                tr_code
                            });

                            listaCompleta[i] = obj;
                            actualizarListas(listaCompleta);
                        }
                    }

                    if (!tr_code) {
                        console.log("entra aqui porque no hay tr_code");

                        let nuevo_tr_code = crypto.randomUUID();

                        obj = {
                            atleta: recortarNombre(nombre),
                            sexo: sexo,
                            edad: edad,
                            rol: rol,
                            peso: peso,
                            modalidad: modalidad,
                            submodalidad: submodalidad,
                            categoria: pesoMin + " - " + pesoMax,
                            grupo: grupoEdicion_p,
                            tr_code: nuevo_tr_code,
                            //ids
                            id_evento: id_evento,
                            id_atleta: id_atleta,
                            id_division: id_division,
                            id_modalidad: id_modalidad,
                            id_subModalidad: id_subModalidad,
                            id_categoria: id_categoria,
                            id_academia: id_academia
                        };
                        gruposAtletas.push(obj);
                        actualizarTablaInscripciones(obj);
                        guardarAtletaInscrito(obj);
                    }
                }

                if (totalCards > 1) {
                    if (contAlertas === totalCards) {
                        mostrarAlerta("Se han actualizado los datos correctamente.", "Éxito", "✅");
                    }
                }
            });


            // console.log("atletasCambios: ", atletasCambios);

            for (let i = 0; i < atletasCambios.length; i++) {
                if (modoDesintegrarGrupo === true) {
                    if (atletasCambios[i].obj.grupo.includes('—')) {

                        id = atletasCambios[i].obj.id_atleta;

                        let atleta = atletasModificar.find(a => a.id_atleta === id);
                        tempx.push(atleta);

                        // Eliminarlo del array atletasModificar
                        atletasModificar = atletasModificar.filter(a => a.id_atleta !== id);

                        modificarAtletaInscrito_p(atletasCambios[i].obj, tempx);
                        actualizarTrTablaInscripciones(atletasCambios[i].obj, atletasCambios[i].tr_code);

                    }
                    else {
                        if (atletasCambios[i].obj.grupo.includes('#')) {
                            atletasCambios[i].obj.grupo = atletasCambios[i].obj.grupo + "-p";

                            //_______________________________________________________________________________
                            id = atletasCambios[i].obj.id_atleta;

                            let atleta = atletasModificar.find(a => a.id_atleta === id);
                            tempx.push(atleta);

                            // Eliminarlo del array atletasModificar
                            atletasModificar = atletasModificar.filter(a => a.id_atleta !== id);
                            //_______________________________________________________________________________

                            // modificarAtletaInscrito(atletasCambios[i].obj);
                            modificarAtletaInscrito_p(atletasCambios[i].obj, tempx);
                            actualizarTrTablaInscripciones(atletasCambios[i].obj, atletasCambios[i].tr_code);
                        }
                    }
                } else {

                    modificarAtletaInscrito(atletasCambios[i].obj);
                    actualizarTrTablaInscripciones(atletasCambios[i].obj, atletasCambios[i].tr_code);
                }
            }

            marcadorAmarillo();//pinta en la lista el td de grupo para indicar que el grupo esta incompleto

            if (contAlertas === totalCards) {
                $("#contenedor .clonEdit").remove();
                $("#panelRegistro").show();

                // Si quieres limpiar el valor seleccionado sin dejarlo en blanco visualmente,
                // quita el val('') o pon un valor por defecto
                $('#panelRegistro').find('select.atletas-select').val('').trigger('change');

                $("#panelRegistro select.atletas-select").select2({
                    placeholder: "Selecciona un atleta",
                    width: '100%'
                });

                $("#panelRegistro").find('.modalidades-select, .submodalidades-select, .categorias-select, #pesoInput').show();

                $("#containerButton").html(`
                    <button id="bInscribir" class="btn btn-outline-success w-100">
                        <i class="bi bi-plus-circle"></i> Inscribir
                    </button>
                `);
            }

            edicionGrupo_modo_p = false;
            edicionIndividual_modo_ = false;
            edicionGrupal_modo_ = false;
            editMode = false;
        }
        listaCompleta = listaAtletas.concat(gruposAtletas);
        console.log("ASI ESTA LA LISTA: ", listaCompleta);
    });

    // ☑️ Delegación
    $('#bEnviar').on('click', function () {//@audit bEnviar
        let eventoText = $('#evento-select option:selected').text();
        let id_evento = $('#evento-select option:selected').val();
        let id_academia = $('#idAcademia').val();
        let modeView = $('#modeView').val();

        if (confirm("⚠️ Aviso! Desea enviar esta inscripcion? la informacion no puede cambiarse posterior al envio.")) {
            console.log("a1");


            if (validarEnvioInscripcion() === true) {
                console.log("a2");


                let listaCompleta = listaAtletas.concat(gruposAtletas);
                if (listaCompleta.length >= 2) {

                    $.ajax({
                        url: '/procesarInscripcion',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            id_academia: id_academia,
                            id_evento: id_evento,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            mostrarAlerta("Su inscripción para el evento " + eventoText + " se ha procesado con éxito!", "Éxito", "✅",
                                function () {
                                    if (modeView == false) {
                                        window.location.href = "/nuevaInscripcion";
                                    } else {
                                        window.location.href = "/misInscripciones";
                                    }
                                }
                            );
                        },
                        error: function (xhr, status, error) {
                            mostrarAlerta("Lo sentimos, al parecer a ocurrido un error al procesar su inscripcion.", "Aviso", "⚠️");
                        }
                    });
                } else {
                    mostrarAlerta("Verifique la cantidad de atletas inscritos en su lista antes de continuar.", "Aviso", "⚠️");
                }
            }

        } else {

        }
    });



    /*
     ___       __   ________  ___       __   ________     
     |\  \     |\  \|\   __  \|\  \     |\  \|\   __  \    
     \ \  \    \ \  \ \  \|\  \ \  \    \ \  \ \  \|\  \   
      \ \  \  __\ \  \ \  \\\  \ \  \  __\ \  \ \   __  \  
       \ \  \|\__\_\  \ \  \\\  \ \  \|\__\_\  \ \  \ \  \ 
        \ \____________\ \_______\ \____________\ \__\ \__\
         \|____________|\|_______|\|____________|\|__|\|__|
                                                                     
    ████████████████████████████████████████████████████████
    █                                                      █
    █  🚀 CONTINUE EDIT LOGIC                             █
    █                                                      █
    █  ACTION:                                             █
    █     - CONTINUE MODIFYING EXISTING REGISTRATION       █
    █     - PRESERVE CHANGES AND UPDATE FIELDS            -█
    █     - ENSURE DATA INTEGRITY                          █
    █                                                      █
    ████████████████████████████████████████████████████████
    */
    $(document).on('click', '.btn-edit', function (e) {
        let id_evento = $(this).data('id-evento');
    });

    if (window.inscripcionApp && window.inscripcionApp.continuarEdicion) {
        $("#panelRegistro").show();
        $("#containerButton").show();

        let $select = $("#evento-select");
        if ($select.length) {
            $select.prop("selectedIndex", 1).prop("disabled", true);
        }

        btn_edit_code = "";


        //distribuir
        const atletasPHP = window.inscripcionApp.atletasInscripcion;

        let obj = {};

        for (let atleta of atletasPHP) {
            console.log("ANTES: ", atletasPHP);


            // Calcular edad
            var fecha_nacimiento = atleta.fecha_nacimiento;
            var edad = '';
            if (fecha_nacimiento) {
                var anio = parseInt(fecha_nacimiento.split('-')[0]);
                var currentYear = new Date().getFullYear();
                edad = currentYear - anio;
            }

            if (atleta.rol === "atleta") {
                //CREAR EL OBJETO 🚩

                obj = {
                    atleta: `${atleta.nombre || '—'} ${atleta.primer_apellido || '—'} ${atleta.segundo_apellido || '—'} - ${atleta.identificacion || '—'} -`,
                    sexo: atleta.sexo || '—',
                    edad: edad || '—',
                    rol: atleta.rol || '—',
                    peso: atleta.peso || '—',
                    modalidad: atleta.modalidad?.nombre || '—',
                    submodalidad: atleta.subModalidad?.nombre || '—',
                    categoria: atleta.categoria ? `${atleta.categoria.peso_min} - ${atleta.categoria.peso_max}` : '—',
                    grupo: atleta.grupo || '—',
                    tr_code: crypto.randomUUID(),
                    // ids
                    id_evento: atleta.evento?.id_evento || null,
                    id_atleta: atleta.id_atleta || null,
                    id_division: atleta.categoria.id_division || null,
                    id_modalidad: atleta.modalidad?.id_modalidad || null,
                    id_subModalidad: atleta.subModalidad?.id_subModalidad || null,
                    id_categoria: atleta.categoria?.id_categoria || null,
                    id_academia: atleta.id_academia || null
                };
            } else {
                obj = {
                    atleta: `${atleta.nombre || '—'} ${atleta.primer_apellido || '—'} ${atleta.segundo_apellido || '—'} - ${atleta.identificacion || '—'} -`,
                    sexo: atleta.sexo || '—',
                    edad: edad || '—',
                    rol: atleta.rol || '—',
                    peso: '—',
                    modalidad: '—',
                    submodalidad: '—',
                    categoria: '—',
                    grupo: '—',
                    tr_code: crypto.randomUUID(),
                    // ids
                    id_evento: atleta.evento?.id_evento || null,
                    id_atleta: atleta.id_atleta || null,
                    id_division: null,
                    id_modalidad: null,
                    id_subModalidad: null,
                    id_categoria: null,
                    id_academia: atleta.id_academia || null
                };
            }

            // LLENAR LAS LISTAS 🚩
            if (obj.grupo.includes("#")) {
                gruposAtletas.push(obj);
            } else {
                listaAtletas.push(obj);
            }
        }

        let listaCompleta = listaAtletas.concat(gruposAtletas);
        for (let atleta of listaCompleta) {
            actualizarTablaInscripciones2(atleta);
        }

        marcadorAmarillo();//para resaltar los grupos incompletos

        //LLENAR MODALIDADES🏳️
        function cargarModalidades() {
            var id_evento = $('#evento-select').val();
            // console.log("Enviando id_evento:", id_evento); // depuración

            if (!id_evento) return;

            //🏳️
            $.ajax({
                url: '/obtenerModalidades',
                method: 'POST',
                dataType: 'json',
                data: {
                    id_evento: id_evento,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    var select = $('.modalidades-select');
                    select.empty();
                    select.append('<option value="">Seleccione una modalidad</option>');

                    // si la respuesta viene dentro de un objeto: res.modalidades
                    var lista = Array.isArray(res) ? res : res.modalidades;

                    lista.forEach(function (item) {
                        select.append('<option value="' + item.id_modalidad + '" data-nombre="' + item.nombre + '">' + item.nombre + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    mostrarAlerta("Lo sentimos, al parecer a ocurrido un error al cargar las modalidades.", "Aviso", "⚠️");
                    console.error(error);
                }
            });
        }

        $('#evento-select').on('change', cargarModalidades);
        cargarModalidades();
    }

    $(document).on('click', '.bEliminarMiInscripcion', function (e) {
        let $fila = $(this).closest("tr");
        let id_evento = $fila.find("td:eq(0)").data("id-evento");
        let id_academia = $('#idAcademia').val();

        if (confirm("⚠️ Aviso! ¿Esta seguro que desea eliminar esta inscripción?")) {
            $.ajax({
                url: '/eliminarInscripcion',
                method: 'POST',
                dataType: 'json',
                data: {
                    id_evento: id_evento,
                    id_academia: id_academia,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    location.reload();
                    mostrarAlerta("Su inscripcion ha sido eliminada.", "Éxito", "✅");
                },
                error: function (xhr, status, error) {
                    mostrarAlerta("Lo sentimos, al parecer a ocurrido un error al eliminar esta inscripcion.", "Aviso", "⚠️");
                }
            });
        } else {

        }
    });

    $(document).on("click", ".bEditar2", function () {//@audit bEditar2
        let $fila = $(this).closest("tr");
        let tr_code = $fila.attr("data-code");
        let grupo = $fila.find("td:eq(7)").text().trim();
        let trRol = $fila.find("td:eq(3)").text().trim();
        let cantidadMiembrosActuales = 0;

        if (btn_edit_code != tr_code) {
            atletasModificar = [];
            editMode = true;
            $("#panelRegistro").hide();

            // Eliminar todos los clones en el contenedor
            $("#contenedor .baseCard").remove();
            $("#contenedor .clonEdit").remove();

            if (!grupo.includes("#")) { // ATLETA INDIVIDUAL
                edicionIndividual_modo_ = true;

                for (let item of listaAtletas) {
                    if (item.tr_code === tr_code) {

                        let atleta = item;
                        atletasModificar.push(atleta);

                        let panelOriginal = $("#panelRegistro");
                        let contenedor = $("#contenedor");

                        // Destruir Select2 si existe
                        const select = panelOriginal.find('.atletas-select');
                        if (select.data('select2')) select.select2('destroy');

                        // Clonar panel
                        let nuevaCard = panelOriginal.clone();
                        nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                            "data-code": atleta.tr_code,
                            "data-grupo": atleta.grupo
                        });

                        // Copiar contenido de selects
                        nuevaCard.find(".rol-select").html(panelOriginal.find(".rol-select").html());
                        nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html());
                        nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html());
                        nuevaCard.find(".categorias-select").html(panelOriginal.find(".categorias-select").html());

                        // Inicializar Select2
                        nuevaCard.find('.atletas-select').select2({ placeholder: "Selecciona un atleta", width: '100%' });
                        // Seleccionar atleta correcto
                        let optionAtleta = nuevaCard.find(".atletas-select option").filter(function () {
                            return $(this).data("id") == atleta.id_atleta;
                        });
                        if (optionAtleta.length) nuevaCard.find(".atletas-select").val(optionAtleta.val()).trigger("change");


                        // Seleccionar rol y modalidad
                        nuevaCard.find(".rol-select option").filter(function () {
                            return $(this).val() == atleta.rol;
                        }).prop("selected", true);
                        nuevaCard.find(".modalidades-select option").filter(function () {
                            return $(this).data("nombre") == atleta.modalidad;
                        }).prop("selected", true);
                        // Llenar submodalidades y seleccionar la correcta
                        let selectSub = nuevaCard.find(".submodalidades-select");
                        $.ajax({
                            url: '/obtenerSubModalidades',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_modalidad: atleta.id_modalidad,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                selectSub.empty();
                                selectSub.append('<option value="">Seleccione una submodalidad</option>');

                                res.forEach(function (item) {
                                    selectSub.append(
                                        '<option value="' + item.id_subModalidad + '" ' +
                                        'data-nombre="' + item.nombre + '" ' +
                                        'data-cantidad-atletas="' + item.cantidad_atletas + '" ' +
                                        'data-sexo-mixto="' + item.sexo_mixto + '">' + item.nombre + '</option>'
                                    );
                                });

                                // Selección automática
                                let opcionSub = selectSub.find('option').filter(function () {
                                    return $(this).data('nombre') == atleta.submodalidad;
                                });
                                if (opcionSub.length) selectSub.val(opcionSub.val()).trigger('change');
                            },
                            error: function () {
                                mostrarAlerta("Error al cargar submodalidades.", "Aviso", "⚠️");
                            }
                        });
                        // Llenar categorías
                        let selectCat = nuevaCard.find('.categorias-select');
                        $.ajax({
                            url: '/obtenerCategorias',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_division: atleta.id_division,
                                sexo: atleta.sexo,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                selectCat.empty();
                                selectCat.append('<option value="">Seleccione una categoria</option>');
                                res.forEach(function (item) {
                                    selectCat.append(
                                        '<option value="' + item.id_categoria + '" data-min="' + item.peso_min + '" data-max="' + item.peso_max + '">' +
                                        'Peso mínimo: ' + item.peso_min + ' - Peso máximo: ' + item.peso_max +
                                        '</option>'
                                    );
                                });
                            },
                            error: function () {
                                mostrarAlerta("Error al cargar categorías.", "Aviso", "⚠️");
                            }
                        });

                        // Rellenar datos del atleta
                        nuevaCard.find(".inputSexo").val(atleta.sexo);
                        nuevaCard.find(".inputEdad").val(atleta.edad);
                        nuevaCard.find(".inputPeso").val(atleta.peso);

                        // Mostrar clon
                        nuevaCard.show();
                        contenedor.append(nuevaCard);
                    }
                }

                // Ajustar visibilidad según rol
                $('.clonEdit').each(function () {
                    if (trRol === 'entrenador' || trRol === 'asistente') {
                        $(this).find('.modalidades-select, .submodalidades-select, .categorias-select, #pesoInput').hide();
                    } else {
                        $(this).find('.modalidades-select, .submodalidades-select, .categorias-select, #pesoInput').show();
                    }
                });


            } else { // ATLETAS EN GRUPO 
                let panelOriginal = $("#panelRegistro");
                let contenedor = $("#contenedor");
                let atletaReferencia;
                edicionGrupal_modo_ = true;

                for (let item of gruposAtletas) {
                    if (item.grupo === grupo) {
                        cantidadMiembrosActuales++;

                        let datos = {
                            id_atleta: item.id_atleta,
                            id_academia: item.id_academia,
                            id_evento: item.id_evento,
                            id_modalidad: item.id_modalidad,
                            id_subModalidad: item.id_subModalidad,
                            id_categoria: item.id_categoria,
                            grupo: item.grupo,
                            rol: item.rol
                        };
                        atletasModificar.push(datos);

                        // Buscar atleta por tr_code
                        let atleta = gruposAtletas.find(a => a.tr_code == item.tr_code);
                        atletaReferencia = atleta;

                        // Destruir Select2 si existe
                        const select = panelOriginal.find('.atletas-select');
                        if (select.data('select2')) select.select2('destroy');

                        // Clonar panel
                        let nuevaCard = panelOriginal.clone();
                        nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                            "data-code": atleta.tr_code,
                            "data-grupo": atleta.grupo
                        });

                        // 🔹 Copiar selects del padre
                        nuevaCard.find(".rol-select").html(panelOriginal.find(".rol-select").html()).prop("disabled", true);
                        let indicator = grupo.slice(-2);
                        if (indicator === "-p") {
                            nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html()).prop("disabled", true);
                            nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html()).prop("disabled", true);
                        } else {
                            nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html());
                            nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html());
                        }
                        nuevaCard.find(".categorias-select").html(panelOriginal.find(".categorias-select").html());

                        // Inicializar Select2
                        let newSelect = nuevaCard.find('.atletas-select');
                        newSelect.select2({ placeholder: "Selecciona un atleta", width: '100%' });

                        // Seleccionar atleta
                        let option = nuevaCard.find(".atletas-select option").filter(function () {
                            return $(this).data("id") == atleta.id_atleta;
                        });
                        if (option.length) newSelect.val(option.val()).trigger("change");

                        // Seleccionar rol y modalidad
                        nuevaCard.find(".rol-select option").filter(function () { return $(this).val() == atleta.rol; }).prop("selected", true);
                        nuevaCard.find(".modalidades-select option").filter(function () { return $(this).data("nombre") == atleta.modalidad; }).prop("selected", true);

                        // 🔹 Cargar submodalidades vía AJAX y seleccionar la correcta
                        let selectSub = nuevaCard.find('.submodalidades-select');
                        $.ajax({
                            url: '/obtenerSubModalidades',
                            method: 'POST',
                            dataType: 'json',
                            data: { id_modalidad: atleta.id_modalidad, _token: $('meta[name="csrf-token"]').attr('content') },
                            success: function (res) {
                                selectSub.empty();
                                selectSub.append('<option value="">Seleccione una submodalidad</option>');
                                res.forEach(function (item) {
                                    selectSub.append(
                                        '<option value="' + item.id_subModalidad + '" ' +
                                        'data-nombre="' + item.nombre + '" ' +
                                        'data-cantidad-atletas="' + item.cantidad_atletas + '" ' +
                                        'data-sexo-mixto="' + item.sexo_mixto + '">' + item.nombre + '</option>'
                                    );
                                });
                                // Selección automática
                                let opcionSub = selectSub.find('option').filter(function () { return $(this).data('nombre') == atleta.submodalidad; });
                                if (opcionSub.length) selectSub.val(opcionSub.val()).trigger('change');
                            },
                            error: function () { mostrarAlerta("Error al cargar submodalidades.", "Aviso", "⚠️"); }
                        });

                        // 🔹 Cargar categorías vía AJAX
                        let selectCat = nuevaCard.find('.categorias-select');
                        $.ajax({
                            url: '/obtenerCategorias',
                            method: 'POST',
                            dataType: 'json',
                            data: { id_division: atleta.id_division, sexo: atleta.sexo, _token: $('meta[name="csrf-token"]').attr('content') },
                            success: function (res) {
                                selectCat.empty();
                                selectCat.append('<option value="">Seleccione una categoria</option>');
                                res.forEach(function (item) {
                                    selectCat.append(
                                        '<option value="' + item.id_categoria + '" data-min="' + item.peso_min + '" data-max="' + item.peso_max + '">' +
                                        'Peso mínimo: ' + item.peso_min + ' - Peso máximo: ' + item.peso_max +
                                        '</option>'
                                    );
                                });
                            },
                            error: function () { mostrarAlerta("Error al cargar categorías.", "Aviso", "⚠️"); }
                        });

                        // Rellenar datos del atleta
                        nuevaCard.find(".inputSexo").val(atleta.sexo);
                        nuevaCard.find(".inputEdad").val(atleta.edad);
                        nuevaCard.find(".inputPeso").val(atleta.peso);

                        // Mostrar clon
                        nuevaCard.show();
                        contenedor.append(nuevaCard);
                    }
                }



                //================================== COMPROBADOR DE FALTANTES =========================================\\
                let indicator = grupo.slice(-2);
                if (indicator === "-p") {
                    edicionGrupo_modo_p = true;

                    let numeroFaltantes = grupo[1] - cantidadMiembrosActuales;
                    console.log("Faltan miembros: ", numeroFaltantes);

                    for (let i = 0; i < numeroFaltantes; i++) {
                        // Crear clon
                        let nuevaCard = panelOriginal.clone();

                        // Quitar id duplicado y marcarlo como clon
                        nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                            "data-code": "",
                            "data-grupo": ""
                        });

                        // 🔹 Copiar selects del padre
                        // nuevaCard.find(".atletas-select").html(panelOriginal.find(".atletas-select").html());
                        nuevaCard.find(".rol-select").html(panelOriginal.find(".rol-select").html()).prop("disabled", true);
                        nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html()).prop("disabled", true);
                        nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html()).prop("disabled", true);
                        nuevaCard.find(".categorias-select").html(panelOriginal.find(".categorias-select").html());

                        var select = nuevaCard.find('.categorias-select');
                        select.empty();
                        select.append('<option value="">Seleccione una categoria</option>');

                        /**
                         * PATCH 
                         * Seleccionar la submodalidad correspondiente manualmente
                         */

                        $.ajax({
                            url: '/obtenerSubModalidades',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_modalidad: atletaReferencia.id_modalidad,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                let submodalidadSelect = nuevaCard.find(".submodalidades-select");
                                submodalidadSelect.empty();
                                submodalidadSelect.append('<option value="">Seleccione una submodalidad</option>');

                                res.forEach(function (item) {
                                    submodalidadSelect.append(
                                        '<option value="' + item.id_subModalidad + '" ' +
                                        'data-nombre="' + item.nombre + '" data-cantidad-atletas="' + item.cantidad_atletas + '">' + item.nombre + '</option>'
                                    );
                                });

                                // 🔹 Seleccionar automáticamente si tienes el nombre guardado
                                var nombreSub = submodalidad
                                if (nombreSub) {
                                    let opcion = submodalidadSelect.find('option').filter(function () {
                                        return $(this).data('nombre') == nombreSub;
                                    });

                                    if (opcion.length) {
                                        opcion.prop('selected', true);
                                        submodalidadSelect.val(opcion.val()).trigger("change");
                                    }
                                }
                            },
                            error: function () {
                                mostrarAlerta("Lo sentimos, al parecer a ocurrido un error al cargar las submodalidades", "Aviso", "⚠️");
                            }
                        });


                        // 🔹 Limpiar todos los selects y inputs del clon
                        nuevaCard.find("select").val('').trigger('change');
                        nuevaCard.find("input[type='text'], input[type='number'], input[type='hidden']").val(''); // limpia inputs de texto/número

                        var newSelect = nuevaCard.find('.atletas-select');
                        // Inicializar Select2
                        newSelect.select2({
                            placeholder: "Selecciona un atleta",
                            width: '100%'
                        });

                        nuevaCard.find(".rol-select option").filter(function () {
                            return $(this).val() == atletaReferencia.rol;
                        }).prop("selected", true);
                        nuevaCard.find(".modalidades-select option").filter(function () {
                            return $(this).data("nombre") == atletaReferencia.modalidad;
                        }).prop("selected", true);
                        nuevaCard.find(".submodalidades-select option").filter(function () {
                            return $(this).data("nombre") == atletaReferencia.submodalidad;
                        }).prop("selected", true);

                        // Mostrar clon aunque el original esté oculto
                        nuevaCard.show();
                        contenedor.append(nuevaCard);
                    }
                }
                //================================== COMPROBADOR DE FALTANTES =========================================\\



                // Ocultar campos segun el rol
                if (trRol === 'entrenador' || trRol === 'asistente') {
                    // Ocultar los elementos específicos dentro de los clones
                    $('.clonEdit').each(function () {
                        $(this).find('.modalidades-select').hide();
                        $(this).find('.submodalidades-select').hide();
                        $(this).find('.categorias-select').hide();
                        $(this).find('#pesoInput').hide();
                    });
                } else {
                    // Mostrar de nuevo si no es rol especial
                    $('.clonEdit').each(function () {
                        $(this).find('.modalidades-select').show();
                        $(this).find('.submodalidades-select').show();
                        $(this).find('.categorias-select').show();
                        $(this).find('#pesoInput').show();
                    });
                }
            }

            $("#containerButton").html(`
                 <button id="bGuardar" class="btn btn-outline-success w-100">
                    <i class="bi bi-plus-circle"></i> Guardar
                 </button>
            `);

            $('html, body').animate({
                scrollTop: $("#contenedor").offset().top
            }, 500);

            btn_edit_code = tr_code;
        }
    });












    /*
       ___       __   ________  ___       __   ________     
       |\  \     |\  \|\   __  \|\  \     |\  \|\   __  \    
       \ \  \    \ \  \ \  \|\  \ \  \    \ \  \ \  \|\  \   
        \ \  \  __\ \  \ \  \\\  \ \  \  __\ \  \ \   __  \  
         \ \  \|\__\_\  \ \  \\\  \ \  \|\__\_\  \ \  \ \  \ 
          \ \____________\ \_______\ \____________\ \__\ \__\
           \|____________|\|_______|\|____________|\|__|\|__|
                                                                       
      ████████████████████████████████████████████████████████
      █                                                      █
      █  🚀                    Alertas                       █
      █                                                      █
      ████████████████████████████████████████████████████████
      */

    // Mostrar alerta personalizada
    function mostrarAlerta(mensaje, titulo = "Alerta", icono = "⚠️", callback = null) {
        $("#customAlertMessage").text(mensaje);
        $("#customAlertTitle").text(titulo);
        $(".custom-alert-icon").text(icono);

        $("#customAlertOverlay").fadeIn(200).css("display", "flex");

        // Guardar callback si existe
        $("#customAlertOverlay").data("callback", callback);
    }

    // Cerrar alerta
    function cerrarAlerta() {
        $("#customAlertOverlay").fadeOut(200, function () {
            var callback = $("#customAlertOverlay").data("callback");
            if (typeof callback === "function") {
                callback(); // ejecutar solo si existe callback
            }
            // Limpiar callback después
            $("#customAlertOverlay").removeData("callback");
        });
    }

    // Evento de cerrar alerta con botón
    $(document).on("click", "#btnCerrarAlerta", function () {
        cerrarAlerta();
    });

    // Cerrar alerta con tecla ESC
    $(document).on("keydown", function (e) {
        if (e.key === "Escape") {
            cerrarAlerta();
        }
    });





    /*
       ___       __   ________  ___       __   ________     
       |\  \     |\  \|\   __  \|\  \     |\  \|\   __  \    
       \ \  \    \ \  \ \  \|\  \ \  \    \ \  \ \  \|\  \   
        \ \  \  __\ \  \ \  \\\  \ \  \  __\ \  \ \   __  \  
         \ \  \|\__\_\  \ \  \\\  \ \  \|\__\_\  \ \  \ \  \ 
          \ \____________\ \_______\ \____________\ \__\ \__\
           \|____________|\|_______|\|____________|\|__|\|__|
                                                                       
      ████████████████████████████████████████████████████████
      █                                                      █
      █  🚀                    Selct2                       █
      █                                                      █
      ████████████████████████████████████████████████████████
      */
});




