
function initDataTable(config) {
    // Destruir instancia previa
    if ($.fn.DataTable.isDataTable('#tabla')) {
        $('#tabla').DataTable().clear().destroy();
    }

    const table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollCollapse: true,
        fixedHeader: true,
        responsive: false,
        autoWidth: false,
        deferRender: true,

        ajax: {
            url: config.ajaxUrl,
            type: config.ajaxType || "GET"
        },
        columns: config.columns,
        language: {
            emptyTable: "No hay datos disponibles",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            lengthMenu: "Mostrar _MENU_ registros",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "No se encontraron coincidencias",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        pageLength: config.pageLength || 10,
        lengthMenu: config.lengthMenu || [[10, 25, 50, 100], [10, 25, 50, 100]],

        initComplete: function () {
            // Ajuste inicial (solo una vez)
            setTimeout(() => {
                table.columns.adjust();
                if (table.fixedHeader) table.fixedHeader.adjust?.();
            }, 300);
        },

        drawCallback: function () {
            // Solo tooltips (sin redibujar)
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
        }
    });

    let lastWidth = $('.table-responsive').width();

    const ajustarTabla = _.debounce(() => {
        if ($.fn.DataTable.isDataTable('#tabla')) {
            const table = $('#tabla').DataTable();
            const currentWidth = $('.table-responsive').width();

            if (Math.abs(currentWidth - lastWidth) > 30) {
                lastWidth = currentWidth;
                table.columns.adjust();

                //  Verificar que fixedHeader exista antes de usarlo
                if (table.fixedHeader && typeof table.fixedHeader.adjust === 'function') {
                    table.fixedHeader.adjust();
                }
            }
        }
    }, 300);

    // Eventos comunes que solo recalculan tamaños
    $(window).on('resize', ajustarTabla);
    $('.modal').on('shown.bs.modal hidden.bs.modal', ajustarTabla);
    $('#toggleSidebar').on('click', () => setTimeout(ajustarTabla, 400));
    $(window).on('focus', ajustarTabla);

    return table;
}