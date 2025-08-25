<!-- Approve Requisition Modal -->
<div class="modal fade" id="approve-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle text-success"></i> Aprobar Requisición
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approve-form" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Revise las cantidades solicitadas y ajuste las cantidades aprobadas según disponibilidad.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Solicitado</th>
                                    <th>Cantidad a Aprobar</th>
                                </tr>
                            </thead>
                            <tbody id="approve-items-tbody">
                                <!-- Items will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprobar Requisición
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
