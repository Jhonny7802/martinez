@extends('layouts.app')

@section('title')
    Control de Presupuestos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Control de Presupuestos</h1>
            <div>
                <a href="{{ route('budget-controls.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Presupuesto
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @include('flash::message')

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="budget-controls-table">
                        <thead class="table-light">
                            <tr>
                                <th>Proyecto</th>
                                <th>Presupuesto Total</th>
                                <th>Gastado</th>
                                <th>Restante</th>
                                <th>% Utilizado</th>
                                <th>Estado</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($budgetControls as $budgetControl)
                                <tr>
                                    <td>
                                        <a href="{{ route('projects.show', $budgetControl->project_id) }}">
                                            {{ $budgetControl->project->name }}
                                        </a>
                                    </td>
                                    <td>{{ number_format($budgetControl->total_budget, 2) }}</td>
                                    <td>{{ number_format($budgetControl->current_spent, 2) }}</td>
                                    <td>{{ number_format($budgetControl->remaining_budget, 2) }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $budgetControl->percentage_spent > 90 ? 'bg-danger' : ($budgetControl->percentage_spent > 75 ? 'bg-warning' : 'bg-success') }}" 
                                                role="progressbar" 
                                                style="width: {{ $budgetControl->percentage_spent }}%;" 
                                                aria-valuenow="{{ $budgetControl->percentage_spent }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $budgetControl->percentage_spent }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ \App\Models\BudgetControl::STATUS_BADGE[$budgetControl->budget_status] }}">
                                            {{ \App\Models\BudgetControl::STATUS_TEXT[$budgetControl->budget_status] }}
                                        </span>
                                    </td>
                                    <td>{{ $budgetControl->last_updated ? $budgetControl->last_updated->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('budget-controls.show', $budgetControl->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('budget-controls.edit', $budgetControl->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('budget-controls.add-expense', $budgetControl->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-plus"></i> Gasto
                                            </a>
                                            <a href="{{ route('budget-controls.report', $budgetControl->id) }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-chart-bar"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $budgetControls->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#budget-controls-table').DataTable({
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
