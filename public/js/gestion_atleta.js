// CREAR
$(document).ready(function () {
    // Configurar previsualización de imagen
    function setupImagePreview(modalElement) {
        const inputFile = modalElement.querySelector(".fotoAtletaInput");
        const previewImage = modalElement.querySelector(".previewImage");
        const previewText = modalElement.querySelector(".previewText");
        const removeBtn = modalElement.querySelector(".removeImageBtn");

        if (inputFile && previewImage && previewText && removeBtn) {
            inputFile.addEventListener("change", function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = "block";
                        previewText.style.display = "none";
                        removeBtn.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeBtn.addEventListener("click", function() {
                previewImage.src = "";
                previewImage.style.display = "none";
                previewText.style.display = "block";
                removeBtn.style.display = "none";
                inputFile.value = "";
            });
        }
    }

    // Configurar previsualización en el modal
    const atletaModal = document.getElementById("modalAtleta");
    if (atletaModal) {
        setupImagePreview(atletaModal);
    }

    function toggleCampos(tipo) {
        if (tipo === "Nacional") {
            $("#nombre, #primer_apellido, #segundo_apellido, #fecha_nacimiento")
                .val("")
                .prop("readonly", true);
        } else {
            $("#nombre, #primer_apellido, #segundo_apellido, #fecha_nacimiento")
                .prop("readonly", false);
        }
    }

    function actualizarDivision(fecha) {
        if (!fecha) {
            $("#division").val("");
            return;
        }
        $.ajax({
            url: "/calcular-division/" + fecha,
            type: "GET",
            success: function(data) {
                if (data.division) {
                    $("#division").val(data.division);
                } else {
                    $("#division").val("No disponible");
                }
            },
            error: function() {
                $("#division").val("Error al calcular");
            }
        });
    }

    // Cambio de tipo_identificacion
    $("#tipo_identificacion").on("change", function () {
        toggleCampos($(this).val());
        $("#nombre, #primer_apellido, #segundo_apellido, #fecha_nacimiento").val("");
        $("#division").val("");
    });

    // Buscar en padrón cuando pierde foco identificación
    $("#identificacion").on("blur", function () {
        if ($("#tipo_identificacion").val() === "Nacional") {
            let identificacion = $(this).val();
            if (identificacion) {
                $.ajax({
                    url: "/buscar-padron/" + identificacion,
                    type: "GET",
                    success: function (data) {
                        if (data.found) {
                            $("#nombre").val(data.nombre);
                            $("#primer_apellido").val(data.primer_apellido);
                            $("#segundo_apellido").val(data.segundo_apellido);
                            $("#fecha_nacimiento").val(data.fecha_nacimiento);
                            actualizarDivision(data.fecha_nacimiento);
                        } else {
                            alert("No se encontró en el padrón");
                            $("#nombre, #primer_apellido, #segundo_apellido, #fecha_nacimiento").val("");
                            $("#division").val("");
                        }
                    },
                    error: function () {
                        alert("Error al consultar el padrón");
                    }
                });
            }
        }
    });

    // Actualizar división al cambiar fecha de nacimiento (para "Otro")
    $("#fecha_nacimiento").on("change", function() {
        let fecha = $(this).val();
        actualizarDivision(fecha);
    });

    // Manejo del formulario
    $('#formRegistrarAtleta').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this); // Usar FormData para soportar imagen
        var actionUrl = $(this).attr('action');
        var $errorMessages = $('#errorMessages');

        $errorMessages.addClass('d-none').empty();

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false, // Necesario para FormData
            contentType: false, // Necesario para FormData
            success: function (response) {
                console.log('Éxito: Registro completado', response); // Para depuración
                $('#modalAtleta').modal('hide');
                alert(response.message);
                $('#formRegistrarAtleta')[0].reset(); // Corregido: usar referencia directa al formulario
                // Resetear previsualización de imagen
                document.querySelector(".previewImage").style.display = "none";
                document.querySelector(".previewText").style.display = "block";
                document.querySelector(".removeImageBtn").style.display = "none";
                window.location.href = window.location.href; // Refrescar la página
            },
            error: function (xhr) {
                console.log('Respuesta del servidor:', xhr.responseText);
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON?.errors || { error: [xhr.responseJSON?.error] };
                    if (typeof errors === 'string') {
                        $errorMessages.append('<p>' + errors + '</p>');
                    } else {
                        $.each(errors, function (key, error) {
                            $errorMessages.append('<p>' + (Array.isArray(error) ? error[0] : error) + '</p>');
                        });
                    }
                    $errorMessages.removeClass('d-none');
                } else if (xhr.status === 500) {
                    $errorMessages.text('Error interno del servidor: ' + (xhr.responseJSON?.error || 'Revisa los logs de Laravel')).removeClass('d-none');
                    alert('Error 500: ' + (xhr.responseJSON?.error || 'Error desconocido'));
                } else {
                    alert('Error al registrar el atleta. Por favor, intenta de nuevo.');
                }
            }
        });
    });
});



// ACTUALIZAR
$(document).ready(function () {
    // Setup image preview for edit modal
    const editarModal = document.getElementById("modalEditarAtleta");
    if (editarModal) {
        setupImagePreview(editarModal);
    }

    // Populate edit modal with athlete data
    $('.btn-edit').click(function (e) {
        e.preventDefault();
        let atletaId = $(this).data('id');
        console.log('Click en editar, ID:', atletaId);

        $.get('/atletas/' + atletaId + '/datos', function (data) {
            console.log('Datos recibidos:', data);

            $('#e_tipo_identificacion').val(data.tipo_identificacion);
            $('#e_identificacion').val(data.identificacion);
            $('#e_nombre').val(data.nombre);
            $('#e_apellido1').val(data.primer_apellido);
            $('#e_apellido2').val(data.segundo_apellido);
            $('#e_rol').val(data.rol);
            $('#e_sexo').val(data.sexo.toLowerCase());
            $('#e_fecha_nacimiento').val(data.fecha_nacimiento);
            $('#e_grado').val(data.id_grado);


            if ($("#e_academia option[value='" + data.id_academia + "']").length === 0) {
                // Solo se agrega si no está en la lista (porque es inactiva)
                $("#e_academia").append(
                    $("<option>", {
                        value: data.id_academia,
                        text: data.academias.nombre
                    })
                );
            }

            $('#e_academia').val(data.id_academia);
            $('input[name="estado"][value="' + data.estado + '"]').prop('checked', true);

            // Handle image preview
            const previewImage = $('#modalEditarAtleta').find('.previewImage');
            const previewText = $('#modalEditarAtleta').find('.previewText');
            const removeBtn = $('#modalEditarAtleta').find('.removeImageBtn');
            const inputFile = $('#modalEditarAtleta').find('#e_fotoAtletaEditar');
            const removeImagenInput = $('#modalEditarAtleta').find('#removeImagen');

            previewText.text('Sin foto');
            if (data.imagen && data.imagen !== '') {
                previewImage.attr('src', '/storage/' + data.imagen).css('display', 'block');
                previewText.css('display', 'none');
                removeBtn.css('display', 'inline-block');
            } else {
                previewImage.attr('src', '').css('display', 'none');
                previewText.css('display', 'block');
                removeBtn.css('display', 'none');
            }
            inputFile.val('');
            removeImagenInput.val('0');

            $('#formEditarAtleta').attr('action', '/atletas/' + atletaId);

            // Show modal
            let modal = new bootstrap.Modal(document.getElementById('modalEditarAtleta'));
            modal.show();
        });
    });

    // Handle form submission
    $('#formEditarAtleta').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = new FormData(this); // Use FormData to handle file uploads

        $.ajax({
            url: actionUrl,
            type: 'POST', // Use POST with _method=PUT
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-HTTP-Method-Override': 'PUT' // Override to PUT
            },
            success: function (response) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $('#modalEditarAtleta').modal('hide');
                    form[0].reset();
                    window.location.reload();
                });
            },
            error: function (xhr) {
                let errorMessage = 'Error al actualizar el atleta.';
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.identificacion) {
                        let originalMessage = errors.identificacion[0];
                        if (originalMessage.includes('has already been taken')) {
                            errorMessage = 'Ya existe un/a atleta con esa identificación.';
                        } else {
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }
                    } else {
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                } else if (xhr.status === 500) {
                    errorMessage = xhr.responseJSON.error || 'Error interno del servidor.';
                }

                Swal.fire({
                    title: 'Error',
                    html: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    // Image preview setup
    function setupImagePreview(modalElement) {
        const inputFile = modalElement.querySelector(".fotoAtletaInput");
        const previewImage = modalElement.querySelector(".previewImage");
        const previewText = modalElement.querySelector(".previewText");
        const removeBtn = modalElement.querySelector(".removeImageBtn");
        const removeImagenInput = modalElement.querySelector("#removeImagen");

        if (inputFile && previewImage && previewText && removeBtn && removeImagenInput) {
            inputFile.addEventListener("change", function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = "block";
                        previewText.style.display = "none";
                        removeBtn.style.display = "inline-block";
                        removeImagenInput.value = "0";
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeBtn.addEventListener("click", function () {
                previewImage.src = "";
                previewImage.style.display = "none";
                previewText.style.display = "block";
                removeBtn.style.display = "none";
                inputFile.value = "";
                removeImagenInput.value = "1";
            });
        }
    }
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

