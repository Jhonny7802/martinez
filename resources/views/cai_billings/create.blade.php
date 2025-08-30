@extends('layouts.app')

@section('title')
    Nueva Factura CAI
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle text-primary me-2"></i>
                Nueva Factura CAI
            </h1>
            <a href="{{ route('cai-billings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Volver
            </a>
        </div>

        <form method="POST" action="{{ route('cai-billings.store') }}" id="caiBillingForm">
            @csrf
            
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
                                <label for="company_name" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select name="company_name" id="company_name" class="form-select @error('company_name') is-invalid @enderror" required>
                                    <option value="">Seleccionar cliente...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->company_name }}" {{ old('company_name') == $customer->company_name ? 'selected' : '' }}>
                                            {{ $customer->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_rtn" class="form-label">RTN del Cliente</label>
                                <input type="text" name="customer_rtn" id="customer_rtn" 
                                       class="form-control @error('customer_rtn') is-invalid @enderror"
                                       value="{{ old('customer_rtn') }}" placeholder="0000-0000-00000">
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
                                          rows="3">{{ old('customer_address') }}</textarea>
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
                                       value="{{ old('issue_date', date('Y-m-d')) }}" required>
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
                                       value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Borrador</option>
                                    <option value="issued" {{ old('status') == 'issued' ? 'selected' : '' }}>Emitida</option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Pagada</option>
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
                        <!-- Items will be added here dynamically -->
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
                                                   value="{{ old('discount_amount', 0) }}" 
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
                                  rows="4" placeholder="Notas adicionales sobre la factura...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <button type="submit" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-save me-1"></i>
                        Crear Factura CAI
                    </button>
                    <a href="{{ route('cai-billings.index') }}" class="btn btn-outline-secondary btn-lg">
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
                <div class="col-md-3">
                    <label class="form-label">Producto <span class="text-danger">*</span></label>
                    <select name="items[INDEX][product_id]" class="form-select item-product" required>
                        <option value="">Seleccionar producto...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->rate }}" 
                                    data-description="{{ $product->title }}"
                                    data-tax="{{ $product->firstTax ? $product->firstTax->rate : 15 }}">
                                {{ $product->title }} - L. {{ number_format($product->rate, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="items[INDEX][description]" class="item-description">
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
                <div class="col-md-2">
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

    // Add first item on load
    addItem();

    // Customer selection change
    $('#company_name').change(function() {
        const companyName = $(this).val();
        if (companyName) {
            // Find the customer data from the dropdown
            const customers = @json($customers);
            const selectedCustomer = customers.find(c => c.company_name === companyName);
            if (selectedCustomer) {
                $('#customer_rtn').val(selectedCustomer.vat_number || '');
                $('#customer_address').val('');
            }
        } else {
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

    // Product selection change
    $(document).on('change', '.item-product', function() {
        const row = $(this).closest('.item-row');
        const selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val()) {
            const price = parseFloat(selectedOption.data('price')) || 0;
            const description = selectedOption.data('description') || '';
            const tax = parseFloat(selectedOption.data('tax')) || 15;
            
            row.find('.item-price').val(price.toFixed(2));
            row.find('.item-description').val(description);
            row.find('.item-tax').val(tax);
        } else {
            row.find('.item-price').val('0.00');
            row.find('.item-description').val('');
            row.find('.item-tax').val('15');
        }
        
        calculateItemTotal(row);
        calculateTotals();
    });

    // Calculate item total on input change
    $(document).on('input', '.item-quantity, .item-price, .item-tax', function() {
        const row = $(this).closest('.item-row');
        
        calculateItemTotal(row);
        calculateTotals();
    });

    // Calculate totals when discount changes
    $('#discount_amount').on('input', function() {
        calculateTotals();
    });

    function addItem() {
        const template = $('#itemTemplate').html();
        const itemHtml = template.replace(/INDEX/g, itemIndex);
        $('#itemsContainer').append(itemHtml);
        itemIndex++;
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
});
</script>
@endpush
