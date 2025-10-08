function initDataTable(config) {
    // Destruir tabla existente si ya hay una
    if ($.fn.DataTable.isDataTable('#tabla')) {
        $('#tabla').DataTable().clear().destroy();
    }

    const table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false, // No dejar que DataTables calcule anchos automáticos
        scrollX: true,    // Scroll horizontal estable
        scrollCollapse: true, // Evita que se "rompa" el ancho al reducir pantalla
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
            // Ajustar después de inicializar
            setTimeout(() => {
                table.columns.adjust().draw(false);
                if (table.responsive) table.responsive.recalc();
            }, 200);
        },
        drawCallback: function () {
            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });

    // Recalcular columnas al redimensionar ventana (DevTools, resize, etc.)
    let resizeTimer;
    $(window).on('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if ($.fn.DataTable.isDataTable('#tabla')) {
                table.columns.adjust().draw(false);
                if (table.responsive) table.responsive.recalc();
            }
        }, 250);
    });

    // Recalcular al mostrar o cerrar modales (Bootstrap)
    $('.modal').on('shown.bs.modal hidden.bs.modal', function () {
        setTimeout(() => {
            if ($.fn.DataTable.isDataTable('#tabla')) {
                table.columns.adjust().draw(false);
                if (table.responsive) table.responsive.recalc();
            }
        }, 250);
    });

    // Recalcular cuando se abre/cierra el sidebar
    $('#toggleSidebar').on('click', function () {
        setTimeout(() => {
            if ($.fn.DataTable.isDataTable('#tabla')) {
                table.columns.adjust().draw(false);
                if (table.responsive) table.responsive.recalc();
            }
        }, 400);
    });

    // Ajustar al volver a la ventana
    $(window).on('focus', function () {
        setTimeout(() => {
            if ($.fn.DataTable.isDataTable('#tabla')) {
                table.columns.adjust().draw(false);
                if (table.responsive) table.responsive.recalc();
            }
        }, 250);
    });

    return table;
}
