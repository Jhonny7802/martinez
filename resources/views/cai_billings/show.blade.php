@extends('layouts.app')

@section('title')
    Factura CAI {{ $caiBilling->invoice_number }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-invoice text-primary me-2"></i>
                Factura CAI {{ $caiBilling->invoice_number }}
            </h1>
            <div class="btn-group">
                <a href="{{ route('cai-billings.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Volver
                </a>
                @if($caiBilling->status !== 'paid')
                    <a href="{{ route('cai-billings.edit', $caiBilling) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        Editar
                    </a>
                @endif
                <a href="{{ route('cai-billings.pdf', $caiBilling) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i>
                    Descargar PDF
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Main Invoice Card -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Detalles de la Factura</h6>
                        <span class="badge bg-{{ $caiBilling->status_color }} fs-6">
                            {{ $caiBilling->status_label }}
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Invoice Header -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Información de Facturación</h5>
                                <div class="mb-2">
                                    <strong>CAI:</strong> 
                                    <span class="text-primary fw-bold">{{ $caiBilling->cai_number }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Número de Factura:</strong> {{ $caiBilling->invoice_number }}
                                </div>
                                <div class="mb-2">
                                    <strong>Fecha de Emisión:</strong> {{ $caiBilling->issue_date->format('d/m/Y') }}
                                </div>
                                @if($caiBilling->due_date)
                                    <div class="mb-2">
                                        <strong>Fecha de Vencimiento:</strong> {{ $caiBilling->due_date->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Información del Cliente</h5>
                                <div class="mb-2">
                                    <strong>Cliente:</strong> {{ $caiBilling->customer_name }}
                                </div>
                                @if($caiBilling->customer_rtn)
                                    <div class="mb-2">
                                        <strong>RTN:</strong> {{ $caiBilling->customer_rtn }}
                                    </div>
                                @endif
                                @if($caiBilling->customer_address)
                                    <div class="mb-2">
                                        <strong>Dirección:</strong><br>
                                        {{ $caiBilling->customer_address }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Descripción</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Precio Unit.</th>
                                        <th class="text-center">% Imp.</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-end">Impuesto</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($caiBilling->items)
                                        @foreach($caiBilling->items as $item)
                                            @php
                                                $subtotal = $item['quantity'] * $item['unit_price'];
                                                $tax = $subtotal * (($item['tax_rate'] ?? 0) / 100);
                                                $total = $subtotal + $tax;
                                            @endphp
                                            <tr>
                                                <td>{{ $item['description'] }}</td>
                                                <td class="text-center">{{ number_format($item['quantity'], 2) }}</td>
                                                <td class="text-end">L. {{ number_format($item['unit_price'], 2) }}</td>
                                                <td class="text-center">{{ $item['tax_rate'] ?? 0 }}%</td>
                                                <td class="text-end">L. {{ number_format($subtotal, 2) }}</td>
                                                <td class="text-end">L. {{ number_format($tax, 2) }}</td>
                                                <td class="text-end fw-bold">L. {{ number_format($total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals -->
                        <div class="row">
                            <div class="col-md-6">
                                @if($caiBilling->notes)
                                    <h6 class="text-primary mb-2">Notas:</h6>
                                    <div class="bg-light p-3 rounded">
                                        {{ $caiBilling->notes }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="fw-bold">Subtotal:</td>
                                            <td class="text-end">L. {{ number_format($caiBilling->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Impuestos:</td>
                                            <td class="text-end">L. {{ number_format($caiBilling->tax_amount, 2) }}</td>
                                        </tr>
                                        @if($caiBilling->discount_amount > 0)
                                            <tr>
                                                <td class="fw-bold text-danger">Descuento:</td>
                                                <td class="text-end text-danger">- L. {{ number_format($caiBilling->discount_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr class="table-primary">
                                            <td class="fw-bold fs-5">TOTAL:</td>
                                            <td class="text-end fw-bold fs-5">L. {{ number_format($caiBilling->total_amount, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-1"></i>
                            Estado de la Factura
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="badge bg-{{ $caiBilling->status_color }} fs-4 px-3 py-2">
                                {{ $caiBilling->status_label }}
                            </span>
                        </div>
                        
                        @if($caiBilling->status !== 'paid')
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                <i class="fas fa-exchange-alt me-1"></i>
                                Cambiar Estado
                            </button>
                        @endif

                        @if($caiBilling->status === 'paid')
                            <div class="mt-3">
                                <small class="text-muted">
                                    <strong>Método de Pago:</strong> {{ ucfirst($caiBilling->payment_method) }}<br>
                                    <strong>Fecha de Pago:</strong> {{ $caiBilling->payment_date->format('d/m/Y') }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bolt me-1"></i>
                            Acciones Rápidas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('cai-billings.pdf', $caiBilling) }}" class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>
                                Descargar PDF
                            </a>
                            
                            @if($caiBilling->status !== 'paid')
                                <a href="{{ route('cai-billings.edit', $caiBilling) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit me-1"></i>
                                    Editar Factura
                                </a>
                            @endif

                            <button type="button" class="btn btn-outline-info" onclick="window.print()">
                                <i class="fas fa-print me-1"></i>
                                Imprimir
                            </button>

                            @if($caiBilling->customer)
                                <a href="{{ route('customers.show', $caiBilling->customer) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-user me-1"></i>
                                    Ver Cliente
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-line me-1"></i>
                            Resumen
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="fs-5 fw-bold text-primary">{{ count($caiBilling->items ?? []) }}</div>
                                    <small class="text-muted">Artículos</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fs-5 fw-bold text-success">{{ $caiBilling->formatted_total }}</div>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="small text-muted">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Creada:</span>
                                <span>{{ $caiBilling->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Actualizada:</span>
                                <span>{{ $caiBilling->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
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
                        
                        <div class="mb-3">
                            <label for="newStatus" class="form-label">Nuevo Estado</label>
                            <select name="status" id="newStatus" class="form-select" required>
                                <option value="draft" {{ $caiBilling->status == 'draft' ? 'selected' : '' }}>Borrador</option>
                                <option value="issued" {{ $caiBilling->status == 'issued' ? 'selected' : '' }}>Emitida</option>
                                <option value="paid" {{ $caiBilling->status == 'paid' ? 'selected' : '' }}>Pagada</option>
                                <option value="cancelled" {{ $caiBilling->status == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>

                        <div id="paymentFields" style="display: {{ $caiBilling->status == 'paid' ? 'block' : 'none' }};">
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Método de Pago</label>
                                <select name="payment_method" id="paymentMethod" class="form-select">
                                    <option value="efectivo" {{ $caiBilling->payment_method == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="transferencia" {{ $caiBilling->payment_method == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                    <option value="cheque" {{ $caiBilling->payment_method == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="tarjeta" {{ $caiBilling->payment_method == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paymentDate" class="form-label">Fecha de Pago</label>
                                <input type="date" name="payment_date" id="paymentDate" class="form-control" 
                                       value="{{ $caiBilling->payment_date ? $caiBilling->payment_date->format('Y-m-d') : '' }}">
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
    // Handle status change
    $('#newStatus').change(function() {
        if ($(this).val() === 'paid') {
            $('#paymentFields').show();
            $('#paymentMethod').prop('required', true);
            $('#paymentDate').prop('required', true);
            if (!$('#paymentDate').val()) {
                $('#paymentDate').val(new Date().toISOString().split('T')[0]);
            }
        } else {
            $('#paymentFields').hide();
            $('#paymentMethod').prop('required', false);
            $('#paymentDate').prop('required', false);
        }
    });

    // Submit status change
    $('#changeStatusForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: `{{ route('cai-billings.change-status', $caiBilling) }}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
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
});
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .card-header, .modal, .sidebar {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .container-fluid {
        max-width: 100% !important;
        padding: 0 !important;
    }
}
</style>
@endpush
