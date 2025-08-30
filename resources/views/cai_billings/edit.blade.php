@extends('layouts.app')

@section('title')
    Editar Factura CAI {{ $caiBilling->invoice_number }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-warning me-2"></i>
                Editar Factura CAI {{ $caiBilling->invoice_number }}
            </h1>
            <div class="btn-group">
                <a href="{{ route('cai-billings.show', $caiBilling) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Volver
                </a>
                <a href="{{ route('cai-billings.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list me-1"></i>
                    Lista
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('cai-billings.update', $caiBilling) }}" id="caiBillingForm">
            @csrf
            @method('PUT')
            
            <!-- CAI Info Card -->
            <div class="card shadow mb-4 border-warning">
                <div class="card-header py-3 bg-warning bg-opacity-10">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-info-circle me-1"></i>
                        Información CAI
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Número CAI</label>
                                <input type="text" class="form-control bg-light" value="{{ $caiBilling->cai_number }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Número de Factura</label>
                                <input type="text" class="form-control bg-light" value="{{ $caiBilling->invoice_number }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-1"></i>
                        Información del Cliente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar cliente...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $caiBilling->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->company_name ?: $customer->first_name . ' ' . $customer->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nombre del Cliente <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" id="customer_name" 
                                       class="form-control @error('customer_name') is-invalid @enderror"
                                       value="{{ old('customer_name', $caiBilling->customer_name) }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_rtn" class="form-label">RTN del Cliente</label>
                                <input type="text" name="customer_rtn" id="customer_rtn" 
                                       class="form-control @error('customer_rtn') is-invalid @enderror"
                                       value="{{ old('customer_rtn', $caiBilling->customer_rtn) }}" placeholder="0000-0000-00000">
                                @error('customer_rtn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_address" class="form-label">Dirección del Cliente</label>
                                <textarea name="customer_address" id="customer_address" 
                                          class="form-control @error('customer_address') is-invalid @enderror"
                                          rows="3">{{ old('customer_address', $caiBilling->customer_address) }}</textarea>
                                @error('customer_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-invoice me-1"></i>
                        Detalles de la Factura
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="issue_date" class="form-label">Fecha de Emisión <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" id="issue_date" 
                                       class="form-control @error('issue_date') is-invalid @enderror"
                                       value="{{ old('issue_date', $caiBilling->issue_date->format('Y-m-d')) }}" required>
                                @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" name="due_date" id="due_date" 
                                       class="form-control @error('due_date') is-invalid @enderror"
                                       value="{{ old('due_date', $caiBilling->due_date ? $caiBilling->due_date->format('Y-m-d') : '') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status', $caiBilling->status) == 'draft' ? 'selected' : '' }}>Borrador</option>
                                    <option value="issued" {{ old('status', $caiBilling->status) == 'issued' ? 'selected' : '' }}>Emitida</option>
                                    <option value="paid" {{ old('status', $caiBilling->status) == 'paid' ? 'selected' : '' }}>Pagada</option>
                                    <option value="cancelled" {{ old('status', $caiBilling->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-1"></i>
                        Artículos de la Factura
                    </h6>
                    <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                        <i class="fas fa-plus me-1"></i>
                        Agregar Artículo
                    </button>
                </div>
                <div class="card-body">
                    <div id="itemsContainer">
                        <!-- Items will be loaded here -->
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="fw-bold">Subtotal:</td>
                                        <td class="text-end" id="subtotalDisplay">L. 0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Impuestos:</td>
                                        <td class="text-end" id="taxDisplay">L. 0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Descuento:</td>
                                        <td class="text-end">
                                            <input type="number" name="discount_amount" id="discount_amount" 
                                                   class="form-control form-control-sm text-end" 
                                                   value="{{ old('discount_amount', $caiBilling->discount_amount) }}" 
                                                   min="0" step="0.01">
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td class="fw-bold">Total:</td>
                                        <td class="text-end fw-bold" id="totalDisplay">L. 0.00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-sticky-note me-1"></i>
                        Notas Adicionales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                  rows="4" placeholder="Notas adicionales sobre la factura...">{{ old('notes', $caiBilling->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <button type="submit" class="btn btn-warning btn-lg me-3">
                        <i class="fas fa-save me-1"></i>
                        Actualizar Factura CAI
                    </button>
                    <a href="{{ route('cai-billings.show', $caiBilling) }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Item Template (Hidden) -->
    <template id="itemTemplate">
        <div class="item-row border rounded p-3 mb-3 bg-light">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Descripción <span class="text-danger">*</span></label>
                    <input type="text" name="items[INDEX][description]" class="form-control item-description" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad <span class="text-danger">*</span></label>
                    <input type="number" name="items[INDEX][quantity]" class="form-control item-quantity" 
                           min="0.01" step="0.01" value="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Precio Unitario <span class="text-danger">*</span></label>
                    <input type="number" name="items[INDEX][unit_price]" class="form-control item-price" 
                           min="0" step="0.01" value="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">% Impuesto</label>
                    <input type="number" name="items[INDEX][tax_rate]" class="form-control item-tax" 
                           min="0" max="100" step="0.01" value="15">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Total</label>
                    <input type="text" class="form-control item-total bg-white" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-item-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let itemIndex = 0;
    const existingItems = @json($caiBilling->items ?? []);

    // Load existing items
    if (existingItems.length > 0) {
        existingItems.forEach(function(item) {
            addItem(item);
        });
    } else {
        addItem();
    }

    // Customer selection change
    $('#customer_id').change(function() {
        const customerId = $(this).val();
        if (customerId) {
            $.get(`/admin/cai-billings/customer-data/${customerId}`)
                .done(function(data) {
                    $('#customer_name').val(data.name);
                    $('#customer_rtn').val(data.rtn);
                    $('#customer_address').val(data.address);
                })
                .fail(function() {
                    console.error('Error loading customer data');
                });
        } else {
            $('#customer_name').val('');
            $('#customer_rtn').val('');
            $('#customer_address').val('');
        }
    });

    // Add item button
    $('#addItemBtn').click(function() {
        addItem();
    });

    // Remove item
    $(document).on('click', '.remove-item-btn', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            calculateTotals();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Debe haber al menos un artículo en la factura'
            });
        }
    });

    // Calculate item total on input change
    $(document).on('input', '.item-quantity, .item-price, .item-tax', function() {
        calculateItemTotal($(this).closest('.item-row'));
        calculateTotals();
    });

    // Calculate totals when discount changes
    $('#discount_amount').on('input', function() {
        calculateTotals();
    });

    function addItem(itemData = null) {
        const template = $('#itemTemplate').html();
        const itemHtml = template.replace(/INDEX/g, itemIndex);
        const $itemRow = $(itemHtml);
        
        if (itemData) {
            $itemRow.find('.item-description').val(itemData.description || '');
            $itemRow.find('.item-quantity').val(itemData.quantity || 1);
            $itemRow.find('.item-price').val(itemData.unit_price || 0);
            $itemRow.find('.item-tax').val(itemData.tax_rate || 15);
        }
        
        $('#itemsContainer').append($itemRow);
        itemIndex++;
        
        if (itemData) {
            calculateItemTotal($itemRow);
        }
        calculateTotals();
    }

    function calculateItemTotal(row) {
        const quantity = parseFloat(row.find('.item-quantity').val()) || 0;
        const price = parseFloat(row.find('.item-price').val()) || 0;
        const taxRate = parseFloat(row.find('.item-tax').val()) || 0;
        
        const subtotal = quantity * price;
        const tax = subtotal * (taxRate / 100);
        const total = subtotal + tax;
        
        row.find('.item-total').val('L. ' + total.toFixed(2));
    }

    function calculateTotals() {
        let subtotal = 0;
        let totalTax = 0;

        $('.item-row').each(function() {
            const quantity = parseFloat($(this).find('.item-quantity').val()) || 0;
            const price = parseFloat($(this).find('.item-price').val()) || 0;
            const taxRate = parseFloat($(this).find('.item-tax').val()) || 0;
            
            const itemSubtotal = quantity * price;
            const itemTax = itemSubtotal * (taxRate / 100);
            
            subtotal += itemSubtotal;
            totalTax += itemTax;
            
            calculateItemTotal($(this));
        });

        const discount = parseFloat($('#discount_amount').val()) || 0;
        const total = subtotal + totalTax - discount;

        $('#subtotalDisplay').text('L. ' + subtotal.toFixed(2));
        $('#taxDisplay').text('L. ' + totalTax.toFixed(2));
        $('#totalDisplay').text('L. ' + total.toFixed(2));
    }

    // Form validation
    $('#caiBillingForm').submit(function(e) {
        let isValid = true;
        let errorMessage = '';

        // Check if there are items
        if ($('.item-row').length === 0) {
            isValid = false;
            errorMessage = 'Debe agregar al menos un artículo a la factura.';
        }

        // Check if all items have description and valid quantities/prices
        $('.item-row').each(function() {
            const description = $(this).find('.item-description').val().trim();
            const quantity = parseFloat($(this).find('.item-quantity').val());
            const price = parseFloat($(this).find('.item-price').val());

            if (!description) {
                isValid = false;
                errorMessage = 'Todos los artículos deben tener una descripción.';
                return false;
            }

            if (isNaN(quantity) || quantity <= 0) {
                isValid = false;
                errorMessage = 'Todos los artículos deben tener una cantidad válida mayor a 0.';
                return false;
            }

            if (isNaN(price) || price < 0) {
                isValid = false;
                errorMessage = 'Todos los artículos deben tener un precio válido.';
                return false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error de Validación',
                text: errorMessage
            });
        }
    });

    // Initial calculation
    calculateTotals();
});
</script>
@endpush
