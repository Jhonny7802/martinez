@extends('layouts.app')

@section('title')
    Requisición {{ $materialRequisition->requisition_number }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-list"></i> Requisición {{ $materialRequisition->requisition_number }}
            </h1>
            <p class="mb-0 text-muted">Detalles de la solicitud de materiales</p>
        </div>
        <div>
            <a href="{{ route('material-requisitions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @if($materialRequisition->status === 'pending')
                <a href="{{ route('material-requisitions.edit', $materialRequisition->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Requisition Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Número:</strong></td>
                                    <td>{{ $materialRequisition->requisition_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Proyecto:</strong></td>
                                    <td>{{ $materialRequisition->project->project_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Solicitado por:</strong></td>
                                    <td>{{ $materialRequisition->requestedBy->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha Requerida:</strong></td>
                                    <td>{{ $materialRequisition->required_date->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td><span class="badge bg-{{ $materialRequisition->status_color }}">{{ $materialRequisition->status_label }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Prioridad:</strong></td>
                                    <td><span class="badge bg-{{ $materialRequisition->priority_color }}">{{ $materialRequisition->priority_label }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Costo Total:</strong></td>
                                    <td><strong>L. {{ number_format($materialRequisition->total_cost, 2) }}</strong></td>
                                </tr>
                                @if($materialRequisition->approved_by)
                                <tr>
                                    <td><strong>Aprobado por:</strong></td>
                                    <td>{{ $materialRequisition->approvedBy->name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    @if($materialRequisition->purpose)
                    <div class="mt-3">
                        <strong>Propósito:</strong>
                        <p class="mt-1">{{ $materialRequisition->purpose }}</p>
                    </div>
                    @endif
                    
                    @if($materialRequisition->notes)
                    <div class="mt-3">
                        <strong>Notas:</strong>
                        <p class="mt-1">{{ $materialRequisition->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Materials List -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Materiales Solicitados</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Solicitado</th>
                                    <th>Aprobado</th>
                                    <th>Entregado</th>
                                    <th>Pendiente</th>
                                    <th>Costo Unit.</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialRequisition->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->item->title ?? 'N/A' }}</strong>
                                        @if($item->specifications)
                                            <br><small class="text-muted">{{ $item->specifications }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity_requested }}</td>
                                    <td>{{ $item->quantity_approved ?? '-' }}</td>
                                    <td>{{ $item->quantity_delivered }}</td>
                                    <td>{{ $item->pending_quantity }}</td>
                                    <td>L. {{ number_format($item->unit_cost, 2) }}</td>
                                    <td>L. {{ number_format($item->total_cost, 2) }}</td>
                                    <td>
                                        @if($item->quantity_delivered >= $item->quantity_approved)
                                            <span class="badge badge-success">Completo</span>
                                        @elseif($item->quantity_delivered > 0)
                                            <span class="badge badge-warning">Parcial</span>
                                        @else
                                            <span class="badge badge-secondary">Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Historial
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Creada</h6>
                                <p class="timeline-text">{{ $materialRequisition->created_at->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">Por {{ $materialRequisition->requestedBy->name }}</small>
                            </div>
                        </div>
                        
                        @if($materialRequisition->approved_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Aprobada</h6>
                                <p class="timeline-text">{{ $materialRequisition->approved_at->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">Por {{ $materialRequisition->approvedBy->name }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($materialRequisition->delivered_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Entregada</h6>
                                <p class="timeline-text">{{ $materialRequisition->delivered_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if($materialRequisition->status === 'pending')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Acciones Pendientes</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-success btn-block approve-btn" 
                            data-id="{{ $materialRequisition->id }}">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                    <button type="button" class="btn btn-danger btn-block reject-btn" 
                            data-id="{{ $materialRequisition->id }}">
                        <i class="fas fa-times"></i> Rechazar
                    </button>
                </div>
            </div>
            @endif

            @if($materialRequisition->status === 'approved')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Entrega de Materiales</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-block deliver-btn" 
                            data-id="{{ $materialRequisition->id }}">
                        <i class="fas fa-truck"></i> Entregar Materiales
                    </button>
                </div>
            </div>
            @endif

            <!-- Project Info -->
            @if($materialRequisition->project)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-project-diagram"></i> Información del Proyecto
                    </h6>
                </div>
                <div class="card-body">
                    <h6>{{ $materialRequisition->project->project_name }}</h6>
                    <p class="text-muted">{{ $materialRequisition->project->description ?? 'Sin descripción' }}</p>
                    <small class="text-muted">
                        <strong>Cliente:</strong> {{ $materialRequisition->project->customer->company_name ?? 'N/A' }}
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('material_requisitions.modals.approve')
@include('material_requisitions.modals.deliver')
@include('material_requisitions.modals.reject')
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 17px;
    width: 2px;
    height: calc(100% + 5px);
    background-color: #e3e6f0;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 13px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Include the same modal handling scripts from actions.blade.php
    $('.approve-btn').click(function() {
        let id = $(this).data('id');
        $('#approve-modal').modal('show');
        $('#approve-form').attr('action', `/admin/material-requisitions/${id}/approve`);
        loadRequisitionItems(id, 'approve');
    });
    
    $('.deliver-btn').click(function() {
        let id = $(this).data('id');
        $('#deliver-modal').modal('show');
        $('#deliver-form').attr('action', `/admin/material-requisitions/${id}/deliver`);
        loadRequisitionItems(id, 'deliver');
    });
    
    $('.reject-btn').click(function() {
        let id = $(this).data('id');
        $('#reject-modal').modal('show');
        $('#reject-form').attr('action', `/admin/material-requisitions/${id}/reject`);
    });
});

function loadRequisitionItems(requisitionId, type) {
    $.get(`/admin/material-requisitions/${requisitionId}/items`, function(data) {
        let html = '';
        data.items.forEach(function(item) {
            if (type === 'approve') {
                html += `
                    <tr>
                        <td>${item.item.title}</td>
                        <td>${item.quantity_requested}</td>
                        <td>
                            <input type="number" class="form-control" 
                                   name="items[${item.item_id}][quantity_approved]" 
                                   value="${item.quantity_requested}" 
                                   min="0" max="${item.quantity_requested}">
                        </td>
                    </tr>
                `;
            } else if (type === 'deliver') {
                let pending = item.quantity_approved - item.quantity_delivered;
                html += `
                    <tr>
                        <td>${item.item.title}</td>
                        <td>${item.quantity_approved}</td>
                        <td>${item.quantity_delivered}</td>
                        <td>${pending}</td>
                        <td>
                            <input type="number" class="form-control" 
                                   name="items[${item.item_id}][quantity_delivered]" 
                                   value="0" min="0" max="${Math.min(pending, item.item.stock_quantity)}">
                        </td>
                    </tr>
                `;
            }
        });
        $(`#${type}-items-tbody`).html(html);
    });
}
</script>
@endpush
