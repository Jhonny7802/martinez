@extends('layouts.app')

@section('title')
    Requisiciones de Materiales
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-list"></i> Requisiciones de Materiales
            </h1>
            <p class="mb-0 text-muted">Gestión de solicitudes de materiales para proyectos</p>
        </div>
        <div>
            <a href="{{ route('material-requisitions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Requisición
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pending-count">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Aprobadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="approved-count">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Entregadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="delivered-count">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Mes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-month">L. 0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-control" id="status-filter">
                        <option value="">Todos los Estados</option>
                        <option value="pending">Pendientes</option>
                        <option value="approved">Aprobadas</option>
                        <option value="rejected">Rechazadas</option>
                        <option value="delivered">Entregadas</option>
                        <option value="cancelled">Canceladas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="priority-filter">
                        <option value="">Todas las Prioridades</option>
                        <option value="low">Baja</option>
                        <option value="medium">Media</option>
                        <option value="high">Alta</option>
                        <option value="urgent">Urgente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="date-from" placeholder="Desde">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="date-to" placeholder="Hasta">
                </div>
            </div>
        </div>
    </div>

    <!-- Requisitions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Requisiciones</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="requisitions-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Proyecto</th>
                            <th>Solicitado por</th>
                            <th>Fecha Requerida</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Costo Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@include('material_requisitions.modals.approve')
@include('material_requisitions.modals.deliver')
@include('material_requisitions.modals.reject')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let table = $('#requisitions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('material-requisitions.index') }}",
            data: function (d) {
                d.status = $('#status-filter').val();
                d.priority = $('#priority-filter').val();
                d.date_from = $('#date-from').val();
                d.date_to = $('#date-to').val();
            }
        },
        columns: [
            {data: 'requisition_number', name: 'requisition_number'},
            {data: 'project_name', name: 'project.project_name'},
            {data: 'requested_by_name', name: 'requestedBy.name'},
            {data: 'required_date', name: 'required_date'},
            {data: 'status_badge', name: 'status', orderable: false, searchable: false},
            {data: 'priority_badge', name: 'priority', orderable: false, searchable: false},
            {data: 'total_cost_formatted', name: 'total_cost'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        }
    });

    // Filter handlers
    $('#status-filter, #priority-filter, #date-from, #date-to').change(function() {
        table.draw();
    });

    // Update stats
    function updateStats() {
        $.get("{{ route('material-requisitions.stats') }}", function(data) {
            $('#pending-count').text(data.pending || 0);
            $('#approved-count').text(data.approved || 0);
            $('#delivered-count').text(data.delivered || 0);
            $('#total-month').text('L. ' + (data.total_month || 0).toLocaleString());
        });
    }

    updateStats();
    setInterval(updateStats, 30000); // Update every 30 seconds
});
</script>
@endpush
