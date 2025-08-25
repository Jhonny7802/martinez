<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Presupuesto - {{ $budgetControl->project->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            color: #2d3748;
            font-size: 24px;
            margin: 0;
        }
        h2 {
            color: #4a5568;
            font-size: 18px;
            margin: 15px 0 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table th {
            text-align: left;
            padding: 8px;
            background-color: #f8f9fa;
        }
        .summary-table td {
            padding: 8px;
        }
        .progress-container {
            width: 100%;
            background-color: #e2e8f0;
            border-radius: 4px;
            margin: 10px 0;
            height: 20px;
        }
        .progress-bar {
            height: 20px;
            border-radius: 4px;
            text-align: center;
            color: white;
            font-weight: bold;
        }
        .progress-success {
            background-color: #48bb78;
        }
        .progress-warning {
            background-color: #ed8936;
        }
        .progress-danger {
            background-color: #e53e3e;
        }
        .categories-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .categories-table th {
            background-color: #4a5568;
            color: white;
            text-align: left;
            padding: 10px;
        }
        .categories-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .categories-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        .status-healthy {
            background-color: #48bb78;
        }
        .status-warning {
            background-color: #ed8936;
        }
        .status-critical {
            background-color: #e53e3e;
        }
        .status-exceeded {
            background-color: #2d3748;
        }
        .recommendations {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #4a5568;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE PRESUPUESTO</h1>
        <p>Proyecto: {{ $budgetControl->project->name }}</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <h2>Resumen del Presupuesto</h2>
    <table class="summary-table">
        <tr>
            <th>Presupuesto Total:</th>
            <td>${{ number_format($budgetControl->total_budget, 2) }}</td>
            <th>Estado:</th>
            <td>
                <span class="status-badge status-{{ strtolower(\App\Models\BudgetControl::STATUS_TEXT[$budgetControl->budget_status]) }}">
                    {{ \App\Models\BudgetControl::STATUS_TEXT[$budgetControl->budget_status] }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Gastado:</th>
            <td>${{ number_format($budgetControl->current_spent, 2) }}</td>
            <th>Última Actualización:</th>
            <td>{{ $budgetControl->last_updated ? $budgetControl->last_updated->format('d/m/Y H:i') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Restante:</th>
            <td>${{ number_format($budgetControl->remaining_budget, 2) }}</td>
            <th>Umbral de Alerta:</th>
            <td>{{ $budgetControl->alert_threshold }}%</td>
        </tr>
    </table>

    <h3>Progreso del Presupuesto: {{ $budgetControl->percentage_spent }}% Utilizado</h3>
    <div class="progress-container">
        <div class="progress-bar {{ $budgetControl->percentage_spent > 90 ? 'progress-danger' : ($budgetControl->percentage_spent > 75 ? 'progress-warning' : 'progress-success') }}" 
            style="width: {{ $budgetControl->percentage_spent }}%;">
            {{ $budgetControl->percentage_spent }}%
        </div>
    </div>

    <h2>Distribución de Gastos por Categoría</h2>
    @if(count($expensesByCategory) > 0)
        <table class="categories-table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Monto Total</th>
                    <th>Cantidad</th>
                    <th>% del Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expensesByCategory as $category)
                    <tr>
                        <td>{{ $category['category_name'] }}</td>
                        <td>${{ number_format($category['total_amount'], 2) }}</td>
                        <td>{{ $category['count'] }}</td>
                        <td>{{ $category['percentage'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay gastos registrados para este presupuesto.</p>
    @endif

    <div class="recommendations">
        <h2>Análisis y Recomendaciones</h2>
        
        <h3>Estado del Presupuesto</h3>
        @if($budgetControl->percentage_spent < 50)
            <p>El presupuesto se encuentra en buen estado, con un {{ $budgetControl->percentage_spent }}% utilizado.</p>
        @elseif($budgetControl->percentage_spent < 75)
            <p>El presupuesto se encuentra en estado moderado, con un {{ $budgetControl->percentage_spent }}% utilizado.</p>
        @elseif($budgetControl->percentage_spent < 90)
            <p>El presupuesto se encuentra en estado de advertencia, con un {{ $budgetControl->percentage_spent }}% utilizado.</p>
        @else
            <p>El presupuesto se encuentra en estado crítico, con un {{ $budgetControl->percentage_spent }}% utilizado.</p>
        @endif
        
        <h3>Recomendaciones</h3>
        <ul>
            @if($budgetControl->percentage_spent > 90)
                <li>Considerar solicitar una ampliación del presupuesto.</li>
                <li>Revisar urgentemente los gastos planificados vs. ejecutados.</li>
                <li>Priorizar gastos esenciales únicamente.</li>
            @elseif($budgetControl->percentage_spent > 75)
                <li>Monitorear de cerca los gastos restantes.</li>
                <li>Evaluar posibles ajustes en el plan de gastos.</li>
                <li>Preparar un plan de contingencia si se supera el umbral crítico.</li>
            @else
                <li>Continuar con el plan de gastos establecido.</li>
                <li>Realizar revisiones periódicas del presupuesto.</li>
                <li>Documentar adecuadamente todos los gastos.</li>
            @endif
        </ul>
    </div>

    <div class="footer">
        <p>Sistema de Gestión de Construcción Martinez - Reporte generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</p>
    </div>
</body>
</html>
