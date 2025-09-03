// CREAR
$(document).ready(function () {
    $('#formRegistrarAtleta').on('submit', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var actionUrl = $(this).attr('action');
        var $errorMessages = $('#errorMessages');

        $errorMessages.addClass('d-none').empty();

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#modalAtleta').modal('hide');
                alert(response.message);
                // Opcional: recargar la tabla de atletas
                // $('#tablaAtletas').DataTable().ajax.reload();
                window.location.reload(); // O redirigir a la lista de atletas
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.error || xhr.responseJSON.errors;
                    if (typeof errors === 'string') {
                        $errorMessages.append('<p>' + errors + '</p>');
                    } else {
                        $.each(errors, function (key, error) {
                            $errorMessages.append('<p>' + error[0] + '</p>');
                        });
                    }
                    $errorMessages.removeClass('d-none');
                } else {
                    alert('Error al registrar el atleta. Por favor, intenta de nuevo.');
                }
            }
        });
    });
});


// ACTUALIZAR
$(document).ready(function () {
    $('.btn-edit').click(function (e) {
        e.preventDefault();
        let atletaId = $(this).data('id');
        console.log('Click en editar, ID:', atletaId); // <-- Para depurar

        $.get('/atletas/' + atletaId + '/datos', function (data) {
            console.log('Datos recibidos:', data); // <-- Para depurar

            $('#e_tipo_identificacion').val(data.tipo_identificacion);
            $('#e_identificacion').val(data.identificacion);
            $('#e_nombre').val(data.nombre);
            $('#e_apellido1').val(data.primer_apellido);
            $('#e_apellido2').val(data.segundo_apellido);
            $('#e_rol').val(data.rol);
            $('#e_sexo').val(data.sexo.toLowerCase());
            $('#e_fecha_nacimiento').val(data.fecha_nacimiento);
            $('#e_grado').val(data.id_grado);
            $('#e_academia').val(data.id_academia);
            // $('#e_categoria').val(data.categorias.division);
            $('input[name="estado"][value="' + data.estado + '"]').prop('checked', true);

            $('#formEditarAtleta').attr('action', '/atletas/' + atletaId);

            // Abrir modal
            let modal = new bootstrap.Modal(document.getElementById('modalEditarAtleta'));
            modal.show();
        });
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
                success: function (response) {
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
}

$(document).ready(function () {
    $('#formEditarAtleta').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = form.serialize();

        $.ajax({
            url: actionUrl,
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Atleta actualizado correctamente.',
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

                    if (errors.identificacion) {
                        let originalMessage = errors.identificacion[0];
                        if (originalMessage.includes('has already been taken')) {
                            errorMessage = 'Ya existe un/a atleta con esa identificación.';
                        } else {
                            errorMessage = originalMessage;
                        }
                    } else {
                        $.each(errors, function (key, error) {
                            errorMessage += error[0] + '<br>';
                        });
                    }

                    Swal.fire({
                        title: 'Error de Validación',
                        html: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al actualizar el atleta. Por favor, inténtalo de nuevo.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
        });
    });
});