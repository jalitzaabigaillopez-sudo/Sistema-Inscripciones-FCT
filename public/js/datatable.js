function initDataTable(config) {
    // Destruir tabla existente si hay una
    if ($.fn.DataTable.isDataTable('#tabla')) {
        $('#tabla').DataTable().destroy();
    }

    const table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false, // IMPORTANTE: Cambiar a false
        scrollX: true,    // Añadir scroll horizontal
        ajax: {
            url: config.ajaxUrl,
            type: config.ajaxType || "GET"
        },
        columns: config.columns,
        language: {
            decimal: "",
            emptyTable: "No hay datos disponibles en la tabla",
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            infoEmpty: "Mostrando 0 a 0 de 0 entradas",
            infoFiltered: "(filtrado de _MAX_ entradas totales)",
            lengthMenu: "Mostrar _MENU_ entradas",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "No se encontraron registros coincidentes",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        pageLength: config.pageLength || 10,
        lengthMenu: config.lengthMenu || [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],
        initComplete: function () {
            // Redimensionar después de inicializar
            setTimeout(() => {
                table.columns.adjust();
                if (table.responsive) {
                    table.responsive.recalc();
                }
            }, 100);
        },
        drawCallback: function () {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });

    // Redimensionamiento agresivo para todos los escenarios
    $(window).on('resize', function () {
        setTimeout(() => {
            table.columns.adjust();
            if (table.responsive) {
                table.responsive.recalc();
            }
        }, 150);
    });

    // Redimensionar cuando se interactúa con el sidebar
    $('#toggleSidebar').on('click', function () {
        setTimeout(() => {
            table.columns.adjust();
            if (table.responsive) {
                table.responsive.recalc();
            }
        }, 350); // Tiempo mayor para esperar la animación completa
    });

    // Redimensionar cuando se cierra cualquier modal
    $('.modal').on('hidden.bs.modal', function () {
        setTimeout(() => {
            table.columns.adjust();
            if (table.responsive) {
                table.responsive.recalc();
            }
        }, 150);
    });

    // Forzar redimensionamiento cada vez que la ventana gana foco
    $(window).on('focus', function () {
        setTimeout(() => {
            table.columns.adjust();
            if (table.responsive) {
                table.responsive.recalc();
            }
        }, 200);
    });

    return table;
}