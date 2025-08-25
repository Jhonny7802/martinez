@extends('layouts.app')

@section('title')
    Reporte de Presupuesto
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Reporte de Presupuesto: {{ $budgetControl->project->name }}</h1>
            <div>
                <a href="{{ route('budget-controls.show', $budgetControl->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="{{ route('budget-controls.export-report-pdf', $budgetControl->id) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Resumen del Presupuesto</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Proyecto:</th>
                                <td>{{ $budgetControl->project->name }}</td>
                            </tr>
                            <tr>
                                <th>Presupuesto Total:</th>
                                <td>${{ number_format($budgetControl->total_budget, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Gastado:</th>
                                <td>${{ number_format($budgetControl->current_spent, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Restante:</th>
                                <td>${{ number_format($budgetControl->remaining_budget, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Porcentaje Utilizado:</th>
                                <td>{{ $budgetControl->percentage_spent }}%</td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <span class="badge {{ \App\Models\BudgetControl::STATUS_BADGE[$budgetControl->budget_status] }}">
                                        {{ \App\Models\BudgetControl::STATUS_TEXT[$budgetControl->budget_status] }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Última Actualización:</th>
                                <td>{{ $budgetControl->last_updated ? $budgetControl->last_updated->format('d/m/Y H:i') : 'N/A' }}</td>
                            </tr>
                        </table>

                        <div class="progress mt-3" style="height: 25px;">
                            <div class="progress-bar {{ $budgetControl->percentage_spent > 90 ? 'bg-danger' : ($budgetControl->percentage_spent > 75 ? 'bg-warning' : 'bg-success') }}" 
                                role="progressbar" 
                                style="width: {{ $budgetControl->percentage_spent }}%;" 
                                aria-valuenow="{{ $budgetControl->percentage_spent }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ $budgetControl->percentage_spent }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Distribución de Gastos por Categoría</h5>
                    </div>
                    <div class="card-body">
                        @if(count($expensesByCategory) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Categoría</th>
                                            <th>Monto Total</th>
                                            <th>Cantidad de Gastos</th>
                                            <th>% del Total</th>
                                            <th>Distribución</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expensesByCategory as $category)
                                            <tr>
                                                <td>{{ $category['category_name'] }}</td>
                                                <td>${{ number_format($category['total_amount'], 2) }}</td>
                                                <td>{{ $category['count'] }}</td>
                                                <td>{{ $category['percentage'] }}%</td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-info" 
                                                            role="progressbar" 
                                                            style="width: {{ $category['percentage'] }}%;" 
                                                            aria-valuenow="{{ $category['percentage'] }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                            {{ $category['percentage'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <canvas id="expensesChart" width="400" height="200"></canvas>
                            </div>
                        @else
                            <div class="alert alert-info">
                                No hay gastos registrados para este presupuesto.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Análisis del Presupuesto</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Estado del Presupuesto</h5>
                                        @if($budgetControl->percentage_spent < 50)
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle"></i> El presupuesto se encuentra en buen estado, con un {{ $budgetControl->percentage_spent }}% utilizado.
                                            </div>
                                        @elseif($budgetControl->percentage_spent < 75)
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> El presupuesto se encuentra en estado moderado, con un {{ $budgetControl->percentage_spent }}% utilizado.
                                            </div>
                                        @elseif($budgetControl->percentage_spent < 90)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i> El presupuesto se encuentra en estado de advertencia, con un {{ $budgetControl->percentage_spent }}% utilizado.
                                            </div>
                                        @else
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-circle"></i> El presupuesto se encuentra en estado crítico, con un {{ $budgetControl->percentage_spent }}% utilizado.
                                            </div>
                                        @endif
                                        
                                        <p>Presupuesto restante: ${{ number_format($budgetControl->remaining_budget, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Recomendaciones</h5>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            @if(count($expensesByCategory) > 0)
                // Prepare data for chart
                var categoryNames = {!! json_encode($expensesByCategory->pluck('category_name')->toArray()) !!};
                var categoryAmounts = {!! json_encode($expensesByCategory->pluck('total_amount')->toArray()) !!};
                var categoryPercentages = {!! json_encode($expensesByCategory->pluck('percentage')->toArray()) !!};
                
                // Generate random colors
                var backgroundColors = [];
                for (var i = 0; i < categoryNames.length; i++) {
                    var r = Math.floor(Math.random() * 255);
                    var g = Math.floor(Math.random() * 255);
                    var b = Math.floor(Math.random() * 255);
                    backgroundColors.push('rgba(' + r + ', ' + g + ', ' + b + ', 0.7)');
                }
                
                // Create pie chart
                var ctx = document.getElementById('expensesChart').getContext('2d');
                var expensesChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: categoryNames,
                        datasets: [{
                            data: categoryAmounts,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.raw || 0;
                                        var percentage = categoryPercentages[context.dataIndex];
                                        return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            @endif
        });
    </script>
@endpush
