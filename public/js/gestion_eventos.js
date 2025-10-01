// CREAR
document.addEventListener("DOMContentLoaded", function () {
    // Setup modal and image preview
    const crearModal = document.getElementById("modalEvento");
    if (crearModal) {
        setupImagePreview(crearModal);
    }

    // Setup form submission
    const formCrearEvento = document.getElementById("create-event-form");
    const guardarBtn = document.querySelector("#modalEvento .btn-success");

    if (formCrearEvento && guardarBtn) {
        guardarBtn.addEventListener("click", function (e) {
            e.preventDefault();

            // Client-side validation
            const nombre = document.getElementById('nombreEvento').value;
            const startDate = new Date(document.getElementById('fechaInicio').value);
            const endDate = new Date(document.getElementById('fechaFin').value);
            const regStartDate = new Date(document.getElementById('fechaInicioInscripcion').value);
            const regEndDate = new Date(document.getElementById('fechaFinInscripcion').value);
            const tipoEvento = document.getElementById('id_tipo_evento').value;

            let hasError = false;
            let errorMessage = '';

            // Check required fields
            if (!nombre) {
                errorMessage += 'El nombre del evento es obligatorio. ';
                hasError = true;
            }
            if (!tipoEvento) {
                errorMessage += 'El tipo de evento es obligatorio. ';
                hasError = true;
            }
            if (!document.getElementById('fechaInicio').value || !document.getElementById(
                'fechaFin').value ||
                !document.getElementById('fechaInicioInscripcion').value || !document
                    .getElementById('fechaFinInscripcion').value) {
                errorMessage += 'Todos los campos de fecha son obligatorios. ';
                hasError = true;
            }

            // Validate date logic
            if (startDate && endDate && endDate < startDate) {
                errorMessage +=
                    'La fecha final del evento no puede ser anterior a la fecha de inicio. ';
                hasError = true;
            }
            if (regStartDate && regEndDate && regEndDate < regStartDate) {
                errorMessage +=
                    'La fecha final de inscripción no puede ser anterior a la fecha de inicio de inscripción. ';
                hasError = true;
            }
            if (regEndDate && startDate && regEndDate > startDate) {
                errorMessage +=
                    'Las inscripciones deben finalizar antes de la fecha de inicio del evento. ';
                hasError = true;
            }

            if (hasError) {
                Swal.fire({
                    title: '¡Error de Validación!',
                    html: `<p>${errorMessage}</p>`,
                    icon: 'warning',
                    confirmButtonText: 'Corregir'
                });
                return;
            }

            // Proceed with AJAX submission
            const formData = new FormData(formCrearEvento);
            console.log([...formData]); // Log form data for debugging

            $.ajax({
                url: storeEventUrl, // Usa la variable global que definiste en la vista
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#modalEvento').modal('hide');
                            formCrearEvento.reset();
                            document.querySelector(".previewImage").style
                                .display = "none";
                            document.querySelector(".previewText").style
                                .display = "block";
                            document.querySelector(".removeImageBtn").style
                                .display = "none";
                            location.reload();
                        });
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'Ocurrió un error al registrar el evento.';
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors || {};
                        errorMessage = Object.values(errors).flat().join('<br>');
                    } else if (xhr.status === 500) {
                        errorMessage = xhr.responseJSON?.error ||
                            'Error interno del servidor.';
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
    }

    // Image preview setup
    function setupImagePreview(modalElement) {
        const inputFile = modalElement.querySelector(".imagenEventoInput");
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
                        removeBtn.style.display = "block";
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
            });
        }
    }
});

// EDITAR
document.addEventListener("DOMContentLoaded", function () {
    // Configurar vista previa de la imagen para el modal de edición
    const editarModal = document.getElementById("modalEditarEvento");
    if (editarModal) {
        setupImagePreview(editarModal);
    }

    // Configurar el envío del formulario de edición
    const formEditarEvento = document.getElementById("formEditarEvento");
    const guardarBtn = document.querySelector("#modalEditarEvento .btn-success");

    if (formEditarEvento && guardarBtn) {
        guardarBtn.addEventListener("click", function (e) {
            e.preventDefault();

            // Validación del lado del cliente
            const nombre = document.getElementById('editNombreEvento').value;
            const startDate = new Date(document.getElementById('editFechaInicio').value);
            const endDate = new Date(document.getElementById('editFechaFin').value);
            const regStartDate = new Date(document.getElementById('editFechaInicioInscripcion')
                .value);
            const regEndDate = new Date(document.getElementById('editFechaFinInscripcion').value);
            const tipoEvento = document.getElementById('editIdTipoEvento').value;

            // CAMBIO AQUÍ
            const estado = document.querySelector('input[name="estado"]:checked')?.value || '';

            let hasError = false;
            let errorMessage = '';

            // Validar campos obligatorios
            if (!nombre) {
                errorMessage += 'El nombre del evento es obligatorio. ';
                hasError = true;
            }
            if (!tipoEvento) {
                errorMessage += 'El tipo de evento es obligatorio. ';
                hasError = true;
            }
            if (!estado) {
                errorMessage += 'El estado es obligatorio. ';
                hasError = true;
            }
            if (!document.getElementById('editFechaInicio').value || !document.getElementById(
                'editFechaFin').value ||
                !document.getElementById('editFechaInicioInscripcion').value || !document
                    .getElementById('editFechaFinInscripcion').value) {
                errorMessage += 'Todos los campos de fecha son obligatorios. ';
                hasError = true;
            }

            // Validar lógica de fechas
            if (startDate && endDate && endDate < startDate) {
                errorMessage +=
                    'La fecha final del evento no puede ser anterior a la fecha de inicio. ';
                hasError = true;
            }
            if (regStartDate && regEndDate && regEndDate < regStartDate) {
                errorMessage +=
                    'La fecha final de inscripción no puede ser anterior a la fecha de inicio de inscripción. ';
                hasError = true;
            }
            if (regEndDate && startDate && regEndDate > startDate) {
                errorMessage +=
                    'Las inscripciones deben finalizar antes de la fecha de inicio del evento. ';
                hasError = true;
            }

            if (hasError) {
                Swal.fire({
                    title: '¡Error de Validación!',
                    html: `<p>${errorMessage}</p>`,
                    icon: 'warning',
                    confirmButtonText: 'Corregir'
                });
                return;
            }

            // Enviar datos mediante AJAX
            const formData = new FormData(formEditarEvento);
            const eventoId = formEditarEvento.dataset
                .eventoId; // ID del evento desde el atributo data

            // Agregar el método PUT para Laravel
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/eventos/${eventoId}`,
                method: "POST", // Laravel usa POST con _method=PUT para actualizaciones
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#modalEditarEvento').modal('hide');
                            location.reload();
                        });
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'Ocurrió un error al actualizar el evento.';
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors || {};
                        errorMessage = Object.values(errors).flat().join('<br>');
                    } else if (xhr.status === 500) {
                        errorMessage = xhr.responseJSON?.error ||
                            'Error interno del servidor.';
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
    }

    // Configurar vista previa de la imagen
    function setupImagePreview(modalElement) {
        const inputFile = modalElement.querySelector(".imagenEventoInputEditar");
        const previewImage = modalElement.querySelector(".previewImage");
        const previewText = modalElement.querySelector(".previewText");
        const removeBtn = modalElement.querySelector(".removeImageBtn");
        const eliminarImagenInput = modalElement.querySelector('#eliminarImagenEvento');

        if (inputFile && previewImage && previewText && removeBtn && eliminarImagenInput) {
            // Escuchar cambios en el input de archivo
            inputFile.addEventListener("change", function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = "block";
                        previewText.style.display = "none";
                        removeBtn.style.display = "block";
                        // Resetear el campo oculto si se sube una nueva imagen
                        eliminarImagenInput.value = "0";
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Escuchar clic en el botón de eliminar
            removeBtn.addEventListener("click", function () {
                previewImage.src = "";
                previewImage.style.display = "none";
                previewText.style.display = "block";
                removeBtn.style.display = "none";
                inputFile.value = "";
                // Marcar el campo oculto para que el servidor sepa que debe eliminar la imagen
                eliminarImagenInput.value = "1";
            });
        }
    }

    // Función para cargar datos en el modal de edición
    function cargarDatosEvento(eventoId) {
        console.log('Cargando datos para el evento ID:', eventoId); // Depuración
        $.ajax({
            url: `/eventos/${eventoId}/edit`,
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Respuesta del servidor:', response); // Depuración
                if (response.success) {
                    const evento = response.evento;
                    document.getElementById('editNombreEvento').value = evento.nombre;
                    document.getElementById('editDescripcionEvento').value = evento
                        .descripcion || '';
                    document.getElementById('editFechaInicioInscripcion').value = evento
                        .fecha_inicio_inscripcion;
                    document.getElementById('editFechaFinInscripcion').value = evento
                        .fecha_final_inscripcion;
                    document.getElementById('editFechaInicio').value = evento.fecha_inicio;
                    document.getElementById('editFechaFin').value = evento.fecha_final;
                    document.getElementById('editIdTipoEvento').value = evento.id_tipo_evento;

                    // CAMBIO AQUÍ
                    const radioEstado = document.querySelector(`input[name="estado"][value="${evento.estado}"]`);
                    if (radioEstado) {
                        radioEstado.checked = true;
                    }

                    // Cargar imagen si existe
                    const previewImage = document.querySelector(
                        "#modalEditarEvento .previewImage");
                    const previewText = document.querySelector(
                        "#modalEditarEvento .previewText");
                    const removeBtn = document.querySelector(
                        "#modalEditarEvento .removeImageBtn");
                    const eliminarImagenInput = document.querySelector('#eliminarImagenEvento');

                    if (evento.imagen) {
                        previewImage.src = `/storage/${evento.imagen}`;
                        previewImage.style.display = "block";
                        previewText.style.display = "none";
                        removeBtn.style.display = "block";
                    } else {
                        previewImage.src = "";
                        previewImage.style.display = "none";
                        previewText.style.display = "block";
                        removeBtn.style.display = "none";
                    }

                    // Reiniciar el campo oculto al cargar un nuevo evento
                    if (eliminarImagenInput) {
                        eliminarImagenInput.value = "0";
                    }

                    // Guardar ID del evento en el formulario
                    document.getElementById('formEditarEvento').dataset.eventoId = eventoId;

                    // Mostrar el modal
                    $('#modalEditarEvento').modal('show');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.error ||
                            'No se pudo cargar los datos del evento.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (xhr) {
                console.error('Error en la solicitud AJAX:', xhr); // Depuración
                let errorMessage = 'No se pudo cargar los datos del evento.';
                if (xhr.status === 404) {
                    errorMessage = 'El evento no fue encontrado.';
                } else if (xhr.status === 500) {
                    errorMessage = xhr.responseJSON?.error || 'Error interno del servidor.';
                }
                Swal.fire({
                    title: 'Error',
                    html: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }

    // Escuchar clics en botones de edición
    $(document).on('click', '.btn-edit', function (e) {
        e.preventDefault(); // Evitar comportamiento por defecto del enlace
        const eventoId = this.dataset.eventoId; // Obtener ID del evento
        if (!eventoId) {
            Swal.fire({
                title: 'Error',
                text: 'ID del evento no encontrado.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
        cargarDatosEvento(eventoId);
    });


    // Resetear el formulario y la vista previa al cerrar el modal
    $('#modalEditarEvento').on('hidden.bs.modal', function () {
        const formEditarEvento = document.getElementById('formEditarEvento');
        const previewImage = formEditarEvento.querySelector('.previewImage');
        const previewText = formEditarEvento.querySelector('.previewText');
        const removeBtn = formEditarEvento.querySelector('.removeImageBtn');
        const eliminarImagenInput = formEditarEvento.querySelector('#eliminarImagenEvento');
        const inputFile = formEditarEvento.querySelector('.imagenEventoInputEditar');

        formEditarEvento.reset();
        previewImage.src = "";
        previewImage.style.display = "none";
        previewText.style.display = "block";
        removeBtn.style.display = "none";

        if (eliminarImagenInput) {
            eliminarImagenInput.value = "0";
        }
        if (inputFile) {
            inputFile.value = "";
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
            $.ajax({
                url: $('#form-eliminar-' + id).attr('action'),
                method: $('#form-eliminar-' + id).attr('method'),
                data: $('#form-eliminar-' + id).serialize(),
                success: function (response) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: 'El evento ha sido eliminado correctamente.',
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
                        text: 'Ocurrió un error al intentar eliminar el evento.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
}