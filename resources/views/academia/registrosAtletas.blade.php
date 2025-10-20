@extends('academia')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-3">Atletas registrados en esta academia</h4>

    {{-- Botón para abrir modal --}}
    <div class="mb-3 text-end">
        <button type="button" id="btnNuevoAtleta" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrear">
            + Nuevo Atleta
        </button>
    </div>

    {{-- Script para limpiar formulario al abrir modal --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('btnNuevoAtleta');
        btn && btn.addEventListener('click', function () {
            const form = document.querySelector('#modalCrear form');
            if (!form) return;
            form.reset(); // limpiar campos
            // quitar vista previa de imagen si existe
            const preview = form.querySelector('img');
            if (preview) preview.remove();
            // limpiar posibles mensajes de error (si los muestra el DOM)
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        });
    });
    </script>
    


    {{-- Tabla de atletas --}}
  <table class="table table-minimalista" id="tabla-atletas">
    <thead class="table-light" role="rowgroup" style="position:sticky; top:0; z-index:1020; backdrop-filter: blur(6px); background-color:#f8f9fa; border-bottom:2px solid #dee2e6;">
        <tr style="font-weight:600; color:#495057;">
            <th style="min-width:80px;">Imagen</th>
            <th style="min-width:100px;">Tipo ID</th>
            <th style="min-width:120px;">Identificación</th>
            <th style="min-width:180px;">Nombre completo</th>
            <th style="min-width:80px;">Sexo</th>
            <th style="min-width:120px;">Fecha Nacimiento</th>
            <th style="min-width:100px;">Grado</th>
            <th style="min-width:160px;">Academia</th>
            <th style="min-width:100px;">Estado</th>
            <th style="min-width:140px;">Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($atletas as $a)
            <tr data-id="{{ $a->id_atleta }}">
                <td>
                    @if($a->imagen)
                        <img src="{{ asset('storage/' . $a->imagen) }}" alt="Foto" style="width:45px; height:45px; object-fit:cover; border-radius:50%; border:1px solid #ced4da;">
                    @else
                        <span class="text-muted fst-italic">Sin foto</span>
                    @endif
                </td>
                <td>{{ $a->tipo_identificacion }}</td>
                <td>{{ $a->identificacion }}</td>
                <td>{{ $a->nombre }} {{ $a->primer_apellido }} {{ $a->segundo_apellido }}</td>
                <td>
                    @php $sexo = strtolower($a->sexo ?? ''); @endphp
                    @if($sexo === 'm' || $sexo === 'masculino') Masculino
                    @elseif($sexo === 'f' || $sexo === 'femenino') Femenino
                    @else <span class="badge bg-secondary">{{ $a->sexo ?? '-' }}</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($a->fecha_nacimiento)->format('d/m/Y') }}</td>
                <td>{{ $a->grado->nombre ?? '-' }}</td>
                <td>{{ optional($a->academia)->nombre ?? $academia->nombre ?? '-' }}</td>
                <td>
                    @if(strtolower($a->estado) == 'activo')
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-secondary">Inactivo</span>
                    @endif
                </td>

                <td>
                  <button type="button" class="btn-edit" data-id="{{ $a->id_atleta }}">
                   <i class="bi bi-pencil-square"></i>
                    </button>


                    <form class="form-eliminar d-inline" action="{{ route('registro-atletas.destroy', $a->id_atleta) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" title="Eliminar atleta">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-muted text-center py-3">No hay atletas registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Modal Crear --}}
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formCrear" method="POST" action="{{ route('registro-atletas.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_academia" value="{{ $academia->id_academia }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearLabel">Registrar Nuevo Atleta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Campos básicos --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Identificación *</label>
                                <select name="tipo_identificacion" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Nacional">Nacional</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Identificación *</label>
                                <input type="text" name="identificacion" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Primer Apellido *</label>
                                <input type="text" name="primer_apellido" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Segundo Apellido</label>
                                <input type="text" name="segundo_apellido" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sexo *</label>
                                <select name="sexo" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Grado *</label>
                                <select name="id_grado" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($grados as $grado)
                                        <option value="{{ $grado->id_grado }}">{{ $grado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Foto de Perfil</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Modal Editar ÚNICO (se rellena por JS) --}}
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formEditar" method="POST" action="#" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_academia" value="{{ $academia->id_academia ?? '' }}">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar Atleta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Tipo de Identificación *</label>
              <select id="e_tipo_identificacion" name="tipo_identificacion" class="form-select" required>
                <option value="Nacional">Nacional</option>
                <option value="Otro">Otro</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Identificación *</label>
              <input id="e_identificacion" type="text" name="identificacion" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nombre *</label>
              <input id="e_nombre" type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Primer Apellido *</label>
              <input id="e_primer_apellido" type="text" name="primer_apellido" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Segundo Apellido</label>
              <input id="e_segundo_apellido" type="text" name="segundo_apellido" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de Nacimiento *</label>
              <input id="e_fecha_nacimiento" type="date" name="fecha_nacimiento" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Sexo *</label>
              <select id="e_sexo" name="sexo" class="form-select" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Grado *</label>
              <select id="e_id_grado" name="id_grado" class="form-select" required>
                @foreach ($grados as $grado)
                  <option value="{{ $grado->id_grado }}">{{ $grado->nombre }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Estado *</label>
              <select id="e_estado" name="estado" class="form-select" required>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
              </select>
            </div>
            <div class="col-md-12">
              <label class="form-label">Foto de Perfil</label>
              <input id="e_imagen" type="file" name="imagen" class="form-control" accept="image/*">
              <div id="e_preview" class="mt-2"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar'));

// CREAR ATLETA (básico y funcional)
document.getElementById('formCrear').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    Swal.fire({ title: 'Guardando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(form)
        });

        let data = {};
        try { data = await res.json(); } catch (err) { /* non-json response */ }

        Swal.close();

        if (res.status === 422) {
            Swal.fire('Verifique los datos', 'Hay errores en el formulario.', 'warning');
            return;
        }

        if (res.ok && (data.success === undefined || data.success === true)) {
            const modalEl = document.getElementById('modalCrear');
            const inst = bootstrap.Modal.getInstance(modalEl);
            inst && inst.hide();
            Swal.fire({ title: 'Guardado', icon: 'success', timer: 1000, showConfirmButton: false }).then(() => location.reload());
        } else {
            Swal.fire('Error', data.message || 'No se pudo registrar.', 'error');
        }
    } catch (err) {
        Swal.close();
        Swal.fire('Error', 'Error de conexión.', 'error');
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
});

// CARGAR DATOS PARA EDITAR (simple)
document.querySelector('#tabla-atletas').addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-edit');
    if (!btn) return;
    const id = btn.dataset.id;
    if (!id) return;

    Swal.fire({ title: 'Cargando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    const url = '{{ route("registro-atletas.edit", ":id") }}'.replace(':id', id);

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(async r => {
        if (!r.ok) throw new Error(r.statusText);
        return r.json();
    })
    .then(json => {
        Swal.close();
        const data = json && json.atleta ? json.atleta : json;

        document.getElementById('formEditar').action = '{{ url("registro-atletas.edit") }}/' + id;

        document.getElementById('e_tipo_identificacion').value = data.tipo_identificacion || '';
        document.getElementById('e_identificacion').value = data.identificacion || '';
        document.getElementById('e_nombre').value = data.nombre || '';
        document.getElementById('e_primer_apellido').value = data.primer_apellido || '';
        document.getElementById('e_segundo_apellido').value = data.segundo_apellido || '';
        document.getElementById('e_fecha_nacimiento').value = (data.fecha_nacimiento || '').split('T')[0] || '';
        document.getElementById('e_sexo').value = data.sexo || '';
        document.getElementById('e_id_grado').value = data.id_grado || '';
        document.getElementById('e_estado').value = (data.estado || 'activo').toLowerCase();

        const prev = document.getElementById('e_preview');
        prev.innerHTML = '';
        if (data.imagen) {
            const img = document.createElement('img');
            img.src = '/storage/' + data.imagen;
            img.style.width = '60px'; img.style.height = '60px'; img.style.borderRadius = '50%';
            prev.appendChild(img);
        }

        modalEditar.show();
    })
    .catch(() => {
        Swal.close();
        Swal.fire('Error', 'No se pudo cargar el atleta.', 'error');
    });
});

// ACTUALIZAR ATLETA (básico)
document.getElementById('formEditar').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    Swal.fire({ title: 'Actualizando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(form)
        });

        let data = {};
        try { data = await res.json(); } catch (err) { /* ignore */ }

        Swal.close();

        if (res.status === 422) {
            Swal.fire('Verifique los datos', 'Hay errores en el formulario.', 'warning');
            return;
        }

        if (res.ok && (data.success === undefined || data.success === true)) {
            const modalEl = document.getElementById('modalEditar');
            const inst = bootstrap.Modal.getInstance(modalEl);
            inst && inst.hide();
            Swal.fire({ title: 'Actualizado', icon: 'success', timer: 1000, showConfirmButton: false }).then(() => location.reload());
        } else {
            Swal.fire('Error', data.message || 'No se pudo actualizar.', 'error');
        }
    } catch (err) {
        Swal.close();
        Swal.fire('Error', 'Error de conexión.', 'error');
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
});

// ELIMINAR ATLETA
document.querySelector('#tabla-atletas').addEventListener('submit', function (e) {
    const form = e.target.closest('.form-eliminar');
    if (!form) return;
    e.preventDefault();

    Swal.fire({
        title: '¿Está seguro?',
        text: 'Esta acción eliminará el atleta definitivamente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (!result.isConfirmed) return;

        const action = form.action;
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        Swal.fire({ title: 'Eliminando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch(action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ _method: 'DELETE' })
        }).then(r => r.json()).then(resp => {
            Swal.close();
            if (resp.success) {
                const tr = form.closest('tr');
                if (tr) tr.remove();
                Swal.fire('Eliminado', resp.message || 'Atleta eliminado correctamente.', 'success');
            } else {
                Swal.fire('No eliminado', resp.message || 'No se pudo eliminar el atleta.', 'error');
            }
        }).catch(() => Swal.fire('Error', 'Error al eliminar atleta.', 'error'));
    });
});
</script>

<style>
    /* Estilo general de la tabla */
.table-minimalista {
    font-size: 0.9rem;
    border-collapse: collapse;
    width: 100%;
    box-shadow: 0 0 8px rgba(0,0,0,0.05);
    border-radius: 0.5rem;
    overflow: hidden;
}

/* Encabezado claro */
.table-minimalista thead {
    background-color: #ffffff;
    color: #6c757d;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

/* Filas alternas */
.table-minimalista tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

/* Bordes finos */
.table-minimalista th,
.table-minimalista td {
    border: 1px solid #dee2e6;
    padding: 0.5rem;
    vertical-align: middle;
    text-align: center;
}

/* Foto redonda */
.table-minimalista img {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 50%;
    border: 1px solid #ced4da;
}

/* Badges suaves */
.badge-success {
    background-color: #d4edda;
    color: #155724;
    font-weight: 500;
}

.badge-secondary {
    background-color: #e2e3e5;
    color: #6c757d;
    font-weight: 500;
}

.badge-primary {
    background-color: #cce5ff;
    color: #004085;
    font-weight: 500;
}

.badge-danger {
    background-color: #f8d7da;
    color: #721c24;
    font-weight: 500;
}

/* Botón circular amarillo para editar */
.btn-edit {
    background-color: #ffc107; /* amarillo */
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: background-color 0.2s ease;
}

.btn-edit:hover {
    background-color: #e0a800;
}

/* Botón circular rojo para eliminar */
.btn-delete {
    background-color: #dc3545; /* rojo */
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: background-color 0.2s ease;
}

.btn-delete:hover {
    background-color: #c82333;
}


</style>
@endsection


