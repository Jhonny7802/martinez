@extends('layouts.app')

@section('title')
    Nueva Requisición de Materiales
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle"></i> Nueva Requisición de Materiales
            </h1>
            <p class="mb-0 text-muted">Crear nueva solicitud de materiales para proyecto</p>
        </div>
        <div>
            <a href="{{ route('material-requisitions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <form id="requisition-form" method="POST" action="{{ route('material-requisitions.store') }}">
        @csrf
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id">Proyecto <span class="text-danger">*</span></label>
                                    <select class="form-control" id="project_id" name="project_id" required>
                                        <option value="">Seleccionar proyecto...</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="required_date">Fecha Requerida <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="required_date" name="required_date" 
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Prioridad <span class="text-danger">*</span></label>
                                    <select class="form-control" id="priority" name="priority" required>
                                        <option value="medium">Media</option>
                                        <option value="low">Baja</option>
                                        <option value="high">Alta</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purpose">Propósito</label>
                                    <input type="text" class="form-control" id="purpose" name="purpose" 
                                           placeholder="Ej: Construcción de cimientos">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notas Adicionales</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Especificaciones adicionales o comentarios..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Materials Section -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Materiales Solicitados</h6>
                        <button type="button" class="btn btn-sm btn-success" id="add-material">
                            <i class="fas fa-plus"></i> Agregar Material
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="materials-table">
                                <thead>
                                    <tr>
                                        <th width="35%">Material</th>
                                        <th width="15%">Cantidad</th>
                                        <th width="15%">Stock Actual</th>
                                        <th width="25%">Especificaciones</th>
                                        <th width="10%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="materials-tbody">
                                    <!-- Materials will be added here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info mt-3" id="no-materials" style="display: none;">
                            <i class="fas fa-info-circle"></i> Agregue al menos un material para continuar.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Acciones</h6>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Crear Requisición
                        </button>
                        <button type="button" class="btn btn-secondary btn-block" onclick="window.history.back()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-exclamation-triangle"></i> Stock Bajo
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="low-stock-items">
                            <p class="text-muted">Cargando...</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Requisitions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-history"></i> Requisiciones Recientes
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="recent-requisitions">
                            <p class="text-muted">Cargando...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Material Row Template -->
<template id="material-row-template">
    <tr class="material-row">
        <td>
            <select class="form-control material-select" name="items[INDEX][item_id]" required>
                <option value="">Seleccionar material...</option>
                @foreach($materials as $material)
                    <option value="{{ $material->id }}" 
                            data-stock="{{ $material->stock_quantity ?? 0 }}"
                            data-unit="{{ $material->unit_of_measure ?? 'unidad' }}">
                        {{ $material->title }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="form-control quantity-input" 
                   name="items[INDEX][quantity_requested]" min="1" required>
        </td>
        <td>
            <span class="stock-display badge badge-secondary">-</span>
        </td>
        <td>
            <input type="text" class="form-control" name="items[INDEX][specifications]" 
                   placeholder="Especificaciones...">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger remove-material">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let materialIndex = 0;

    // Add material row
    $('#add-material').click(function() {
        addMaterialRow();
    });

    function addMaterialRow() {
        let template = $('#material-row-template').html();
        template = template.replace(/INDEX/g, materialIndex);
        $('#materials-tbody').append(template);
        materialIndex++;
        updateNoMaterialsAlert();
    }

    // Remove material row
    $(document).on('click', '.remove-material', function() {
        $(this).closest('tr').remove();
        updateNoMaterialsAlert();
    });

    // Update stock display when material is selected
    $(document).on('change', '.material-select', function() {
        let stock = $(this).find(':selected').data('stock') || 0;
        let unit = $(this).find(':selected').data('unit') || 'unidad';
        let stockBadge = $(this).closest('tr').find('.stock-display');
        
        stockBadge.text(stock + ' ' + unit);
        stockBadge.removeClass('badge-secondary badge-success badge-warning badge-danger');
        
        if (stock <= 0) {
            stockBadge.addClass('badge-danger');
        } else if (stock <= 10) {
            stockBadge.addClass('badge-warning');
        } else {
            stockBadge.addClass('badge-success');
        }
    });

    function updateNoMaterialsAlert() {
        if ($('#materials-tbody tr').length === 0) {
            $('#no-materials').show();
        } else {
            $('#no-materials').hide();
        }
    }

    // Form validation
    $('#requisition-form').submit(function(e) {
        if ($('#materials-tbody tr').length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un material a la requisición.');
            return false;
        }
    });

    // Load low stock items
    $.get("{{ route('materials.low-stock') }}", function(data) {
        let html = '';
        if (data.length > 0) {
            data.forEach(function(item) {
                html += `<div class="mb-2">
                    <small class="font-weight-bold">${item.title}</small><br>
                    <small class="text-danger">Stock: ${item.stock_quantity} ${item.unit_of_measure || 'unidad'}</small>
                </div>`;
            });
        } else {
            html = '<p class="text-muted">No hay materiales con stock bajo.</p>';
        }
        $('#low-stock-items').html(html);
    }).fail(function() {
        $('#low-stock-items').html('<p class="text-muted">Error al cargar datos.</p>');
    });

    // Load recent requisitions
    $.get("{{ route('material-requisitions.recent') }}", function(data) {
        let html = '';
        if (data.length > 0) {
            data.forEach(function(req) {
                html += `<div class="mb-2">
                    <small class="font-weight-bold">${req.requisition_number}</small><br>
                    <small class="text-muted">${req.project_name}</small><br>
                    <span class="badge badge-${req.status_color}">${req.status_label}</span>
                </div>`;
            });
        } else {
            html = '<p class="text-muted">No hay requisiciones recientes.</p>';
        }
        $('#recent-requisitions').html(html);
    }).fail(function() {
        $('#recent-requisitions').html('<p class="text-muted">Error al cargar datos.</p>');
    });

    // Add first material row
    addMaterialRow();
    updateNoMaterialsAlert();
});
</script>
@endpush
