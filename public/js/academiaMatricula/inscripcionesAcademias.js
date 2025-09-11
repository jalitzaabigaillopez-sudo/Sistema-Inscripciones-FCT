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
    // |
    // |
    // |===> critical data variable                                          
    let delta = 0;

    //================================== MEMORY ACCESS =======================================
    let listaAtletas = [];
    let gruposAtletas = [];



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
                    select.append('<option value="' + item.id_modalidad + '" data-nombre="' + item.nombre + '">' + item.nombre + '</option>');
                });
            },
            error: function (xhr, status, error) {
                alert("⚠️ Lo sentimos, al parecer a ocurrido un error al cargar las modalidades.");
            }
        });
    });

    //=========================== SELECT DE MODALIDADES ===========================

    // ⬅️Delegación de eventos sobre el select del panel principal
    $(document).on('change', '.modalidades-select', function () {
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
                    select.append('<option value="' + item.id_subModalidad + '" data-cantidad-atletas="' + item.cantidad_atletas + '" data-nombre="' + item.nombre + '">' + item.nombre + '</option>');
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
        panel.find('.inputRol').val(rol);

        if (rol === 'entrenador' || rol === 'asistente') {
            // Ocultar los elementos específicos dentro de los clones
            $('.baseCard').each(function () {
                $(this).find('.modalidades-select').hide();
                $(this).find('.submodalidades-select').hide();
                $(this).find('.categorias-select').hide();
                $(this).find('#pesoInput').hide();
            });
        } else {
            // Mostrar de nuevo si no es rol especial
            $('.baseCard').each(function () {
                $(this).find('.modalidades-select').show();
                $(this).find('.submodalidades-select').show();
                $(this).find('.categorias-select').show();
                $(this).find('#pesoInput').show();
            });
        }

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
                alert("⚠️ Lo sentimos, al parecer a ocurrido un error al cargar las categorias.");
            }
        });
    });

    // ⬅️Delegación de eventos para los selects de submodalidades
    $(document).on('change', '.submodalidades-select', function () {
        var opcion = $(this).find('option:selected');
        var cantidad_atletas = opcion.data('cantidad-atletas') || 1;

        // console.log(cantidad_atletas);

        var panelOriginal = $('#panelRegistro');
        var contenedor = $('#contenedor');
        contenedor.find('.baseCard').not('.clonEdit').remove();

        // Crear N-1 copias limpias
        for (let i = 1; i < cantidad_atletas; i++) {
            var nuevaCard = panelOriginal.clone().removeAttr('id');

            // 🔹 Limpiar inputs editables
            nuevaCard.find('input').val('');

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

            // 🔹 Categorías (vacías y editables por el usuario)
            var selectCategorias = nuevaCard.find('.categorias-select');
            selectCategorias.empty();
            selectCategorias.append('<option value="">Seleccione una categoría</option>');
            selectCategorias.prop('disabled', false);

            contenedor.append(nuevaCard);
        }

        actualizarAtletasEnClones();
        //Hace que solo aparescan atletas en los cards clones
        $("#contenedor .baseCard .atletas-select").each(function () {
            actualizarAtletasEnClones();
        });

        permitirClonar = false;
    });

    // ⬅️ Delegación de eventos para todos los selects de categorias
    $(document).on('change', '.categorias-select', function () {
        // Buscar el panel contenedor de este select (puede ser el padre o clon)
        var panel = $(this).closest('.card-body');

        var opcionSeleccionada = $(this).find('option:selected');

        // Tomar el input de peso solo dentro del panel
        var peso = panel.find('.inputPeso').val();
        var peso_min = opcionSeleccionada.data('min');
        var peso_max = opcionSeleccionada.data('max');

        if (!(peso >= peso_min && peso <= peso_max)) {
            alert("⚠️ Verifique que los pesos esten en el rango seleccionado");

            // Resetear solo el select actual
            $(this).prop("selectedIndex", 0);
        }
    });


    //☠️☠️☠️☠️☠️🚩🚩🚩🚩🚩☠️☠️☠️☠️☠️🚩🚩🚩🚩🚩☠️☠️☠️☠️☠️🚩🚩🚩🚩🚩
    //=========================== BOTON INSCRIBIRSE ===========================
    $(document).on("click", "#bInscribir", function () {
        let totalCards = $(".baseCard").length;
        let codigoGrupo = generarCodigoGrupo(totalCards);

        let atletasGrupoTemporal = [];
        let sexosTemporal = [];
        let contAlertas1 = 0;

        let submodalidad;

        $('.baseCard').each(function () {
            var id_atleta = $(this).find(".atletas-select option:selected").data("id");
            let atleta = $(this).find(".atletas-select option:selected").val();
            let sexo = $(this).find(".atletas-select option:selected").data("sexo");
            let edad = $(this).find(".inputEdad").val();
            let peso = $(this).find(".inputPeso").val();
            let rol = $(this).find(".inputRol").val();
            let modalidad = $(this).find(".modalidades-select option:selected").text();
            submodalidad = $(this).find(".submodalidades-select option:selected").text();
            let pesoMin = $(this).find(".categorias-select option:selected").data('min');
            let pesoMax = $(this).find(".categorias-select option:selected").data('max');

            contAlertas1++;
            sexosTemporal.push(sexo);

            // Solo si es atleta se toma medidas de revision
            if (validarCampos(rol) === false) {

                // Evita que la alerta se dispare el numero de cards en ese momento
                if (totalCards > 1) {
                    if (contAlertas1 === totalCards) {
                        alert("⚠️ Verifique que no hayan campos vacíos en su formulario.");
                    }
                } else {
                    alert("⚠️ Verifique que no hayan campos vacíos en su formulario.");
                }
                return;
            } else {

                if (rol === 'atleta') {

                    // Validar que el usuario no este en la misma modalida y submodalidad dos veces
                    if (verificarInscripcionRepetida(recortarNombre(atleta), modalidad, submodalidad, rol) === true) {
                        alert("⚠️ Aviso! Un atleta no puede matricularse en una submodalidad dos veces.");
                        return;
                    }
                    else if (atletaRepetidoGrupo() === false) {
                        // Evita que la alerta se dispare el numero de cards en ese momento
                        if (totalCards > 1) {
                            if (contAlertas1 === totalCards) {
                                alert("⚠️ Aviso! Uno o mas atletas estan repetidos en su formulario.");
                            }
                        }
                        return;

                    }

                    else if (!(peso >= pesoMin && peso <= pesoMax) && rol === 'atleta') {
                        alert("⚠️ Verifique que los pesos esten en el rango seleccionado");
                        return;
                    }
                    obj = {
                        id_atleta: id_atleta,
                        atleta: recortarNombre(atleta),
                        sexo: sexo,
                        edad: edad,
                        rol: rol,
                        peso: peso,
                        modalidad: modalidad,
                        submodalidad: submodalidad,
                        categoria: pesoMin + " - " + pesoMax,
                        grupo: codigoGrupo,
                        tr_code: crypto.randomUUID()
                    };

                } else if (rol === 'asistente') {
                    if (validarCantidadRol(rol) === false) {// Validacion para cantidad maxima segun el rol (1 entrenador, 2 asistentes x cada 10 atletas)
                        return;
                    }
                    if (verificarInscripcionRepetida(recortarNombre(atleta), modalidad, submodalidad, rol) === true) {
                        alert("⚠️ Aviso! Al parecer este asistente ya se encuentra en lista.");
                        return;
                    }
                    obj = {
                        id_atleta: id_atleta,
                        atleta: recortarNombre(atleta),
                        sexo: sexo,
                        edad: edad,
                        rol: rol,
                        peso: '—',
                        modalidad: '—',
                        submodalidad: '—',
                        categoria: '—',
                        grupo: '—',
                        tr_code: crypto.randomUUID()
                    };
                } else {
                    if (validarCantidadRol(rol) === false) {// Validacion para cantidad maxima segun el rol (1 entrenador, 2 asistentes x cada 10 atletas)
                        return;
                    }
                    obj = {
                        id_atleta: id_atleta,
                        atleta: recortarNombre(atleta),
                        sexo: sexo,
                        edad: edad,
                        rol: rol,
                        peso: '—',
                        modalidad: '—',
                        submodalidad: '—',
                        categoria: '—',
                        grupo: '—',
                        tr_code: crypto.randomUUID()
                    };
                }
            }

            if (contAlertas1 === totalCards) {
                if (validarSexos(submodalidad, 'Poomsae', sexosTemporal) === true) {
                    return;
                }
            }

            //🤪
            // UPDATE (Aqui se actualiza la tabla) SI es un atleta🔄️
            if (totalCards > 1) {
                atletasGrupoTemporal.push(obj);

            } else {
                listaAtletas.push(obj);
                actualizarTablaInscripciones(obj);
                limpiarCards();
                alert("✅ Exito! Se ha añadido un atleta a su lista")
            }
        });

        // UPDATE (Aqui se actualiza la tabla) SI es un grupo de atletas🔄️
        if (atletasGrupoTemporal.length === totalCards) {
            for (let atleta of atletasGrupoTemporal) {
                actualizarTablaInscripciones(atleta);
                gruposAtletas.push(atleta);
            }
            limpiarCards();
            alert("✅ Exito! Se ha añadido un grupo de atletas a su lista")
        }
    });

    //=========================== FUNCIONES INDEPENDIENTES ===========================

    /**
     * 
     * @param {*} submodalidad 
     * @param {*} busqueda 
     * @param {*} sexosTemporal 
     * @returns 
     */
    function validarSexos(submodalidad, busqueda, sexosTemporal) {
        // Normalizar: quitar espacios y pasar a minúsculas
        let normalizado = submodalidad.replace(/\s+/g, "").toLowerCase();
        let normalizadoBusqueda = busqueda.replace(/\s+/g, "").toLowerCase();
        let salida = false


        if (normalizado.includes(normalizadoBusqueda)) {
            if (sexosTemporal.length === 2) {
                if (sexosTemporal.every(e => e === sexosTemporal[0]) === true) {
                    alert("⚠️ Aviso! No se permiten atletas del mismo sexo en esta submodalidad.");
                    salida = true;
                } else
                    salida = false;
            } else if (sexosTemporal.length > 2) {
                if (sexosTemporal.every(e => e === sexosTemporal[0]) !== true) {
                    alert("⚠️ Aviso! Solo se permiten atletas del mismo sexo en esta submodalidad.");
                    salida = true;
                } else {
                    salida = false;
                }
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
                    return false; // rompe el each
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
        } else {
            for (let atleta of atletas) {
                if (atleta.atleta === nombre) {
                    salida = true;
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

        // let dataCode = crypto.randomUUID();

        var fila = `
        <tr data-id="${obj.id_atleta}" data-grupo="${obj.grupo}" data-code="${obj.tr_code}">
            <td>#</td>
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
     * 
     * @param {*} obj Objeto "atleta" que se añade  en fila tr a la tabla.
     * @param {*} id  Id del tr "id_atleta" que se edita.
     * @param {*} tr_code  codigo unico que vincula el atleta a un tr en la tabla.
     */
    function actualizarTrTablaInscripciones(obj, id, tr_code) {
        let fila = $('tr[data-code="' + tr_code + '"]');

        fila.find('td:eq(0)').text('#');
        fila.find('td:eq(1)').text(obj.atleta);
        fila.find('td:eq(2)').text(obj.sexo);
        fila.find('td:eq(3)').text(obj.edad);
        fila.find('td:eq(4)').text(obj.rol);
        fila.find('td:eq(5)').text(obj.modalidad);
        fila.find('td:eq(6)').text(obj.submodalidad);
        fila.find('td:eq(7)').text(obj.categoria);
        fila.find('td:eq(8)').text(obj.grupo);

        fila.attr('data-id', id);
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

    /**
     * Verifica en Card con clase "baseCard o clonEdit" si hay selects o inputs vacios
     * @param {*} rol Rol del atleta "asistente, atleta o entrenador".
     * @returns false cuando no se cumple la condicion.
     */
    function validarCampos(rol) {
        let valido = true;

        if (editMode === false) {
            if (rol === 'atleta') {
                $(".baseCard").each(function (index, card) {
                    $(card).find("input, select").each(function () {
                        if ($(this).val() === "" || $(this).val() === null) {
                            valido = false;
                            // console.warn("Campo vacío en la card #" + (index + 1), this);
                        }
                    });
                });
            } else if (!rol) {
                valido = false;
            }
        } else {
            if (rol === 'atleta') {
                $(".clonEdit").each(function (index, card) {
                    $(card).find("input, select").each(function () {
                        if ($(this).val() === "" || $(this).val() === null) {
                            valido = false;
                        }
                    });
                });
            } else if (!rol) {
                valido = false;
            }
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
                    alert("⚠️ Solo puede haber un entrenador matriculado por inscripcion.");
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
                alert("⚠️ No se permiten asistentes sin atletas.");
                return false;
            }

            // De 1–10 atletas = 1 asistente, 11–20 = 2, ...
            let maxAsistentes = Math.floor((atletas - 1) / 10) + 1;

            if (asistentes >= maxAsistentes) {
                alert("⚠️ Solo se permite " + maxAsistentes + " asistente(s) para " + atletas + " atleta(s).");
                return false;
            }
        }
    }

    function actualizarListas(listaCompleta) {
        listaAtletas = [];
        gruposAtletas = [];
        for (let atleta of listaCompleta) {
            if (atleta.grupo.includes("#")) {
                gruposAtletas.push(atleta);
            } else {
                listaAtletas.push(atleta);
            }
        }
    }

    //=========================== ========================= ===========================



    // ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
    // ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
    // ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    //                                                        ⏮️ Logica para botones eliminar y editar ⏭️
    // ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    // ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
    // ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓

    // ☑️ Delegación
    $(document).on("click", ".bEliminar", function () {
        let $fila = $(this).closest("tr");
        let id = $fila.data("id");
        let grupo = $fila.find("td:eq(8)").text().trim();

        if (grupo.includes("#")) {// tiene grupo
            if (confirm("⚠️ Aviso! Este atleta esta en grupo, si lo elimina eliminara a los atletas de ese grupo. ¿Desea continuar?")) {

                for (let atleta of gruposAtletas) {

                }
            } else {

            }

        } else {// no tiene grupo
            if (confirm("⚠️ Aviso! ¿Esta seguro que quieres eliminar este atleta?")) {
                listaAtletas = listaAtletas.filter(atleta => atleta.id_atleta !== id);
                $fila.remove();
            } else {

            }
        }
    });

    // ☑️ Delegación
    $(document).on("click", ".bEditar", function () {
        let $fila = $(this).closest("tr");
        let tr_code = $fila.data("code"); // ← este debe ser el id_atleta, viene del tr
        let grupo = $fila.find("td:eq(8)").text().trim();
        let totalCards = $(".clonEdit").length;

        editMode = true;
        $("#panelRegistro").hide();

        // Eliminar todos los clones en el contenedor
        $("#contenedor .baseCard").remove();
        $("#contenedor .clonEdit").remove();

        if (!grupo.includes("#")) {// ATLETA INDIVIDUAL 
            for (let item of listaAtletas) {

                if (item.tr_code === tr_code) {
                    let panelOriginal = $("#panelRegistro");
                    let contenedor = $("#contenedor");

                    // Buscar atleta por id
                    console.log("Lista: " + JSON.stringify(listaAtletas));
                    let atleta = item;
                    console.log("El atleta es: " + JSON.stringify(atleta));


                    // Crear clon
                    let nuevaCard = panelOriginal.clone();

                    // Quitar id duplicado y marcarlo como clon
                    nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                        "data-code": atleta.tr_code,
                        "data-grupo": atleta.grupo
                    });

                    // Mostrar clon aunque el original esté oculto
                    nuevaCard.show();

                    // 🔹 Copiar selects del padre
                    nuevaCard.find(".atletas-select").html(panelOriginal.find(".atletas-select").html());
                    nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html());
                    nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html());
                    nuevaCard.find(".categorias-select").html(panelOriginal.find(".categorias-select").html());

                    // 🔹 Limpiar inputs de texto
                    nuevaCard.find("input").val("");

                    // 🔹 Seleccionar automáticamente 
                    nuevaCard.find(".atletas-select option").filter(function () {
                        return $(this).data("id") == atleta.id_atleta;
                    }).prop("selected", true);
                    nuevaCard.find(".modalidades-select option").filter(function () {
                        return $(this).data("nombre") == atleta.modalidad;
                    }).prop("selected", true);
                    nuevaCard.find(".submodalidades-select option").filter(function () {
                        return $(this).data("nombre") == atleta.submodalidad;
                    }).prop("selected", true);

                    nuevaCard.find(".inputSexo").val(atleta.sexo);
                    nuevaCard.find(".inputEdad").val(atleta.edad);
                    nuevaCard.find(".inputRol").val(atleta.rol);
                    nuevaCard.find(".inputPeso").val(atleta.peso);

                    contenedor.append(nuevaCard);
                }
            }
        } else { // ATLETAS EN GRUPO
            let panelOriginal = $("#panelRegistro");
            let contenedor = $("#contenedor");

            for (let item of gruposAtletas) {

                if (item.grupo === grupo) {
                    // atletasModificar.push(item);

                    // Buscar atleta por id
                    let atleta = gruposAtletas.find(a => a.tr_code == item.tr_code);

                    // Crear clon
                    let nuevaCard = panelOriginal.clone();

                    // Quitar id duplicado y marcarlo como clon
                    nuevaCard.removeAttr("id").removeClass("baseCard").addClass("clonEdit").attr({
                        "data-code": atleta.tr_code,
                        "data-grupo": atleta.grupo
                    });

                    // Mostrar clon aunque el original esté oculto
                    nuevaCard.show();

                    // 🔹 Copiar selects del padre
                    nuevaCard.find(".atletas-select").html(panelOriginal.find(".atletas-select").html());
                    nuevaCard.find(".modalidades-select").html(panelOriginal.find(".modalidades-select").html()).prop("disabled", true);
                    nuevaCard.find(".submodalidades-select").html(panelOriginal.find(".submodalidades-select").html()).prop("disabled", true);
                    nuevaCard.find(".categorias-select").html(panelOriginal.find(".categorias-select").html());

                    // 🔹 Limpiar inputs de texto
                    nuevaCard.find("input").val("");

                    // 🔹 Seleccionar automáticamente 
                    nuevaCard.find(".atletas-select option").filter(function () {
                        return $(this).data("id") == atleta.id_atleta;
                    }).prop("selected", true);
                    nuevaCard.find(".modalidades-select option").filter(function () {
                        return $(this).data("nombre") == atleta.modalidad;
                    }).prop("selected", true);
                    nuevaCard.find(".submodalidades-select option").filter(function () {
                        return $(this).data("nombre") == atleta.submodalidad;
                    }).prop("selected", true);

                    nuevaCard.find(".inputSexo").val(atleta.sexo);
                    nuevaCard.find(".inputEdad").val(atleta.edad);
                    nuevaCard.find(".inputRol").val(atleta.rol);
                    nuevaCard.find(".inputPeso").val(atleta.peso);

                    contenedor.append(nuevaCard);
                }
            }

            //lol
            $('#contenedor .clonEdit .atletas-select').each(function () {
                var $sel = $(this);

                // 1) eliminar opciones que contengan "entrenador" o "asistente"
                $sel.find('option').filter(function () {
                    var t = $(this).text().toLowerCase();
                    return t.includes('entrenador') || t.includes('asistente');
                }).remove();

            });
        }

        $("#containerButton").html(`
            <button id="bGuardar" class="btn btn-outline-success w-100">
                <i class="bi bi-plus-circle"></i> Guardar
            </button>
        `);
    });

    // ☑️ Delegación
    $(document).on("click", "#bGuardar", function () {
        // GUARDAR EDICION INDIVIDUAL
        $(".clonEdit").not(".baseCard").each(function () {
            var id_atleta = $(this).find(".atletas-select option:selected").data("id");
            let nombre = $(this).find(".atletas-select option:selected").val();
            let sexo = $(this).find(".atletas-select option:selected").data("sexo");
            let edad = $(this).find(".inputEdad").val();
            let peso = $(this).find(".inputPeso").val();
            let rol = $(this).find(".inputRol").val();
            let modalidad = $(this).find(".modalidades-select option:selected").text();
            submodalidad = $(this).find(".submodalidades-select option:selected").text();
            let pesoMin = $(this).find(".categorias-select option:selected").data('min');
            let pesoMax = $(this).find(".categorias-select option:selected").data('max');

            let tr_code = $(this).data("code");
            let grupo = $(this).data("grupo");

            if (validarCampos(rol) === false) {
                alert("⚠️ Verifique que no hayan campos vacíos en su formulario.");

            } else {

                let listaCompleta = listaAtletas.concat(gruposAtletas);

                for (let i = 0; i < listaCompleta.length; i++) {
                    if (tr_code === listaCompleta[i].tr_code) {

                        let nuevo_tr_code = crypto.randomUUID();

                        let $fila = $("tr[data-code='" + tr_code + "']");
                        $fila.attr("data-code", nuevo_tr_code);
                        // console.log("Usuario a modificar: " + JSON.stringify(data));
                        // console.log("Usuario nuevo: " + JSON.stringify(listaCompleta[i]));

                        let obj = {
                            id_atleta: id_atleta,
                            atleta: recortarNombre(nombre),
                            sexo: sexo,
                            edad: edad,
                            rol: rol,
                            peso: peso,
                            modalidad: modalidad,
                            submodalidad: submodalidad,
                            categoria: pesoMin + " - " + pesoMax,
                            grupo: grupo,
                            tr_code: nuevo_tr_code
                        };

                        listaCompleta[i] = obj;
                        // actualizar las lista separadas "listaAtletas, gruposAtletas"                                         🚩
                        actualizarTrTablaInscripciones(listaCompleta[i], obj.id_atleta, nuevo_tr_code);
                    }
                }

                actualizarListas(listaCompleta);
                alert("✅ Exito! Se han actualizado los datos correctamente.");

                $("#panelRegistro").show();
                $("#contenedor .clonEdit").remove();

                $("#containerButton").html(`
                    <button id="bInscribir" class="btn btn-outline-success w-100">
                        <i class="bi bi-plus-circle"></i> Inscribir
                    </button>
                `);
            }
        });
    });

    // ☑️ Delegación
    $('#bEnviar').on('click', function () {
        var eventoText = $('#evento-select option:selected').text();
        alert("✅ Su inscripcion para el evento " + eventoText + " se ha procesado con exito!");
    });
});
