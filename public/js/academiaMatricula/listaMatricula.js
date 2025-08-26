/**
 * ❗logic with memory reset❗
 */

$(document).ready(function () {
    var seleccionados = []; // memoria local
    var contEntrenadores = 0;
    var contAsistentes = 0;
    var contAtletas = 0;

    $('#select-entrenador, #select-asistente, #select-atleta').on('change', function () {
        var valor = $(this).val();
        if (valor) {
            if (valor.toLowerCase().includes("entrenador")) {
                contEntrenadores++;
                if (contEntrenadores > 1) {
                    alert("Solo un entrenador puede ser matriculado por evento")
                    return;
                }
                // console.log("Este ítem es un ENTRENADOR:", contEntrenadores);

            } else if (valor.toLowerCase().includes("asistente")) {
                // contAsistentes++;
                if (contAtletas > 0) {
                    console.log("entra");
                    if (!contAtletas % 10 === 0) {
                        alert("Solo un asistente puede ser matriculado por cada diez atletas en un evento")
                        return;
                    }
                } else {
                    alert("Solo un asistente puede ser matriculado por cada diez atletas en un evento")
                    return;
                }
                // console.log("Este ítem es un ASISTENTE:", contAsistentes);

            } else if (valor.toLowerCase().includes("atleta")) {
                contAtletas++;
            }

            console.log(contAtletas);

            // Guardar en memoria si no esta
            if (!seleccionados.includes(valor)) {
                seleccionados.push(valor);
                actualizarLista();
            }

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
        seleccionados.splice(idx, 1); // eliminar de memoria
        actualizarLista();
    });
});