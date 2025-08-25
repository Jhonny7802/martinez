@extends('layouts.app')

@section('title')
    Detalle de Control de Presupuesto
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Control de Presupuesto: {{ $budgetControl->project->name }}</h1>
            <div>
                <a href="{{ route('budget-controls.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="{{ route('budget-controls.edit', $budgetControl->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('budget-controls.add-expense', $budgetControl->id) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Agregar Gasto
                </a>
                <a href="{{ route('budget-controls.report', $budgetControl->id) }}" class="btn btn-info">
                    <i class="fas fa-chart-bar"></i> Reporte
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Información General</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Proyecto:</th>
                                <td>
                                    <a href="{{ route('projects.show', $budgetControl->project_id) }}">
                                        {{ $budgetControl->project->name }}
                                    </a>
                                </td>
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
                                <th>Umbral de Alerta:</th>
                                <td>{{ $budgetControl->alert_threshold }}%</td>
                            </tr>
                            <tr>
                                <th>Última Actualización:</th>
                                <td>{{ $budgetControl->last_updated ? $budgetControl->last_updated->format('d/m/Y H:i') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <span class="badge {{ \App\Models\BudgetControl::STATUS_BADGE[$budgetControl->budget_status] }}">
                                        {{ \App\Models\BudgetControl::STATUS_TEXT[$budgetControl->budget_status] }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Progreso del Presupuesto</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="text-center">{{ $budgetControl->percentage_spent }}% Utilizado</h4>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar {{ $budgetControl->percentage_spent > 90 ? 'bg-danger' : ($budgetControl->percentage_spent > 75 ? 'bg-warning' : 'bg-success') }}" 
                                role="progressbar" 
                                style="width: {{ $budgetControl->percentage_spent }}%;" 
                                aria-valuenow="{{ $budgetControl->percentage_spent }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ $budgetControl->percentage_spent }}%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>$0</span>
                            <span>${{ number_format($budgetControl->total_budget, 2) }}</span>
                        </div>

                        <div class="mt-4">
                            <h5>Notas:</h5>
                            <p>{{ $budgetControl->notes ?? 'Sin notas adicionales.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Gastos Registrados</h5>
                        <a href="{{ route('budget-controls.add-expense', $budgetControl->id) }}" class="btn btn-sm btn-light">
                            <i class="fas fa-plus"></i> Nuevo Gasto
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="expenses-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th>Categoría</th>
                                        <th>Monto</th>
                                        <th>Recibo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->expense_date ? $expense->expense_date->format('d/m/Y') : $expense->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $expense->description }}</td>
                                            <td>{{ $expense->category->name }}</td>
                                            <td>${{ number_format($expense->amount, 2) }}</td>
                                            <td>
                                                @if($expense->receipt_path)
                                                    <a href="{{ route('budget-expenses.download-receipt', $expense->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="badge bg-secondary">Sin recibo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('budget-expenses.show', $expense->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('budget-expenses.edit', $expense->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No hay gastos registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $expenses->links() }}
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Alertas de Presupuesto</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="alerts-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Mensaje</th>
                                        <th>Severidad</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($alerts as $alert)
                                        <tr>
                                            <td>{{ $alert->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $alert->message }}</td>
                                            <td>{!! $alert->severity_badge !!}</td>
                                            <td>
                                                @if($alert->is_acknowledged)
                                                    <span class="badge bg-success">Reconocida</span>
                                                @else
                                                    <span class="badge bg-danger">Pendiente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('budget-alerts.show', $alert->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(!$alert->is_acknowledged)
                                                        <a href="{{ route('budget-alerts.acknowledge', $alert->id) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check"></i> Reconocer
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No hay alertas de presupuesto.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $alerts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#expenses-table, #alerts-table').DataTable({
                paging: false,
                searching: true,
                ordering: true,
                info: false,
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });
    </script>
@endpush
