$(document).ready(function () {

    // VARIABLES DE CONTROL

    //=========================================================================
    let listaAtletas = [];


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
                var select = $('.modalidades-select');
                select.empty();

                select.append('<option value="">Seleccione una modalidad</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_modalidad + '">' + item.nombre + '</option>');
                });
            },
            error: function (xhr, status, error) {
                alert("⚠️ Lo sentimos, al parecer a ocurrido un error al cargar las modalidades.");
            }
        });
    });

    //=========================== SELECT DE MODALIDADES ===========================
    $('.modalidades-select').on('change', function () {
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
                var select = $('.submodalidades-select');
                select.empty();

                select.append('<option value="">Seleccione una submodalidad</option>');

                res.forEach(function (item) {
                    select.append('<option value="' + item.id_subModalidad + '" data-cantidad-atletas="' + item.cantidad_atletas + '">' + item.nombre + '</option>');
                });
            },
            error: function (xhr, status, error) {
                alert("⚠️ Lo sentimos, al parecer a ocurrido un error al cargar las modalidades.");
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
            // console.log("Se eliminaron todos los clones porque se seleccionó un rol especial.");
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
        panel.find('#inputEdad').val(edad + " años");
        panel.find('#inputRol').val(rol);

        if (rol === 'entrenador' || rol === 'asistente') {
            // Ocultar los elementos específicos dentro de los clones
            $('.baseCard').each(function () {
                $(this).find('.modalidades-select').hide();
                $(this).find('.submodalidades-select').hide();
                $(this).find('.categorias-select').hide();
                $(this).find("input[id='inputPeso']").hide();
            });
        } else {
            // Mostrar de nuevo si no es rol especial
            $('.baseCard').each(function () {
                $(this).find('.modalidades-select').show();
                $(this).find('.submodalidades-select').show();
                $(this).find('.categorias-select').show();
                $(this).find("input[id='inputPeso']").show();
            });
        }

        $.ajax({
            url: '/obtenerCategorias',
            method: 'POST',
            dataType: 'json',
            data: {
                id_atleta: id_atleta,
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
                alert("⚠️ Lo sentimos, al parecer a ocurrido un error al cargar las categorias.");
            }
        });
    });

    // ⬅️Delegación de eventos para los selects de submodalidades
    $(document).on('change', '.submodalidades-select', function () {
        var opcion = $(this).find('option:selected');
        var cantidad_atletas = opcion.data('cantidad-atletas') || 1;

        var panelOriginal = $('#panelRegistro');
        var contenedor = $('#contenedor');

        // Limpiar copias previas
        contenedor.empty();

        // Crear N-1 copias limpias
        for (let i = 1; i < cantidad_atletas; i++) {
            var nuevaCard = panelOriginal.clone().removeAttr('id');

            // Limpiar inputs
            nuevaCard.find('input').val('');

            // Modalidades (copiar opciones del padre, asignar valor y deshabilitar)
            var selectModalidadesOriginal = panelOriginal.find('.modalidades-select');
            var selectModalidadesCopia = nuevaCard.find('.modalidades-select');
            selectModalidadesCopia.html(selectModalidadesOriginal.html()); // copiar todas las opciones
            selectModalidadesCopia.val(selectModalidadesOriginal.val()).prop('disabled', true);

            // Submodalidades (copiar opciones del padre, asignar valor y deshabilitar)
            var selectSubModalidadesOriginal = panelOriginal.find('.submodalidades-select');
            var selectSubModalidadesCopia = nuevaCard.find('.submodalidades-select');
            selectSubModalidadesCopia.html(selectSubModalidadesOriginal.html());
            selectSubModalidadesCopia.val(selectSubModalidadesOriginal.val()).prop('disabled', true);

            var selectCategorias = nuevaCard.find('.categorias-select');
            selectCategorias.empty();
            selectCategorias.append('<option value="">Seleccione una categoría</option>');

            // Agregar al contenedor
            contenedor.append(nuevaCard);
        }

        //Hace que solo aparescan atletas en los cards clones
        $("#contenedor .baseCard .atletas-select").each(function () {
            // Eliminamos las opciones cuyo texto contenga "entrenador" o "asistente"
            $(this).find("option").filter(function () {
                let texto = $(this).text().toLowerCase();
                return texto.includes("entrenador") || texto.includes("asistente");
            }).remove();
        });

        permitirClonar = false;
    });

    // ⬅️ Delegación de eventos para todos los selects de categorias
    $(document).on('change', '.categorias-select', function () {
        // Buscar el panel contenedor de este select (puede ser el padre o clon)
        var panel = $(this).closest('.card-body');

        // Opción seleccionada
        var opcionSeleccionada = $(this).find('option:selected');

        // Tomar el input de peso solo dentro del panel
        var peso = panel.find('#inputPeso').val();
        var peso_min = opcionSeleccionada.data('min');
        var peso_max = opcionSeleccionada.data('max');

        if (!(peso >= peso_min && peso <= peso_max)) {
            alert("⚠️ Verifique que los pesos esten en el rango seleccionado");

            // Resetear solo el select actual
            $(this).prop("selectedIndex", 0);
        }
    });


    //=========================== FUNCIONES INDEPENDIENTES ===========================

    //👁️FALTA VERIFICAR QUE CODIGO NO EXISTA EN LA BD❗
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

    function actualizarTablaInscripciones(obj) {
        var tbody = $("#tabla-inscripcion tbody");

        let atleta = obj.atleta;
        let palabras = atleta.split(" ");
        palabras.pop();// elimina la última
        let recortado = palabras.join(" ");
        var fila = `
            <tr>
                <td>#</td>
                <td>`+ recortado + `</td>
                <td>`+ obj.sexo + `</td>
                <td>`+ obj.edad + `</td>
                <td>`+ obj.rol + `</td>
                <td>`+ obj.modalidad + `</td>
                <td>`+ obj.submodalidad + `</td>
                <td>`+ obj.categoria + `</td>
                <td>`+ obj.grupo + `</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </div>
                </td>
            </tr>
        `;

        tbody.append(fila);
    }

    // Verificar que todos los campos de los baseCard(Donde se completan los datos del atleta) esten completos
    function validarCampos() {
        let valido = true;
        $(".baseCard").each(function () {
            $(this).find("input, select").each(function () {
                if ($(this).val() === "" || $(this).val() === null) {
                    valido = false;
                }
            });
        });

        if (!valido) {
            return false;
        } else {
            return true;
        }
    }

    //Valida: 1 entrenador y 2 asistentes x cada 10 atletas en una inscripcion
    function validarCantidadRol(rol) {
        if (rol === 'entrenador') {
            for (let atleta of listaAtletas) {
                if (atleta.rol === 'entrenador') {
                    alert("⚠️ Solo puede haber un entrenador matriculado por inscripcion.");
                    return false;
                }
            }
        } else if (rol === 'asistente') {
            let asistentes = listaAtletas.filter(a => a.rol === 'asistente').length;
            let atletas = listaAtletas.filter(a => a.rol === 'atleta').length;

            // Si no hay atletas, no se permite ningún asistente
            if (atletas === 0) {
                alert("⚠️ No se permiten asistentes sin atletas.");
                return false;
            }

            // De 1–10 atletas = 1 asistente, 11–20 = 2, etc.
            let maxAsistentes = Math.floor((atletas - 1) / 10) + 1;

            if (asistentes >= maxAsistentes) {
                alert("⚠️ Solo se permite " + maxAsistentes + " asistente(s) para " + atletas + " atleta(s).");
                return false;
            }
        }
    }
    //=========================== ========================= ===========================


    //=========================== BOTON INSCRIBIRSE ===========================
    $('#bInscribir').on('click', function () {
        let totalCards = $(".baseCard").length;
        let codigoGrupo = generarCodigoGrupo(totalCards);

        $('.baseCard').each(function () {
            let atleta = $(this).find(".atletas-select option:selected").val();
            let sexo = $(this).find(".atletas-select option:selected").data("sexo");
            let edad = $(this).find(".inputEdad").val();
            let peso = $(this).find(".inputPeso").val();
            let rol = $(this).find(".inputRol").val();
            let modalidad = $(this).find(".modalidades-select option:selected").text();
            let submodalidad = $(this).find(".submodalidades-select option:selected").text();
            let pesoMin = $(this).find(".categorias-select option:selected").data('min');
            let pesoMax = $(this).find(".categorias-select option:selected").data('max');

            if (validarCampos() === false && rol === 'atleta') {//solo con rol atleta
                alert("⚠️ Verifique que no hayan campos vacíos en su formulario.");
                return;
            }

            //Validacion para cantidad maxima segun el rol (1 entrenador, 2 asistentes x cada 10 atletas)
            if (validarCantidadRol(rol) === false) {
                return;
            }

            //🤪
            if (!(peso >= pesoMin && peso <= pesoMax) && rol==='atleta') {
                alert("⚠️ Verifique que los pesos esten en el rango seleccionado");
                return;
            }

            let obj;
            if (rol === 'atleta') {
                obj = {
                    atleta: atleta,
                    sexo: sexo,
                    edad: edad,
                    rol: rol,
                    modalidad: modalidad,
                    submodalidad: submodalidad,
                    categoria: pesoMin + " - " + pesoMax,
                    grupo: codigoGrupo
                };
            } else {
                obj = {
                    atleta: atleta,
                    sexo: sexo,
                    edad: edad,
                    rol: rol,
                    modalidad: '—',
                    submodalidad: '—',
                    categoria: '—',
                    grupo: '—'
                };
            }

            listaAtletas.push(obj);
            actualizarTablaInscripciones(obj)
            
        //RESET
        let card = $("#panelRegistro"); // 👈 id del card principal
        // Resetear selects al primer valor
        card.find("select").each(function () {
            this.selectedIndex = 0;
        });
        card.find("input[type='text'], input[type='number']").val('');
        $("#contenedor").empty();
        });
        // console.log(listaAtletas);
    });

});
