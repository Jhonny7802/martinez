@extends('layouts.app')

@section('title')
    Facturación CAI
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                Facturación CAI
            </h1>
            <a href="{{ route('cai-billings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nueva Factura
            </a>
        </div>

        <!-- Filters Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter me-1"></i>
                    Filtros
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('cai-billings.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                            <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Emitida</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pagada</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Fecha Inicio</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Fecha Fin</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="CAI, Factura, Cliente..." value="{{ request('search') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('cai-billings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Card -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Facturas CAI ({{ $caiBillings->total() }} resultados)
                </h6>
            </div>
            <div class="card-body">
                @if($caiBillings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>CAI</th>
                                    <th>Factura</th>
                                    <th>Cliente</th>
                                    <th>Fecha Emisión</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($caiBillings as $billing)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $billing->cai_number }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $billing->invoice_number }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-semibold">{{ $billing->customer_name }}</span>
                                                @if($billing->customer_rtn)
                                                    <br><small class="text-muted">RTN: {{ $billing->customer_rtn }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            {{ $billing->issue_date->format('d/m/Y') }}
                                            @if($billing->due_date)
                                                <br><small class="text-muted">Vence: {{ $billing->due_date->format('d/m/Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ $billing->formatted_total }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $billing->status_color }} status-badge" 
                                                  data-id="{{ $billing->id }}" 
                                                  data-status="{{ $billing->status }}">
                                                {{ $billing->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('cai-billings.show', $billing) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($billing->status !== 'paid')
                                                    <a href="{{ route('cai-billings.edit', $billing) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-info change-status-btn" 
                                                        data-id="{{ $billing->id }}" 
                                                        data-status="{{ $billing->status }}" 
                                                        title="Cambiar Estado">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                                <a href="{{ route('cai-billings.pdf', $billing) }}" 
                                                   class="btn btn-sm btn-outline-danger" title="PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                @if($billing->status !== 'paid')
                                                    <form method="POST" action="{{ route('cai-billings.destroy', $billing) }}" 
                                                          class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $caiBillings->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron facturas</h5>
                        <p class="text-muted">Crea tu primera factura CAI para comenzar.</p>
                        <a href="{{ route('cai-billings.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Crear Primera Factura
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Change Status Modal -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estado de Factura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="changeStatusForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="billingId" name="billing_id">
                        
                        <div class="mb-3">
                            <label for="newStatus" class="form-label">Nuevo Estado</label>
                            <select name="status" id="newStatus" class="form-select" required>
                                <option value="draft">Borrador</option>
                                <option value="issued">Emitida</option>
                                <option value="paid">Pagada</option>
                                <option value="cancelled">Cancelada</option>
                            </select>
                        </div>

                        <div id="paymentFields" style="display: none;">
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Método de Pago</label>
                                <select name="payment_method" id="paymentMethod" class="form-select">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="tarjeta">Tarjeta</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paymentDate" class="form-label">Fecha de Pago</label>
                                <input type="date" name="payment_date" id="paymentDate" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Change status modal
    $('.change-status-btn').click(function() {
        const billingId = $(this).data('id');
        const currentStatus = $(this).data('status');
        
        $('#billingId').val(billingId);
        $('#newStatus').val(currentStatus);
        
        if (currentStatus === 'paid') {
            $('#paymentFields').show();
            $('#paymentMethod').prop('required', true);
            $('#paymentDate').prop('required', true);
        } else {
            $('#paymentFields').hide();
            $('#paymentMethod').prop('required', false);
            $('#paymentDate').prop('required', false);
        }
        
        $('#changeStatusModal').modal('show');
    });

    // Handle status change
    $('#newStatus').change(function() {
        if ($(this).val() === 'paid') {
            $('#paymentFields').show();
            $('#paymentMethod').prop('required', true);
            $('#paymentDate').prop('required', true);
            $('#paymentDate').val(new Date().toISOString().split('T')[0]);
        } else {
            $('#paymentFields').hide();
            $('#paymentMethod').prop('required', false);
            $('#paymentDate').prop('required', false);
        }
    });

    // Submit status change
    $('#changeStatusForm').submit(function(e) {
        e.preventDefault();
        
        const billingId = $('#billingId').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: `/admin/cai-billings/${billingId}/change-status`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const badge = $(`.status-badge[data-id="${billingId}"]`);
                    badge.removeClass('bg-secondary bg-warning bg-success bg-danger')
                         .addClass(`bg-${response.color}`)
                         .text(response.status);
                    
                    $('#changeStatusModal').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    setTimeout(() => location.reload(), 2000);
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Error al actualizar el estado';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error
                });
            }
        });
    });

    // Delete confirmation
    $('.delete-form').submit(function(e) {
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
