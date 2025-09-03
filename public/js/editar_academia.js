$(document).ready(function () {

    // 1. Manejar el clic del botón de edición para llenar el modal
    $('.btn-edit').click(function () {
        var academiaId = $(this).data('id');
        var form = $('#formEditarAcademia');

        // Limpiar el formulario y resetear los selects
        form[0].reset();
        $('#cantonAcademiaEditar').html('<option value="" disabled selected>Seleccione un cantón...</option>');
        $('#distritoAcademiaEditar').html('<option value="" disabled selected>Seleccione un distrito...</option>');

        // Petición AJAX para obtener todos los datos de la academia
        $.ajax({
            url: '/academias/' + academiaId + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Rellenar los campos generales del formulario
                $('#nombreAcademiaEditar').val(data.nombre);
                $('#profesorAcademiaEditar').val(data.profesor_encargado);
                $('#telefonoAcademiaEditar').val(data.telefono);
                $('#correoAcademiaEditar').val(data.correo);
                $('#direccionAcademiaEditar').val(data.direccion);
                
                var estadoDB = data.estado.toLowerCase();
                $('input[name="estado"][value="' + estadoDB + '"]').prop('checked', true);

                // Obtener los IDs de las ubicaciones
                var provinciaId = data.distrito.canton.provincia.id_provincia;
                var cantonId = data.distrito.canton.id_canton;
                var distritoId = data.id_distrito;

                // Llenar el select de provincia
                $('#provinciaAcademiaEditar').val(provinciaId);
                
                // Cargar y seleccionar el cantón y distrito de forma secuencial sin usar 'trigger'
                loadCantones(provinciaId, function() {
                    $('#cantonAcademiaEditar').val(cantonId);
                    loadDistritos(cantonId, function() {
                        $('#distritoAcademiaEditar').val(distritoId);
                    });
                });
                
                // Establecer la acción del formulario y mostrar el modal
                form.attr('action', '/academias/' + academiaId);
                $('#modalEditarAcademia').modal('show');
            },
            error: function (xhr) {
                console.error('Error al cargar los datos:', xhr.responseText);
                alert('No se pudieron cargar los datos de la academia. Por favor, intenta de nuevo.');
            }
        });
    });

    // 2. Manejar el envío del formulario de edición con AJAX
    $('#formEditarAcademia').on('submit', function (e) {
        e.preventDefault(); // Evita el envío normal del formulario

        let form = $(this);
        let actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            type: 'PUT',
            data: form.serialize(),
            success: function (response) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: response.success,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    $.each(errors, function (key, error) {
                        errorMessage += error[0] + '<br>';
                    });

                    Swal.fire({
                        title: 'Error de Validación',
                        html: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al actualizar la academia.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
        });
    });

    // 3. Funciones reutilizables para cargar los selects de ubicación
    function loadCantones(provinciaId, callback) {
        var cantonSelect = $('#cantonAcademiaEditar');
        cantonSelect.html('<option value="" disabled selected>Seleccione un cantón...</option>');
        $('#distritoAcademiaEditar').html('<option value="" disabled selected>Seleccione un distrito...</option>');

        if (provinciaId) {
            $.ajax({
                url: '/cantones/' + provinciaId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (index, canton) {
                        cantonSelect.append('<option value="' + canton.id_canton + '">' + canton.nombre + '</option>');
                    });
                    if (callback) callback();
                }
            });
        }
    }

    function loadDistritos(cantonId, callback) {
        var distritoSelect = $('#distritoAcademiaEditar');
        distritoSelect.html('<option value="" disabled selected>Seleccione un distrito...</option>');

        if (cantonId) {
            $.ajax({
                url: '/distritos/' + cantonId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (index, distrito) {
                        distritoSelect.append('<option value="' + distrito.id_distrito + '">' + distrito.nombre + '</option>');
                    });
                    if (callback) callback();
                }
            });
        }
    }

    // 4. Manejar los cambios manuales del usuario en los selects de ubicación
    $('#provinciaAcademiaEditar').on('change', function () {
        var provinciaId = $(this).val();
        loadCantones(provinciaId);
    });

    $('#cantonAcademiaEditar').on('change', function () {
        var cantonId = $(this).val();
        loadDistritos(cantonId);
    });

    // 5. Función para la confirmación de eliminación con SweetAlert2
    window.confirmarEliminacion = function (id) {
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
                $.ajax({
                    url: $('#form-eliminar-' + id).attr('action'),
                    method: $('#form-eliminar-' + id).attr('method'),
                    data: $('#form-eliminar-' + id).serialize(),
                    success: function (response) {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'El registro ha sido eliminado correctamente.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
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
    };
});