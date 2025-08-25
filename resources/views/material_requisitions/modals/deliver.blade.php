<!-- Deliver Materials Modal -->
<div class="modal fade" id="deliver-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-truck text-primary"></i> Entregar Materiales
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="deliver-form" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Los materiales entregados se descontarán automáticamente del inventario.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Aprobado</th>
                                    <th>Entregado</th>
                                    <th>Pendiente</th>
                                    <th>Entregar Ahora</th>
                                </tr>
                            </thead>
                            <tbody id="deliver-items-tbody">
                                <!-- Items will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-truck"></i> Entregar Materiales
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
