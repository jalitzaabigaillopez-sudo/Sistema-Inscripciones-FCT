@extends('app')
@section('title', 'Calendario de Eventos')
@section('content')


<div class="container mt-4">
    <h3 class="mb-4">📅 Calendario de Fechas Importantes</h3>
    <div id="calendar"></div>
</div>

<!-- FullCalendar CSS y JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        events: [
            {
                title: 'Pesaje Nacional',
                start: '2025-03-10',
                color: '#ffc107'
            },
            {
                title: 'Convocatoria Juvenil',
                start: '2025-03-15',
                color: '#0dcaf0'
            },
            {
                title: 'Evento Regional',
                start: '2025-03-23',
                color: '#198754'
            },
            {
                title: 'Campeonato',
                start: '2026-05-05',
                color: '#198754'
            }
        ]
    });
    calendar.render();
});

</script>

<div class="mt-4">
    <h5>📌 Leyenda</h5>
    <ul class="list-inline">
        <li class="list-inline-item"><span class="badge bg-warning">🟡</span> Pesaje</li>
        <li class="list-inline-item"><span class="badge bg-info">📣</span> Convocatoria</li>
        <li class="list-inline-item"><span class="badge bg-success">🏆</span> Evento</li>
    </ul>
</div>
<a href="#" class="btn btn-outline-primary mb-3 align-self-end">
    <i class="bi bi-file-earmark-arrow-down"></i> Exportar Calendario
</a>
@endsection