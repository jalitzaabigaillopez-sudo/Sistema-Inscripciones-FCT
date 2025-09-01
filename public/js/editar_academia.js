$(document).ready(function () {
    // Cuando cambia la selección de provincia
    $('#provinciaAcademiaEditar').on('change', function () {
        var provinciaId = $(this).val();
        var cantonSelect = $('#cantonAcademiaEditar');
        var distritoSelect = $('#distritoAcademiaEditar');

        // Limpiar los select de cantón y distrito
        cantonSelect.html('<option value="" disabled selected>Seleccione un cantón...</option>');
        distritoSelect.html('<option value="" disabled selected>Seleccione un distrito...</option>');

        if (provinciaId) {
            // Hacer la petición AJAX para obtener los cantones
            $.ajax({
                url: '/cantones/' + provinciaId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Llenar el select de cantones
                    $.each(data, function (index, canton) {
                        cantonSelect.append('<option value="' + canton.id_canton + '">' + canton.nombre + '</option>');
                    });
                    // Si hay un cantón preseleccionado (al editar), seleccionarlo
                    var cantonId = $('#formEditarAcademia').data('canton-id');
                    if (cantonId) {
                        cantonSelect.val(cantonId).trigger('change');
                    }
                },
                error: function (xhr) {
                    console.error('Error al cargar cantones:', xhr.responseText);
                    alert('No se pudieron cargar los cantones. Por favor, intenta de nuevo.');
                }
            });
        }
    });

    // Cuando cambia la selección de cantón
    $('#cantonAcademiaEditar').on('change', function () {
        var cantonId = $(this).val();
        var distritoSelect = $('#distritoAcademiaEditar');

        // Limpiar el select de distrito
        distritoSelect.html('<option value="" disabled selected>Seleccione un distrito...</option>');

        if (cantonId) {
            // Hacer la petición AJAX para obtener los distritos
            $.ajax({
                url: '/distritos/' + cantonId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Llenar el select de distritos
                    $.each(data, function (index, distrito) {
                        distritoSelect.append('<option value="' + distrito.id_distrito + '">' + distrito.nombre + '</option>');
                    });
                    // Si hay un distrito preseleccionado (al editar), seleccionarlo
                    var distritoId = $('#formEditarAcademia').data('distrito-id');
                    if (distritoId) {
                        distritoSelect.val(distritoId);
                    }
                },
                error: function (xhr) {
                    console.error('Error al cargar distritos:', xhr.responseText);
                    alert('No se pudieron cargar los distritos. Por favor, intenta de nuevo.');
                }
            });
        }
    });

    // Cargar datos iniciales al abrir el modal
    $('#modalEditarAcademia').on('shown.bs.modal', function () {
        var provinciaId = $('#provinciaAcademiaEditar').val();
        if (provinciaId) {
            $('#provinciaAcademiaEditar').trigger('change');
        }
    });
});

  function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envía el formulario usando AJAX para manejar la respuesta
                    $.ajax({
                        url: $('#form-eliminar-' + id).attr('action'),
                        method: $('#form-eliminar-' + id).attr('method'),
                        data: $('#form-eliminar-' + id).serialize(),
                        success: function(response) {
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: 'El registro ha sido eliminado correctamente.',
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Recarga la página o actualiza la tabla
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al intentar eliminar el registro.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }
            });
        }