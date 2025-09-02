@extends('academia')

@section('title', 'Dashboard Academia')

@section('content')
<div class="container">
    {{-- Main dashboard mejorado --}}

    <h3 class="mb-4 fw-bold text-">Dashboard de Academia</h3>
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card shadow-sm border-0 text-bg-primary mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Atletas Registrados</h5>
            <p class="card-text fs-1 fw-semibold">35</p>
            <i class="bi bi-person-fill fs-3"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 text-bg-success mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Eventos Inscritos</h5>
            <p class="card-text fs-1 fw-semibold">5</p>
            <i class="bi bi-calendar-event-fill fs-3"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 text-bg-info mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Avance de Eventos</h5>
            <p class="card-text fs-1 fw-semibold">80%</p>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-primary text-white">Atletas inscritos por evento</div>
          <div class="card-body">
            <div class="card shadow-sm border-0 mb-4">
    <div class="mb-2 fw-semibold">Combate <span class="float-end">12</span></div>
    <div class="progress mb-3" style="height: 20px;">
      <div class="progress-bar bg-primary" style="width: 80%;">&nbsp;</div>
    </div>

    <div class="mb-2 fw-semibold">Poomsae <span class="float-end">8</span></div>
    <div class="progress mb-3" style="height: 20px;">
      <div class="progress-bar bg-info" style="width: 53%;">&nbsp;</div>
    </div>

    <div class="mb-2 fw-semibold">Freestyle <span class="float-end">5</span></div>
    <div class="progress mb-3" style="height: 20px;">
      <div class="progress-bar bg-warning" style="width: 33%;">&nbsp;</div>
    </div>
</div>
    </div>
      </div>
      </div>

      <div class="col-md-6 mb-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-info text-white">Estado de eventos</div>
      <div class="card-body">
        <div class="mb-2 fw-semibold">Finalizados <span class="float-end">20</span></div>
          <div class="progress mb-3" style="height: 20px;">
            <div class="progress-bar bg-secondary" style="width: 20%;">&nbsp;</div>
          </div>
           <div class="mb-2 fw-semibold">Pendientes <span class="float-end">80</span></div>
          <div class="progress mb-3" style="height: 20px;">
            <div class="progress-bar bg-warning" style="width: 80%;">&nbsp;</div>
          </div>
        </div>
        </div>
        
       </div>
       
      </div>
     </div>  
    </div>
@endsection
