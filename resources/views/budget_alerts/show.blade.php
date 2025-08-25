@extends('layouts.app')

@section('title')
    Detalle de Alerta de Presupuesto
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Detalle de Alerta #{{ $alert->id }}</h1>
            <div>
                <a href="{{ route('budget-alerts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Alertas
                </a>
                @if(!$alert->is_acknowledged)
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acknowledgeModal">
                        <i class="fas fa-check"></i> Reconocer Alerta
                    </button>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header {{ \App\Models\BudgetAlert::SEVERITY_BG[$alert->severity] }}">
                        <h5 class="card-title mb-0 text-white">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Alerta {{ \App\Models\BudgetAlert::SEVERITY_TEXT[$alert->severity] }}: 
                            {{ \App\Models\BudgetAlert::ALERT_TYPE_TEXT[$alert->alert_type] }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert {{ \App\Models\BudgetAlert::SEVERITY_ALERT[$alert->severity] }}">
                            <h4 class="alert-heading">{{ $alert->message }}</h4>
                            <p>Esta alerta fue generada el {{ $alert->created_at->format('d/m/Y') }} a las {{ $alert->created_at->format('H:i') }}.</p>
                            
                            @if($alert->is_acknowledged)
                                <hr>
                                <p class="mb-0">
                                    <strong>Reconocida por:</strong> {{ $alert->acknowledged_by_user->name ?? 'N/A' }}<br>
                                    <strong>Fecha de reconocimiento:</strong> {{ $alert->acknowledged_at->format('d/m/Y H:i') }}<br>
                                    @if($alert->acknowledgment_notes)
                                        <strong>Notas:</strong> {{ $alert->acknowledgment_notes }}
                                    @endif
                                </p>
                            @endif
                        </div>

                        <h5 class="mt-4">Detalles de la Alerta</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">ID de Alerta</th>
                                <td>{{ $alert->id }}</td>
                            </tr>
                            <tr>
                                <th>Proyecto</th>
                                <td>
                                    <a href="{{ route('projects.show', $alert->project_id) }}">
                                        {{ $alert->project->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Tipo de Alerta</th>
                                <td>{{ \App\Models\BudgetAlert::ALERT_TYPE_TEXT[$alert->alert_type] }}</td>
                            </tr>
                            <tr>
                                <th>Severidad</th>
                                <td>
                                    <span class="badge {{ \App\Models\BudgetAlert::SEVERITY_BADGE[$alert->severity] }}">
                                        {{ \App\Models\BudgetAlert::SEVERITY_TEXT[$alert->severity] }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Fecha de Creación</th>
                                <td>{{ $alert->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Estado</th>
                                <td>
                                    @if($alert->is_acknowledged)
                                        <span class="badge bg-success">Reconocida</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <h5 class="mt-4">Mensaje Completo</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                {{ $alert->message }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Información del Presupuesto</h5>
                    </div>
                    <div class="card-body">
                        <h6>Proyecto: {{ $alert->project->name }}</h6>
                        <p>
                            <strong>Presupuesto Total:</strong> ${{ number_format($alert->budgetControl->total_budget, 2) }}<br>
                            <strong>Gastado Actual:</strong> ${{ number_format($alert->budgetControl->current_spent, 2) }}<br>
                            <strong>Restante:</strong> ${{ number_format($alert->budgetControl->remaining_budget, 2) }}<br>
                            <strong>Umbral de Alerta:</strong> {{ $alert->budgetControl->alert_threshold }}%
                        </p>

                        <div class="progress mt-3" style="height: 20px;">
                            <div class="progress-bar {{ $alert->budgetControl->percentage_spent > 90 ? 'bg-danger' : ($alert->budgetControl->percentage_spent > 75 ? 'bg-warning' : 'bg-success') }}" 
                                role="progressbar" 
                                style="width: {{ $alert->budgetControl->percentage_spent }}%;" 
                                aria-valuenow="{{ $alert->budgetControl->percentage_spent }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ $alert->budgetControl->percentage_spent }}%
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('budget-controls.show', $alert->budgetControl->id) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-pie"></i> Ver Detalle del Presupuesto
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">Acciones Recomendadas</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @if($alert->severity == \App\Models\BudgetAlert::SEVERITY_CRITICAL)
                                <li class="list-group-item list-group-item-danger">
                                    <i class="fas fa-exclamation-circle"></i> Revisar inmediatamente el presupuesto del proyecto
                                </li>
                                <li class="list-group-item list-group-item-danger">
                                    <i class="fas fa-money-bill-wave"></i> Considerar solicitar ampliación de presupuesto
                                </li>
                                <li class="list-group-item list-group-item-danger">
                                    <i class="fas fa-tasks"></i> Priorizar gastos esenciales únicamente
                                </li>
                            @elseif($alert->severity == \App\Models\BudgetAlert::SEVERITY_HIGH)
                                <li class="list-group-item list-group-item-warning">
                                    <i class="fas fa-search-dollar"></i> Revisar detalladamente los gastos recientes
                                </li>
                                <li class="list-group-item list-group-item-warning">
                                    <i class="fas fa-chart-line"></i> Analizar tendencia de gastos
                                </li>
                                <li class="list-group-item list-group-item-warning">
                                    <i class="fas fa-file-invoice-dollar"></i> Preparar informe de gastos para revisión
                                </li>
                            @elseif($alert->severity == \App\Models\BudgetAlert::SEVERITY_MEDIUM)
                                <li class="list-group-item list-group-item-info">
                                    <i class="fas fa-eye"></i> Monitorear de cerca los gastos futuros
                                </li>
                                <li class="list-group-item list-group-item-info">
                                    <i class="fas fa-calendar-alt"></i> Programar revisión de presupuesto
                                </li>
                            @else
                                <li class="list-group-item list-group-item-success">
                                    <i class="fas fa-check-circle"></i> Continuar con el monitoreo regular
                                </li>
                            @endif
                            <li class="list-group-item">
                                <i class="fas fa-file-alt"></i> Documentar acciones tomadas
                            </li>
                        </ul>

                        @if(!$alert->is_acknowledged)
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i> Esta alerta requiere reconocimiento.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para reconocer alerta -->
    <div class="modal fade" id="acknowledgeModal" tabindex="-1" aria-labelledby="acknowledgeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="acknowledgeModalLabel">Reconocer Alerta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budget-alerts.acknowledge', $alert->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>¿Está seguro que desea reconocer esta alerta?</p>
                        <p>Al reconocer la alerta, está confirmando que ha tomado conocimiento de la situación.</p>
                        
                        <div class="form-group">
                            <label for="acknowledgment_notes">Notas (opcional):</label>
                            <textarea id="acknowledgment_notes" name="acknowledgment_notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Reconocer Alerta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
