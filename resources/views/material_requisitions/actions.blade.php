<div class="btn-group" role="group">
    <a href="{{ route('material-requisitions.show', $requisition->id) }}" 
       class="btn btn-sm btn-info" title="Ver Detalles">
        <i class="fas fa-eye"></i>
    </a>
    
    @if($requisition->status === 'pending')
        <a href="{{ route('material-requisitions.edit', $requisition->id) }}" 
           class="btn btn-sm btn-warning" title="Editar">
            <i class="fas fa-edit"></i>
        </a>
        
        <button type="button" class="btn btn-sm btn-success approve-btn" 
                data-id="{{ $requisition->id }}" title="Aprobar">
            <i class="fas fa-check"></i>
        </button>
        
        <button type="button" class="btn btn-sm btn-danger reject-btn" 
                data-id="{{ $requisition->id }}" title="Rechazar">
            <i class="fas fa-times"></i>
        </button>
    @endif
    
    @if($requisition->status === 'approved')
        <button type="button" class="btn btn-sm btn-primary deliver-btn" 
                data-id="{{ $requisition->id }}" title="Entregar">
            <i class="fas fa-truck"></i>
        </button>
    @endif
    
    @if(in_array($requisition->status, ['pending', 'rejected']))
        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                data-id="{{ $requisition->id }}" title="Eliminar">
            <i class="fas fa-trash"></i>
        </button>
    @endif
</div>

<script>
$(document).ready(function() {
    // Approve requisition
    $('.approve-btn').click(function() {
        let id = $(this).data('id');
        $('#approve-modal').modal('show');
        $('#approve-form').attr('action', `/admin/material-requisitions/${id}/approve`);
        loadRequisitionItems(id, 'approve');
    });
    
    // Deliver materials
    $('.deliver-btn').click(function() {
        let id = $(this).data('id');
        $('#deliver-modal').modal('show');
        $('#deliver-form').attr('action', `/admin/material-requisitions/${id}/deliver`);
        loadRequisitionItems(id, 'deliver');
    });
    
    // Reject requisition
    $('.reject-btn').click(function() {
        let id = $(this).data('id');
        $('#reject-modal').modal('show');
        $('#reject-form').attr('action', `/admin/material-requisitions/${id}/reject`);
    });
    
    // Delete requisition
    $('.delete-btn').click(function() {
        let id = $(this).data('id');
        if (confirm('¿Está seguro de eliminar esta requisición?')) {
            $.ajax({
                url: `/admin/material-requisitions/${id}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#requisitions-table').DataTable().ajax.reload();
                        showAlert('success', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Error al eliminar la requisición');
                }
            });
        }
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

function showAlert(type, message) {
    let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    let alert = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>`;
    $('.container-fluid').prepend(alert);
}
</script>
