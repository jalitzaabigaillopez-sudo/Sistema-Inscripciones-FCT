$(document).ready(function () {
    $('#bExportar').on('click', function () {
        let id_evento = $('#reporte-evento-select').val();
        // let texto = $('#reporte-evento-select option:selected').text();

        // Abre el PDF en otra pestaña o fuerza descarga
        window.open('/reporte/' + id_evento, '_blank');
    });
});