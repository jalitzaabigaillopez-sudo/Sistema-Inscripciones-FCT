/**
 * ❗logic with memory reset❗
 */

$(document).ready(function () {
    var seleccionados = []; // memoria local
    var idsAtletas = [];
    var contEntrenadores = 0;


    $('#select-entrenador, #select-asistente, #select-atleta').on('change', function () {
        var valor = $(this).val();

        if (valor) {
            if (seleccionados.includes(valor)) {
                alert("⚠️ Ese atleta ya esta en lista");
                return;
            }
            if (valor.toLowerCase().includes("entrenador")) {
                contEntrenadores++;
                if (contEntrenadores > 1) {
                    alert("Solo un entrenador puede ser matriculado por evento")
                    return;
                }

            } else if (valor.toLowerCase().includes("asistente")) {
                if (!validarAsistentes(seleccionados)) {
                    alert("Solo un asistente puede ser matriculado por cada diez atletas")
                    return;
                }

            }
            // Guardar en memoria si no esta
            if (!seleccionados.includes(valor)) {
                seleccionados.push(valor);
                actualizarLista();
            }

            // Guardar id para uso posterior

            var selectedOption = $(this).find('option:selected'); // la opción seleccionada
            var id = selectedOption.data('id');
            idsAtletas.push(id);
            console.log("ids guardadas: " + idsAtletas);

            // resetear el select para que se pueda volver a elegir más adelante
            $(this).val('');
        }
    });

    // Función para actualizar panel
    function actualizarLista() {

        var $lista = $('#listaSeleccionados');
        $lista.empty();
        seleccionados.forEach(function (item, index) {

            $lista.append(
                '<li class="list-group-item">' +
                item +
                ' <button class="btn btn-xs btn-danger pull-right eliminar" data-index="' + index + '">X</button>' +
                '</li>'
            );
        });

        console.log(seleccionados);
    }

    // Quitar elementos de la lista
    $('#listaSeleccionados').on('click', '.eliminar', function () {
        var idx = $(this).data('index');
        var item = seleccionados[idx];

        if (item.toLowerCase().includes("atleta")) {
            contAtletas--;
        }
        else if (item.toLowerCase().includes("asistente")) {
            contAsistentes--;
        }
        else if (item.toLowerCase().includes("entrenador")) {
            contEntrenadores--;
        }

        seleccionados.splice(idx, 1); // eliminar de memoria
        actualizarLista();
    });


    /**
     * Funciones para x asistente por x cantidad de asitentes
     */
    // Cuenta cuántos elementos contienen la palabra clave (atleta o asistente)
    function validarAsistentes(seleccionados) {
        let atletas = seleccionados.filter(item => item.toLowerCase().includes("atleta")).length;
        let asistentes = seleccionados.filter(item => item.toLowerCase().includes("asistente")).length;

        // No se permiten asistentes sin atletas
        if (atletas === 0) return false;

        // Calcular el máximo de asistentes permitido
        let maxAsistentes = Math.ceil(atletas / 10);

        // Si ya llegué al límite, no dejar agregar más
        return asistentes < maxAsistentes;
    }
});