@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detalle de Gasto</h1>
            <div>
                <a href="{{ route('budget-expenses.index') }}" class="btn btn-sm btn-secondary shadow-sm ml-2">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver
                </a>
            </div>
        </div>

        @include('flash::message')

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Información del Gasto</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">ID</th>
                                    <td>{{ $expense->id }}</td>
                                </tr>
                                <tr>
                                    <th>Proyecto</th>
                                    <td>
                                        <a href="{{ route('projects.show', $expense->budgetControl->project_id) }}">
                                            {{ $expense->budgetControl->project->project_name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Descripción</th>
                                    <td>{{ $expense->description }}</td>
                                </tr>
                                <tr>
                                    <th>Categoría</th>
                                    <td>{{ $expense->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Monto</th>
                                    <td><strong class="text-danger">${{ number_format($expense->amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Fecha del Gasto</th>
                                    <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Registrado por</th>
                                    <td>{{ $expense->creator->name }} ({{ $expense->created_at->format('d/m/Y H:i') }})</td>
                                </tr>
                                <tr>
                                    <th>Recibo/Factura</th>
                                    <td>
                                        @if($expense->receipt_path)
                                            <a href="{{ route('budget-expenses.download-media', $expense->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Descargar recibo
                                            </a>
                                        @else
                                            <span class="text-muted">No hay recibo adjunto</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Notas</th>
                                    <td>
                                        @if($expense->notes)
                                            {{ $expense->notes }}
                                        @else
                                            <span class="text-muted">Sin notas adicionales</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Información del Presupuesto</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h5>Estado del Presupuesto</h5>
                            <div class="progress mb-2" style="height: 25px;">
                                <div class="progress-bar {{ $expense->budgetControl->percentage_spent > 90 ? 'bg-danger' : ($expense->budgetControl->percentage_spent > 70 ? 'bg-warning' : 'bg-success') }}" 
                                     role="progressbar" 
                                     style="width: {{ $expense->budgetControl->percentage_spent }}%;" 
                                     aria-valuenow="{{ $expense->budgetControl->percentage_spent }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $expense->budgetControl->percentage_spent }}%
                                </div>
                            </div>
                            <small class="text-muted">Porcentaje del presupuesto utilizado</small>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Presupuesto Total</th>
                                    <td>${{ number_format($expense->budgetControl->total_budget, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Gastado</th>
                                    <td>${{ number_format($expense->budgetControl->current_spent, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Restante</th>
                                    <td>${{ number_format($expense->budgetControl->remaining_budget, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('budget-controls.show', $expense->budgetControl->id) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-pie"></i> Ver Presupuesto Completo
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Acciones</h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('budget-expenses.edit', $expense->id) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Editar Gasto
                        </a>
                        
                        <form action="{{ route('budget-expenses.destroy', $expense->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Está seguro de eliminar este gasto? Esta acción actualizará el presupuesto del proyecto.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Eliminar Gasto
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
