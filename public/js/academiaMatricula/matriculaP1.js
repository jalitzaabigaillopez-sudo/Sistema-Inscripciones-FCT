/**
 * ❗logic with memory reset❗
 */

$(document).ready(function () {
    var seleccionados = []; // memoria local para atletas
    var idsAtletas = []; // memoria local para ids de atletas
    var contEntrenadores = 0;

    /**
     * Segun la opcion seleccionada en el select se guarda el atleta en la lista en memoria.
     * Se validad 1 entrenador y 1 asistente por cada 10 atletas en la matricula.
     * Se guardan los ids de los atletas en memoria temporal.
     */
    $('#select-entrenador, #select-asistente, #select-atleta').on('change', function () {
        var valor = $(this).val();

        if (valor) {
            if (seleccionados.includes(valor)) {
                alert("⚠️ Ese atleta ya esta en lista");
                $(this).val('');
                return;
            }
            else if (valor.toLowerCase().includes("entrenador")) {
                contEntrenadores++;
                if (contEntrenadores > 1) {
                    alert("Solo un entrenador puede ser matriculado por evento")
                    $(this).val('');
                    return;
                }

            } else if (valor.toLowerCase().includes("asistente")) {
                if (!validarAsistentes(seleccionados)) {
                    alert("Solo un asistente puede ser matriculado por cada diez atletas")
                    $(this).val('');
                    return;
                }
            } else if (valor.toLowerCase().includes("atleta")) {

            }

            // Guardar id para uso posterior
            var selectedOption = $(this).find('option:selected'); // la opción seleccionada
            var idAtleta = selectedOption.data('id');
            idsAtletas.push(idAtleta);
            // console.log("ids guardadas: " + idsAtletas);

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
                ' <button class="btn btn-xs btn-danger pull-right eliminar" data-index="' + index + '" value="' + idsAtletas[index] + '">Quitar</button>' +
                '</li>'
            );
        });

        document.getElementById("idsInput").value = JSON.stringify(idsAtletas);
    }

    // Quitar elementos de la lista
    $('#listaSeleccionados').on('click', '.eliminar', function () {
        var idx = $(this).data('index');
        var item = seleccionados[idx];

        if (item.toLowerCase().includes("entrenador")) {
            contEntrenadores--;
        }

        // Eliminar del arreglo de seleccionados
        seleccionados.splice(idx, 1); // eliminar de memoria
        // console.log(seleccionados);

        // Eliminar id del Atleta de idsAtletas
        var id = parseInt($(this).val(), 10);
        idsAtletas = idsAtletas.filter(num => num !== id);
        // console.log(idsAtletas);

        actualizarLista();
    });


    /**
     * Funcion para x asistente por x cantidad de asitentes
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