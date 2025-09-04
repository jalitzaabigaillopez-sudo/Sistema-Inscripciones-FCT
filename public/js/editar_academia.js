$(document).ready(function () {
    // Setup image preview for edit modal
    const editarModal = document.getElementById("modalEditarAcademia");
    if (editarModal) {
        setupImagePreview(editarModal);
    }

    // Reiniciar el modal al cerrarse
    $('#modalEditarAcademia').on('hidden.bs.modal', function () {
        const form = $('#formEditarAcademia');
        const previewImage = $('#modalEditarAcademia').find('.previewImage');
        const previewText = $('#modalEditarAcademia').find('.previewText');
        const removeBtn = $('#modalEditarAcademia').find('.removeImageBtn');
        const inputFile = $('#modalEditarAcademia').find('#fotoAcademiaEditar');
        const removeImagenInput = $('#modalEditarAcademia').find('#removeImagen');

        // Limpiar el formulario
        form[0].reset();
        // Reiniciar selects
        $('#cantonAcademiaEditar').html('<option value="" disabled selected>Seleccione un cantón...</option>');
        $('#distritoAcademiaEditar').html('<option value="" disabled selected>Seleccione un distrito...</option>');
        // Reiniciar elementos de la imagen
        previewImage.attr('src', '').css('display', 'none');
        previewText.text('Sin foto').css('display', 'block');
        removeBtn.css('display', 'none');
        inputFile.val('');
        removeImagenInput.val('0');
        console.log('Modal reiniciado al cerrarse');
    });

    // Handle edit button click to populate the modal
    $('.btn-edit').click(function () {
        var academiaId = $(this).data('id');
        var form = $('#formEditarAcademia');

        // Limpiar el formulario y resetear los selects
        form[0].reset();
        $('#cantonAcademiaEditar').html('<option value="" disabled selected>Seleccione un cantón...</option>');
        $('#distritoAcademiaEditar').html('<option value="" disabled selected>Seleccione un distrito...</option>');

        // Reiniciar elementos de la imagen antes de cargar nuevos datos
        const previewImage = $('#modalEditarAcademia').find('.previewImage');
        const previewText = $('#modalEditarAcademia').find('.previewText');
        const removeBtn = $('#modalEditarAcademia').find('.removeImageBtn');
        const inputFile = $('#modalEditarAcademia').find('#fotoAcademiaEditar');
        const removeImagenInput = $('#modalEditarAcademia').find('#removeImagen');

        previewImage.attr('src', '').css('display', 'none');
        previewText.text('Sin foto').css('display', 'block');
        removeBtn.css('display', 'none');
        inputFile.val('');
        removeImagenInput.val('0');

        // AJAX request to fetch academy data
        $.ajax({
            url: '/academias/' + academiaId + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Populate form fields
                $('#nombreAcademiaEditar').val(data.nombre);
                $('#profesorAcademiaEditar').val(data.profesor_encargado);
                $('#telefonoAcademiaEditar').val(data.telefono);
                $('#correoAcademiaEditar').val(data.correo);
                $('#direccionAcademiaEditar').val(data.direccion);

                var estadoDB = data.estado.toLowerCase();
                $('input[name="estado"][value="' + estadoDB + '"]').prop('checked', true);

                // Populate image preview
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

                // Populate location fields
                var provinciaId = data.distrito.canton.provincia.id_provincia;
                var cantonId = data.distrito.canton.id_canton;
                var distritoId = data.id_distrito;

                $('#provinciaAcademiaEditar').val(provinciaId);

                loadCantones(provinciaId, function () {
                    $('#cantonAcademiaEditar').val(cantonId);
                    loadDistritos(cantonId, function () {
                        $('#distritoAcademiaEditar').val(distritoId);
                    });
                });

                // Set form action and show modal
                form.attr('action', '/academias/' + academiaId);
                $('#modalEditarAcademia').modal('show');
            },
            error: function (xhr) {
                console.error('Error al cargar los datos:', xhr.responseText);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudieron cargar los datos de la academia.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    // Handle form submission with FormData
    $('#formEditarAcademia').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = new FormData(this);

        // Log form data for debugging
        console.log('Datos enviados:', [...formData.entries()]);

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
                    $('#modalEditarAcademia').modal('hide');
                    form[0].reset();
                    window.location.reload();
                });
            },
            error: function (xhr) {
                let errorMessage = 'Error al actualizar la academia.';
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        errorMessage = Object.values(errors).flat().join('<br>');
                    } else {
                        errorMessage = xhr.responseJSON.error || 'Error de validación.';
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

    // Load cantons for province
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

    // Load districts for canton
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

    // Handle manual changes to location selects
    $('#provinciaAcademiaEditar').on('change', function () {
        var provinciaId = $(this).val();
        loadCantones(provinciaId);
    });

    $('#cantonAcademiaEditar').on('change', function () {
        var cantonId = $(this).val();
        loadDistritos(cantonId);
    });

    // Image preview setup
    function setupImagePreview(modalElement) {
        const inputFile = modalElement.querySelector(".fotoAcademiaInput");
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
                console.log('remove_imagen set to 1');
            });
        } else {
            console.error('Elementos de vista previa de imagen no encontrados.');
        }
    }

    // Handle deletion confirmation
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