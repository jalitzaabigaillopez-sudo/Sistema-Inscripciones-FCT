// CREAR
// --- Lógica para el modal de CREAR ACADEMIA ---
// Cargar cantones al cambiar la provincia en el modal de creación
$('#provinciaAcademia').on('change', function () {
    var provinciaId = $(this).val();
    var cantonSelect = $('#cantonAcademia');
    var distritoSelect = $('#distritoAcademia');

    cantonSelect.html('<option value="" disabled selected>Cargando cantones...</option>');
    distritoSelect.html('<option value="" disabled selected>Seleccione un distrito...</option>');

    if (provinciaId) {
        $.ajax({
            url: '/cantones/' + provinciaId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                cantonSelect.html('<option value="" disabled selected>Seleccione un cantón...</option>');
                $.each(data, function (index, canton) {
                    cantonSelect.append('<option value="' + canton.id_canton + '">' + canton.nombre + '</option>');
                });
            },
            error: function (xhr) {
                cantonSelect.html('<option value="" disabled selected>Error al cargar cantones</option>');
                console.error('Error al cargar cantones:', xhr.responseText);
            }
        });
    }
});

// Cargar distritos al cambiar el cantón en el modal de creación
$('#cantonAcademia').on('change', function () {
    var cantonId = $(this).val();
    var distritoSelect = $('#distritoAcademia');

    distritoSelect.html('<option value="" disabled selected>Cargando distritos...</option>');

    if (cantonId) {
        $.ajax({
            url: '/distritos/' + cantonId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                distritoSelect.html('<option value="" disabled selected>Seleccione un distrito...</option>');
                $.each(data, function (index, distrito) {
                    distritoSelect.append('<option value="' + distrito.id_distrito + '">' + distrito.nombre + '</option>');
                });
            },
            error: function (xhr) {
                distritoSelect.html('<option value="" disabled selected>Error al cargar distritos</option>');
                console.error('Error al cargar distritos:', xhr.responseText);
            }
        });
    }
});

// Resetear el modal de creación al cerrarse
$('#modalAcademia').on('hidden.bs.modal', function () {
    const form = $('#formCrearAcademia');
    form[0].reset();
    $('#cantonAcademia').html('<option value="" disabled selected>Seleccione un cantón...</option>');
    $('#distritoAcademia').html('<option value="" disabled selected>Seleccione un distrito...</option>');
});

// ============================================
// 🖼️ LÓGICA DE VISTA PREVIA Y ELIMINACIÓN DE IMAGEN EN CREAR
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const crearModal = document.getElementById("modalAcademia");
    if (crearModal) {
        setupImagePreview(crearModal);
    }

    // Reiniciar imagen al cerrar el modal de creación
    $('#modalAcademia').on('hidden.bs.modal', function () {
        const previewImage = $(this).find('.previewImage');
        const previewText = $(this).find('.previewText');
        const removeBtn = $(this).find('.removeImageBtn');
        const inputFile = $(this).find('#fotoAcademiaCrear');

        // Reinicia la vista previa y los valores
        previewImage.attr('src', '').hide();
        previewText.text('Sin foto').show();
        removeBtn.hide();
        inputFile.val('');
    });
});

// ============================================
// 🧾 Envío del formulario de creación por AJAX
// ============================================
$(document).ready(function () {
    $('#formCrearAcademia').on('submit', function (e) {
        e.preventDefault(); // ❌ Evita el submit tradicional (no recarga)

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = new FormData(this);

        // Mostrar cargando
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor, espera unos segundos',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message || 'La academia se ha registrado correctamente.',
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $('#modalAcademia').modal('hide');
                    form[0].reset();
                    window.location.reload();
                });
            },
            error: function (xhr) {
                Swal.close(); // Cierra el "Procesando..."

                if (xhr.status === 422) {
                    // ⚠️ Errores de validación
                    let errors = xhr.responseJSON.errors;
                    let errorList = '';
                    Object.values(errors).forEach(msgArr => {
                        msgArr.forEach(msg => errorList += '• ' + msg + '\n');
                    });

                    Swal.fire({
                        icon: 'warning',
                        title: 'Errores en el formulario',
                        text: errorList,
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'Corregir'
                    });

                } else {
                    // ❌ Error general
                    let msg = xhr.responseJSON?.error || 'Ocurrió un error inesperado. Inténtalo nuevamente.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
        });
    });
});


// ============================================
// 🧩 FUNCIÓN COMPARTIDA setupImagePreview()
// ============================================
function setupImagePreview(modalElement) {
    const inputFile = modalElement.querySelector(".fotoAcademiaInput");
    const previewImage = modalElement.querySelector(".previewImage");
    const previewText = modalElement.querySelector(".previewText");
    const removeBtn = modalElement.querySelector(".removeImageBtn");

    if (inputFile && previewImage && previewText && removeBtn) {
        inputFile.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = "block";
                    previewText.style.display = "none";
                    removeBtn.style.display = "inline-block";
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
            console.log('Imagen removida antes de guardar.');
        });
    } else {
        console.error('⚠️ Elementos de vista previa de imagen no encontrados en el modal:', modalElement.id);
    }
}



// EDITAR
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
    $(document).on('click', '.btn-edit', function (e) {
        e.preventDefault();

        const academiaId = $(this).data('id');
        const form = $('#formEditarAcademia');

        console.log('🟢 Editar academia ID:', academiaId);

        // Resetear form e imagen antes de cargar datos
        form[0].reset();
        $('#cantonAcademiaEditar').html('<option value="" disabled selected>Seleccione un cantón...</option>');
        $('#distritoAcademiaEditar').html('<option value="" disabled selected>Seleccione un distrito...</option>');

        const previewImage = $('#modalEditarAcademia').find('.previewImage');
        const previewText = $('#modalEditarAcademia').find('.previewText');
        const removeBtn = $('#modalEditarAcademia').find('.removeImageBtn');
        const inputFile = $('#modalEditarAcademia').find('#fotoAcademiaEditar');
        const removeImagenInput = $('#modalEditarAcademia').find('#removeImagen');

        previewImage.hide();
        previewText.text('Sin foto').show();
        removeBtn.hide();
        inputFile.val('');
        removeImagenInput.val('0');

        // AJAX para obtener los datos
        $.ajax({
            url: '/academias/' + academiaId + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log('✅ Datos cargados:', data);

                $('#nombreAcademiaEditar').val(data.nombre);
                $('#profesorAcademiaEditar').val(data.profesor_encargado);
                $('#telefonoAcademiaEditar').val(data.telefono);
                $('#correoAcademiaEditar').val(data.correo);
                $('#usuarioAcademiaEditar').val(data.usuario ? data.usuario.nombre_completo : 'Sin usuario asignado');

                $('#direccionAcademiaEditar').val(data.direccion);

                $('input[name="estado"][value="' + data.estado.toLowerCase() + '"]').prop('checked', true);

                if (data.imagen && data.imagen !== '') {
                    previewImage.attr('src', '/storage/' + data.imagen).show();
                    previewText.hide();
                    removeBtn.show();
                }

                const provinciaId = data.distrito.canton.provincia.id_provincia;
                const cantonId = data.distrito.canton.id_canton;
                const distritoId = data.id_distrito;

                $('#provinciaAcademiaEditar').val(provinciaId);
                loadCantones(provinciaId, function () {
                    $('#cantonAcademiaEditar').val(cantonId);
                    loadDistritos(cantonId, function () {
                        $('#distritoAcademiaEditar').val(distritoId);
                    });
                });

                // ✅ Actualizar action y mostrar modal
                form.attr('action', '/academias/' + academiaId);
                $('#modalEditarAcademia').modal('show');
            },
            error: function (xhr) {
                console.error('❌ Error al cargar datos:', xhr.responseText);
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
                    confirmButtonColor: '#3085d6',
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
                    confirmButtonColor: '#3085d6',
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
                    url: `/academias/${id}`, 
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: response.message || 'El registro ha sido eliminado correctamente.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#tabla').DataTable().ajax.reload(null, false); // recargar sin perder paginación
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON?.error || 'Ocurrió un error al intentar eliminar el registro.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    };

});