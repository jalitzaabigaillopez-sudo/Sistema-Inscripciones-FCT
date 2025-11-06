@extends('app')

@section('tituloArriba')
    Inscripciones a Eventos
@endsection

@section('breadcrumb-title', 'Lista de Eventos')

@section('content')

    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Inscripciones por Eventos</h4>
        </div>

        {{-- Modal de Inscripción de Academia --}}
        <div class="modal fade" id="modalInscripcionAcademia" tabindex="-1" aria-labelledby="modalInscripcionAcademiaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4 border-0 shadow-lg" style="background-color: #f8f9fa;">
                    <div class="modal-header border-bottom-0 pb-2">
                        <h5 class="modal-title text-center fw-bold w-100 mb-3" id="modalInscripcionAcademiaLabel">
                            Inscribirse como Academia
                        </h5>
                        <button type="button" class="btn-close btn-close-secondary" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="{{ route('inscripciones.store') }}">
                            @csrf
                            <input type="hidden" name="evento_id" id="evento_id">
                            <div class="mb-3">
                                <label for="academia" class="form-label">Seleccione Academia</label>
                                <select name="academia_id" id="academia" class="form-select" required>
                                    <option value="" selected disabled>-- Elige una academia --</option>
                                    @foreach($academias as $academia)
                                        <option value="{{ $academia->id }}">{{ $academia->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer bg-light rounded-bottom d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success rounded-pill">Guardar Inscripción</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- EXPORTAR PDF O EXCEL --}}
<div class="d-flex justify-content-start align-items-center mb-2 flex-wrap">
  <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with export and filter">
    <div class="btn-group me-2" role="group" aria-label="Export buttons">
      <a id="bPDF" href="#" class="btn btn-outline-danger btn-sm rounded-pill px-3 d-flex align-items-center">
        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
      </a>

      <a id="bExcel" href="#" class="btn btn-outline-success btn-sm rounded-pill px-3 d-flex align-items-center ms-2">
        <i class="bi bi-file-earmark-excel me-1"></i> Excel
      </a>
    </div>

    <div class="btn-group" role="group" aria-label="Filter dropdown">
      <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle btn-sm rounded-pill px-3 d-flex align-items-center"
                type="button" id="dropdownFiltro" data-bs-toggle="dropdown" aria-expanded="false" val="" data-filtro="">
          Filtrar por
        </button>

        <ul class="dropdown-menu" aria-labelledby="dropdownFiltro" id="menuFiltro">
          <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">Eventos</a>
            <ul class="dropdown-menu" id="submenuEventos"></ul>
          </li>
          <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">Academias</a>
            <ul class="dropdown-menu" id="submenuAcademias"></ul>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item text-muted" href="#" id="btnMostrarTodo">Mostrar todo</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
        {{-- Campo oculto para registrar el tipo de filtro --}}
        <input type="hidden" id="tipoFiltro" data-filtro="">

        {{-- Tabla de Inscripciones --}}
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover table-bordered text-center border" id="tablaInscripciones">
                <thead class="table-light">
                    <tr>
                        <th>Evento</th>
                        <th>Academia</th>
                        <th>Número de atletas inscritos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscripciones as $item)
                        <tr>
                            <td value="{{ $item->evento->id_evento }}">{{ $item->evento->nombre }}</td>
                            <td value="{{ $item->academia->id_academia }}">{{ $item->academia->nombre }}</td>
                            <td>{{ $item->total_atletas }}</td>
                            <td>
                                <span
                                    class="badge rounded-pill 
                                                                                                                                                            {{ $item->estado == 'activa' ? 'bg-success' : ($item->estado == 'inactiva' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ ucfirst($item->estado) }}
                                </span>
                            </td>
                            <td>
                                @if($item->estado === 'activa')
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill" type="button"
                                            data-bs-toggle="dropdown"></button>
                                        <ul class="dropdown-menu">
                                            <a class="dropdown-item btn-edit"
                                                href="{{ route('admin.editar.inscripcion', ['id_evento' => $item->evento->id_evento, 'id_academia' => $item->academia->id_academia]) }}">
                                                <i class="bi bi-pencil-square"></i> Ver/Editar
                                            </a>
                                            <li>
                                                <a class="dropdown-item text-danger"
                                                    href="{{ route('admin.eliminar.inscripcion', ['id_evento' => $item->evento->id_evento, 'id_academia' => $item->academia->id_academia]) }}">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- Librerías -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function () {
            // 1️⃣ Generar dinámicamente las opciones de Eventos y Academias desde la tabla
            let eventos = new Set();
            let academias = new Set();

            $("#tablaInscripciones tbody tr").each(function () {
                eventos.add($(this).find("td:nth-child(1)").text().trim());
                academias.add($(this).find("td:nth-child(2)").text().trim());
            });

            // 2️⃣ Insertar los items en los submenús
            eventos.forEach(evento => {
                $("#submenuEventos").append(`<li><a class="dropdown-item item-filtro" href="#" data-tipo="evento">${evento}</a></li>`);
            });
            academias.forEach(academia => {
                $("#submenuAcademias").append(`<li><a class="dropdown-item item-filtro" href="#" data-tipo="academia">${academia}</a></li>`);
            });

            // Filtrar la tabla al hacer clic en un ítem
            $(document).on("click", ".item-filtro", function (e) {
                e.preventDefault();
                let valor = $(this).text().trim();
                let tipo = $(this).data("tipo");

                // Buscar el ID real en la tabla
                let idSeleccionado = null;
                $("#tablaInscripciones tbody tr").each(function () {
                    let $td = $(this).find(`td:nth-child(${tipo === "evento" ? 1 : 2})`);
                    if ($td.text().trim() === valor) {
                        idSeleccionado = $td.attr("value");
                        return false; // salir del each
                    }
                });

                // Guardar el ID y tipo en el botón del filtro
                let $boton = $("#dropdownFiltro");
                $boton.attr("data-filtro", tipo);
                $boton.attr("val", idSeleccionado); // asigna el id al val del botón
                console.log("Botón actualizado:", { tipo, id: idSeleccionado });

                // Filtrado visual de la tabla
                $("#tablaInscripciones tbody tr").each(function () {
                    let textoEvento = $(this).find("td:nth-child(1)").text().trim();
                    let textoAcademia = $(this).find("td:nth-child(2)").text().trim();

                    let mostrar = false;
                    if (tipo === "evento" && textoEvento === valor) mostrar = true;
                    if (tipo === "academia" && textoAcademia === valor) mostrar = true;

                    $(this).toggle(mostrar);
                });
            });

            // 4️⃣ Botón "Mostrar todo"
            $("#btnMostrarTodo").on("click", function (e) {
                e.preventDefault();
                $("#tipoFiltro").attr("data-filtro", "");
                $("#tablaInscripciones tbody tr").show();
            });

            // 5️⃣ Habilitar los submenús manualmente (Bootstrap no los abre por defecto)
            $('.dropdown-submenu > a').on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).next('.dropdown-menu').toggle();
            });



            $('#bPDF').on('click', function () {
                let valorID = $('#dropdownFiltro').attr('val');
                let tipoFiltro = $('#dropdownFiltro').data('filtro');

                if (tipoFiltro === "academia") {
                    window.open('/reporteAdministradorInscripcionesAcademia/' + valorID, '_blank');
                } else if (tipoFiltro === "evento") {
                    window.open('/reporteAdministradorInscripcionesEvento/' + valorID, '_blank');
                } else {
                    console.log("WWWWW");
                    
                    window.open('/reporteAdministradorInscripciones/', '_blank');
                }
            });



            $('#bExcel').on('click', function () {
                let valorID = $('#dropdownFiltro').attr('val');
                let tipoFiltro = $('#dropdownFiltro').data('filtro');

                if (tipoFiltro === "academia") {
                    window.open('/reporteAdministradorInscripcionesAcademiaExcel/' + valorID, '_blank');
                } else if (tipoFiltro === "evento") {
                    window.open('/reporteAdministradorInscripcionesEventoExcel/' + valorID, '_blank');
                } else {

                }
            });

        });
    </script>

    <style>
        /* Submenús del dropdown */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-left: 0.1rem;
            margin-right: 0.1rem;
        }
    </style>




@endsection