@extends('layouts.app')

@section('title')
    Alertas de Presupuesto
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Alertas de Presupuesto</h1>
            <div>
                <a href="{{ route('budget-alerts.export-report') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar Reporte
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="severity-filter">Filtrar por Severidad:</label>
                            <select id="severity-filter" class="form-control">
                                <option value="">Todas</option>
                                <option value="{{ \App\Models\BudgetAlert::SEVERITY_LOW }}">Baja</option>
                                <option value="{{ \App\Models\BudgetAlert::SEVERITY_MEDIUM }}">Media</option>
                                <option value="{{ \App\Models\BudgetAlert::SEVERITY_HIGH }}">Alta</option>
                                <option value="{{ \App\Models\BudgetAlert::SEVERITY_CRITICAL }}">Crítica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status-filter">Filtrar por Estado:</label>
                            <select id="status-filter" class="form-control">
                                <option value="">Todos</option>
                                <option value="0">No Reconocidas</option>
                                <option value="1">Reconocidas</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="project-filter">Filtrar por Proyecto:</label>
                            <select id="project-filter" class="form-control">
                                <option value="">Todos los Proyectos</option>
                                @if(isset($projects) && count($projects) > 0)
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date-filter">Filtrar por Fecha:</label>
                            <input type="date" id="date-filter" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="alerts-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Proyecto</th>
                                <th>Tipo</th>
                                <th>Severidad</th>
                                <th>Mensaje</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alerts as $alert)
                                <tr class="{{ !$alert->is_acknowledged ? 'table-warning' : '' }}">
                                    <td>{{ $alert->id }}</td>
                                    <td>
                                        <a href="{{ route('projects.show', $alert->project_id) }}">
                                            {{ $alert->project->project_name }}
                                        </a>
                                    </td>
                                    <td>{{ \App\Models\BudgetAlert::ALERT_TYPE_TEXT[$alert->alert_type] }}</td>
                                    <td>
                                        <span class="badge {{ \App\Models\BudgetAlert::SEVERITY_BADGE[$alert->severity] }}">
                                            {{ \App\Models\BudgetAlert::SEVERITY_TEXT[$alert->severity] }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($alert->message, 50) }}</td>
                                    <td>{{ $alert->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($alert->is_acknowledged)
                                            <span class="badge bg-success">Reconocida</span>
                                        @else
                                            <span class="badge bg-warning">Pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('budget-alerts.show', $alert->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$alert->is_acknowledged)
                                                <button type="button" class="btn btn-sm btn-success acknowledge-btn" data-id="{{ $alert->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('budget-controls.show', $alert->budget_control_id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-chart-pie"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Resumen de Alertas</h5>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="alert alert-danger mb-2">
                                    <h3>{{ $alertStats['critical'] }}</h3>
                                    <p class="mb-0">Críticas</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="alert alert-warning mb-2">
                                    <h3>{{ $alertStats['high'] }}</h3>
                                    <p class="mb-0">Altas</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="alert alert-info mb-2">
                                    <h3>{{ $alertStats['medium'] }}</h3>
                                    <p class="mb-0">Medias</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="alert alert-success mb-2">
                                    <h3>{{ $alertStats['low'] }}</h3>
                                    <p class="mb-0">Bajas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Proyectos con Alertas Críticas</h5>
                        @if(isset($criticalProjects) && count($criticalProjects) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Proyecto</th>
                                            <th>Presupuesto Total</th>
                                            <th>Gastado</th>
                                            <th>% Utilizado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($criticalProjects as $project)
                                            <tr>
                                                <td>{{ $project->project_name }}</td>
                                                <td>${{ number_format($project->budgetControl->total_budget, 2) }}</td>
                                                <td>${{ number_format($project->budgetControl->current_spent, 2) }}</td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-danger" 
                                                            role="progressbar" 
                                                            style="width: {{ $project->budgetControl->percentage_spent }}%;" 
                                                            aria-valuenow="{{ $project->budgetControl->percentage_spent }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                            {{ $project->budgetControl->percentage_spent }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('budget-controls.show', $project->budgetControl->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-chart-pie"></i> Ver Presupuesto
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> No hay proyectos con alertas críticas actualmente.
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
                <form id="acknowledgeForm" method="POST">
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

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            var table = $('#alerts-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                order: [[5, 'desc']], // Ordenar por fecha descendente
                pageLength: 10
            });
            
            // Filtros
            $('#severity-filter, #status-filter, #project-filter, #date-filter').on('change', function() {
                table.draw();
            });
            
            // Custom filtering function
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var severityFilter = $('#severity-filter').val();
                    var statusFilter = $('#status-filter').val();
                    var projectFilter = $('#project-filter').val();
                    var dateFilter = $('#date-filter').val();
                    
                    var severity = data[3]; // Columna de severidad
                    var status = data[6]; // Columna de estado
                    var project = data[1]; // Columna de proyecto
                    var date = data[5]; // Columna de fecha
                    
                    // Filtro de severidad
                    if (severityFilter && !severity.includes(severityFilter)) {
                        return false;
                    }
                    
                    // Filtro de estado
                    if (statusFilter === '1' && !status.includes('Reconocida')) {
                        return false;
                    }
                    if (statusFilter === '0' && !status.includes('Pendiente')) {
                        return false;
                    }
                    
                    // Filtro de proyecto
                    if (projectFilter && !project.includes(projectFilter)) {
                        return false;
                    }
                    
                    // Filtro de fecha
                    if (dateFilter) {
                        var filterDate = new Date(dateFilter);
                        var rowDate = new Date(date.split(' ')[0].split('/').reverse().join('-'));
                        if (filterDate.getTime() !== rowDate.getTime()) {
                            return false;
                        }
                    }
                    
                    return true;
                }
            );
            
            // Modal de reconocimiento
            $('.acknowledge-btn').on('click', function() {
                var alertId = $(this).data('id');
                $('#acknowledgeForm').attr('action', '/budget-alerts/' + alertId + '/acknowledge');
                $('#acknowledgeModal').modal('show');
            });
        });
    </script>
@endpush
