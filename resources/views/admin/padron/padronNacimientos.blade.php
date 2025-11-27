@extends('app')

@section('breadcrumb-title', 'Padrón')

@section('title', 'Padrón de Nacimientos')

@section('content')


    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0">Padrón de Nacimientos</h4>

        </div>
        <hr>

        <h3>Cargar archivo .txt</h3>

        <form action="{{ route('subirArchivo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="archivo">Selecciona el archivo .txt</label>
                <input type="file" name="archivo" id="archivo" class="form-control" accept=".txt" required>
            </div>

            <button type="submit" class="btn btn-primary">Cargar</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- ================================
                CONSULTA DEL PADRÓN DE NACIMIENTOS
                ================================ -->
        <hr class="my-4">

        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <h4 class="fw-bold mb-1 d-flex align-items-center" style="color:#222A59;">
                Consultar Padrón
            </h4>
            <span class="text-muted small">Búsqueda por cédula, nombre o apellidos</span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <!-- FORMULARIO -->
                <form id="formBuscarPadron" class="row gy-3 gx-2">

                    <!-- CÉDULA -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label fw-semibold text-wrap d-block">Cédula</label>
                        <input type="text" name="identificacion"
                            class="form-control form-control-sm border-primary border-opacity-25 shadow-sm" maxlength="30"
                            placeholder="Ej: 122223333">
                    </div>

                    <!-- NOMBRE -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label fw-semibold text-wrap d-block">Nombre</label>
                        <input type="text" name="nombre"
                            class="form-control form-control-sm border-primary border-opacity-25 shadow-sm">
                    </div>

                    <!-- PRIMER APELLIDO -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label fw-semibold text-wrap d-block">Primer Apellido</label>
                        <input type="text" name="primer_apellido"
                            class="form-control form-control-sm border-primary border-opacity-25 shadow-sm">
                    </div>

                    <!-- SEGUNDO APELLIDO -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label fw-semibold text-wrap d-block">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido"
                            class="form-control form-control-sm border-primary border-opacity-25 shadow-sm">
                    </div>

                    <!-- BOTÓN -->
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <button type="submit" class="btn fw-semibold shadow-sm rounded-3 px-5 py-2 text-white"
                            style="min-width: 220px; font-size:1rem; background:#222A59; border-color:#222A59;">
                            <i class="bi bi-search me-2"></i> Buscar
                        </button>
                    </div>

                </form>

                <!-- RESULTADOS -->
                <div class="table-responsive mt-4 d-none" id="contenedorResultados">
                    <table class="table table-hover table-striped table-bordered align-middle shadow-sm rounded">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Cédula</th>
                                <th class="text-center">Nombre Completo</th>
                                <th class="text-center">Fecha nacimiento</th>
                            </tr>
                        </thead>
                        <tbody id="tablaResultados" class="text-center"></tbody>
                    </table>
                </div>

                <!-- SIN RESULTADOS -->
                <div id="sinResultados" class="alert alert-warning mt-4 d-none" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    No se encontraron registros que coincidan con la búsqueda.
                </div>

                <!-- PAGINACIÓN -->
                <nav class="mt-3">
                    <ul class="pagination justify-content-center" id="paginador"></ul>
                </nav>

            </div>
        </div>

    </div>


@endsection

@push('scripts')
    <script>
        document.getElementById('formBuscarPadron').addEventListener('submit', function(e) {
            e.preventDefault();

            const params = new URLSearchParams(new FormData(this));

            const tabla = document.getElementById('tablaResultados');
            const contenedor = document.getElementById('contenedorResultados');
            const sinResultados = document.getElementById('sinResultados');

            tabla.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-3">
                            <div class="spinner-border text-primary"></div>
                        </td>
                    </tr>
                `;

            contenedor.classList.remove("d-none");
            sinResultados.classList.add("d-none");

            fetch("{{ route('padron.buscar') }}?" + params.toString())
                .then(res => res.json())
                .then(data => {

                    tabla.innerHTML = "";

                    if (!data.length) {
                        contenedor.classList.add("d-none");
                        sinResultados.classList.remove("d-none");
                        return;
                    }

                    data.forEach(p => {
                        tabla.innerHTML += `
                    <tr>
                        <td>${p.identificacion}</td>
                        <td>${p.nombre} ${p.primer_apellido} ${p.segundo_apellido}</td>
                        <td>${p.fecha_nacimiento}</td>
                    </tr>
                `;
                    });

                })
                .catch(err => alert("Error buscando datos"));
        });
    </script>
@endpush
