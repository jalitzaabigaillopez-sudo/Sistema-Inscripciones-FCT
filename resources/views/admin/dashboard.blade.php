@extends('layouts.app')

@section('title', 'Panel Administrativo')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/fct_logo.svg') }}" alt="FCT Logo" height="40">
            <h4 class="mb-0">Panel Administrativo</h4>
        </div>
        <div class="d-flex align-items-center gap-2">
            <input type="text" class="form-control" placeholder="Buscar..." aria-label="Buscador">
            <button class="btn btn-outline-secondary">🔍</button>
        </div>
    </div>

    {{-- Sidebar + Main --}}
    <div class="row mt-4">
        {{-- Sidebar --}}
        <div class="col-md-3 col-lg-2">
            <nav class="nav flex-column bg-light rounded p-3 shadow-sm">
                <a class="nav-link active" href="#">🏠 Menú</a>
                <a class="nav-link" href="">👤 Perfil</a>
                <a class="nav-link" href="#">📝 Inscripción</a>
                <a class="nav-link" href="#">📅 Eventos</a>
                <a class="nav-link" href="#">📊 Estadísticas</a>
                <a class="nav-link" href="#">⚖️ Verificación de Peso</a>
                <a class="nav-link" href="#">🔒 Seguridad</a>
                <a class="nav-link" href="#">🔑 Generación de llaves</a>
                <a class="nav-link" href="#">🏆 Ranking nacional</a>
                <a class="nav-link" href="#">📚 Catálogos generales</a>
                <hr>
                <span class="text-muted">Log in as: <strong>Administrador</strong></span>
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="col-md-9 col-lg-10">
            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-warning shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Academias a nivel nacional</h5>
                            <p class="card-text fs-4">100</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Atletas a nivel nacional</h5>
                            <p class="card-text fs-4">300</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-secondary shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Torneos/Eventos</h5>
                            <p class="card-text fs-4">100</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">Estadísticas generales</div>
                        <div class="card-body">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">Distribución de datos</div>
                        <div class="card-body">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="mt-5 pt-4 border-top text-center text-muted">
        <nav class="nav justify-content-center mb-2">
            <a class="nav-link" href="#">⚖️ Peso</a>
            <a class="nav-link" href="#">🏫 Academias</a>
            <a class="nav-link" href="#">🏃‍♂️ Atletas</a>
            <a class="nav-link" href="#">📁 Categorías</a>
            <a class="nav-link" href="#">🎯 Torneos/Eventos</a>
            <a class="nav-link" href="#">👥 Usuarios</a>
            <a class="nav-link" href="#">🔐 Seguridad</a>
        </nav>
        <small>Copyright © FCT 2025</small>
    </footer>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const barCtx = document.getElementById('barChart');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Academias', 'Atletas', 'Eventos', 'Otros'],
            datasets: [{
                label: 'Cantidad',
                data: [100, 300, 100, 50],
                backgroundColor: ['#ffc107', '#dc3545', '#6c757d', '#0d6efd']
            }]
        }
    });

    const pieCtx = document.getElementById('pieChart');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Activos', 'Inactivos'],
            datasets: [{
                data: [80, 20],
                backgroundColor: ['#dc3545', '#6c757d']
            }]
        }
    });
</script>
@endpush
