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
                            // Reemplazo de alert por SweetAlert
                            Swal.fire({
                                icon: 'warning',
                                title: 'Identificación no encontrada',
                                text: 'No se encontró una persona con esa identificación en el padrón.',
                                confirmButtonColor: '#3085d6'
                            });
                            $("#nombre, #primer_apellido, #segundo_apellido, #fecha_nacimiento").val("");
                            $("#division").val("");
                        }
                    },
                    error: function () {
                        // Reemplazo de alert por SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'Hubo un error al consultar el padrón. Por favor, intenta de nuevo.',
                            confirmButtonColor: '#d33'
                        });
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
        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // SweetAlert para éxito con temporizador
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2500 // El mensaje se cerrará automáticamente en 2.5 segundos
                }).then(() => {
                    $('#modalAtleta').modal('hide');
                    $('#formRegistrarAtleta')[0].reset();
                    // Resetear previsualización de imagen
                    document.querySelector(".previewImage").style.display = "none";
                    document.querySelector(".previewText").style.display = "block";
                    document.querySelector(".removeImageBtn").style.display = "none";
                    window.location.href = window.location.href;
                });
            },
            error: function (xhr) {
                let errorText = 'Ha ocurrido un error. Por favor, intenta de nuevo.';

                if (xhr.responseJSON) {
                    // Manejo de errores de validación de Laravel (422)
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors || {};
                        const firstError = Object.values(errors)[0] || [xhr.responseJSON.error];
                        errorText = Array.isArray(firstError) ? firstError[0] : firstError;
                    } else if (xhr.status === 500) {
                        errorText = xhr.responseJSON.error || 'Error interno del servidor.';
                    }
                }

                // SweetAlert para errores
                Swal.fire({
                    icon: 'error',
                    title: 'Error al registrar',
                    text: errorText,
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});


// ACTUALIZAR
$(document).ready(function () {
    // Función para configurar la previsualización de la imagen
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

    // Función para alternar la lectura de los campos de datos personales
    function toggleCamposEditar(tipo) {
        const fields = $('#e_nombre, #e_apellido1, #e_apellido2, #e_fecha_nacimiento');
        if (tipo === "Nacional") {
            fields.prop("readonly", true);
        } else {
            fields.prop("readonly", false);
        }
    }

    // Función para calcular y mostrar la división
    function actualizarDivisionEditar(fecha) {
        const divisionField = $('#e_division');
        if (!fecha) {
            divisionField.val("");
            return;
        }
        $.ajax({
            url: "/calcular-division/" + fecha,
            type: "GET",
            success: function(data) {
                if (data.division) {
                    divisionField.val(data.division);
                } else {
                    divisionField.val("No disponible");
                }
            },
            error: function() {
                divisionField.val("Error al calcular");
            }
        });
    }

    // Configurar la previsualización de la imagen en el modal de edición
    const editarModal = document.getElementById("modalEditarAtleta");
    if (editarModal) {
        setupImagePreview(editarModal);
    }

    // Rellenar el modal de edición con los datos del atleta
    $(document).on('click', '.btn-edit', function (e) {
        e.preventDefault();
        let atletaId = $(this).data('id');

        $.get('/atletas/' + atletaId + '/datos', function (data) {
            $('#e_tipo_identificacion').val(data.tipo_identificacion);
            $('#e_identificacion').val(data.identificacion);
            $('#e_nombre').val(data.nombre);
            $('#e_apellido1').val(data.primer_apellido);
            $('#e_apellido2').val(data.segundo_apellido);
            $('#e_rol').val(data.rol);
            $('#e_sexo').val(data.sexo);
            $('#e_fecha_nacimiento').val(data.fecha_nacimiento);
            $('#e_grado').val(data.id_grado);

            if ($("#e_academia option[value='" + data.id_academia + "']").length === 0) {
                $("#e_academia").append(
                    $("<option>", {
                        value: data.id_academia,
                        text: data.academias.nombre
                    })
                );
            }
            $('#e_academia').val(data.id_academia);
            $('input[name="estado"][value="' + data.estado + '"]').prop('checked', true);

            // Manejo de la imagen
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

            // Llamar a las funciones para el modal de edición
            toggleCamposEditar(data.tipo_identificacion);
            actualizarDivisionEditar(data.fecha_nacimiento);

            let modal = new bootstrap.Modal(document.getElementById('modalEditarAtleta'));
            modal.show();
        });
    });

    // Evento para el cambio de tipo de identificación en el modal de edición
    $("#e_tipo_identificacion").on("change", function () {
        toggleCamposEditar($(this).val());
        // Limpiar campos relacionados cuando el tipo de identificación cambia
        $("#e_identificacion, #e_nombre, #e_apellido1, #e_apellido2, #e_fecha_nacimiento").val("");
        $("#e_division").val("");
    });

    // Evento para buscar en el padrón al perder el foco en la identificación
    $("#e_identificacion").on("blur", function () {
        if ($("#e_tipo_identificacion").val() === "Nacional") {
            let identificacion = $(this).val();
            if (identificacion) {
                $.ajax({
                    url: "/buscar-padron/" + identificacion,
                    type: "GET",
                    success: function (data) {
                        if (data.found) {
                            $("#e_nombre").val(data.nombre);
                            $("#e_apellido1").val(data.primer_apellido);
                            $("#e_apellido2").val(data.segundo_apellido);
                            $("#e_fecha_nacimiento").val(data.fecha_nacimiento);
                            actualizarDivisionEditar(data.fecha_nacimiento);
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Identificación no encontrada',
                                text: 'No se encontró una persona con esa identificación en el padrón.',
                                confirmButtonColor: '#3085d6'
                            });
                            $("#e_nombre, #e_apellido1, #e_apellido2, #e_fecha_nacimiento").val("");
                            $("#e_division").val("");
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'Hubo un error al consultar el padrón. Por favor, intenta de nuevo.',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            }
        }
    });

    // Evento para actualizar la división al cambiar la fecha de nacimiento
    $("#e_fecha_nacimiento").on("change", function() {
        let fecha = $(this).val();
        actualizarDivisionEditar(fecha);
    });

    // Manejo del envío del formulario de edición
    $('#formEditarAtleta').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = new FormData(this);

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-HTTP-Method-Override': 'PUT'
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

// Campo identificación
const tipoSelect = document.getElementById('tipo_identificacion');
    const identificacionInput = document.getElementById('identificacion');

    tipoSelect.addEventListener('change', function () {
        if (this.value !== "") {
            identificacionInput.disabled = false;
            identificacionInput.focus();
        } else {
            identificacionInput.disabled = true;
            identificacionInput.value = "";
        }
    });

