<!-- Reject Requisition Modal -->
<div class="modal fade" id="reject-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle text-danger"></i> Rechazar Requisición
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="reject-form" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Esta acción marcará la requisición como rechazada. Proporcione una razón clara.
                    </div>
                    
                    <div class="form-group">
                        <label for="rejection_reason">Motivo del Rechazo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="4" required placeholder="Explique por qué se rechaza esta requisición..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban"></i> Rechazar Requisición
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
