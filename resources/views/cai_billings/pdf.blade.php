<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura CAI {{ $caiBilling->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .cai-number {
            font-size: 14px;
            color: #666;
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
        }
        
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .invoice-left, .invoice-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .detail-row {
            margin-bottom: 8px;
        }
        
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .items-table th {
            background-color: #007bff;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #007bff;
        }
        
        .items-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
        }
        
        .items-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .totals-table .total-row {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .notes-section {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-draft { background-color: #6c757d; color: white; }
        .status-issued { background-color: #ffc107; color: #212529; }
        .status-paid { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">SISTEMA DE FACTURACIÓN CAI</div>
        <div class="invoice-title">FACTURA</div>
        <div class="cai-number">CAI: {{ $caiBilling->cai_number }}</div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <div class="invoice-left">
            <div class="section-title">Información de Facturación</div>
            <div class="detail-row">
                <span class="detail-label">Factura No.:</span>
                {{ $caiBilling->invoice_number }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Fecha Emisión:</span>
                {{ $caiBilling->issue_date->format('d/m/Y') }}
            </div>
            @if($caiBilling->due_date)
                <div class="detail-row">
                    <span class="detail-label">Fecha Vencimiento:</span>
                    {{ $caiBilling->due_date->format('d/m/Y') }}
                </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Estado:</span>
                <span class="status-badge status-{{ $caiBilling->status }}">
                    {{ $caiBilling->status_label }}
                </span>
            </div>
        </div>
        
        <div class="invoice-right">
            <div class="section-title">Información del Cliente</div>
            <div class="detail-row">
                <span class="detail-label">Cliente:</span>
                {{ $caiBilling->customer_name }}
            </div>
            @if($caiBilling->customer_rtn)
                <div class="detail-row">
                    <span class="detail-label">RTN:</span>
                    {{ $caiBilling->customer_rtn }}
                </div>
            @endif
            @if($caiBilling->customer_address)
                <div class="detail-row">
                    <span class="detail-label">Dirección:</span>
                    {{ $caiBilling->customer_address }}
                </div>
            @endif
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%">Descripción</th>
                <th style="width: 10%" class="text-center">Cant.</th>
                <th style="width: 15%" class="text-right">Precio Unit.</th>
                <th style="width: 10%" class="text-center">% Imp.</th>
                <th style="width: 15%" class="text-right">Subtotal</th>
                <th style="width: 10%" class="text-right">Total</th>
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
                        <td class="text-right">L. {{ number_format($item['unit_price'], 2) }}</td>
                        <td class="text-center">{{ $item['tax_rate'] ?? 0 }}%</td>
                        <td class="text-right">L. {{ number_format($subtotal, 2) }}</td>
                        <td class="text-right">L. {{ number_format($total, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-right">L. {{ number_format($caiBilling->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Impuestos:</strong></td>
                <td class="text-right">L. {{ number_format($caiBilling->tax_amount, 2) }}</td>
            </tr>
            @if($caiBilling->discount_amount > 0)
                <tr>
                    <td><strong>Descuento:</strong></td>
                    <td class="text-right" style="color: #dc3545;">- L. {{ number_format($caiBilling->discount_amount, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>L. {{ number_format($caiBilling->total_amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Notes -->
    @if($caiBilling->notes)
        <div class="notes-section">
            <div class="section-title">Notas</div>
            <p>{{ $caiBilling->notes }}</p>
        </div>
    @endif

    <!-- Payment Information -->
    @if($caiBilling->status === 'paid' && $caiBilling->payment_method)
        <div class="notes-section">
            <div class="section-title">Información de Pago</div>
            <div class="detail-row">
                <span class="detail-label">Método de Pago:</span>
                {{ ucfirst($caiBilling->payment_method) }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Fecha de Pago:</span>
                {{ $caiBilling->payment_date->format('d/m/Y') }}
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Factura generada el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>CAI: {{ $caiBilling->cai_number }} | Factura: {{ $caiBilling->invoice_number }}</p>
    </div>
</body>
</html>
