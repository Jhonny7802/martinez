@extends('layouts.app')

@section('title')
    Reporte de Alertas de Presupuesto
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Reporte de Alertas de Presupuesto</h1>
            <div>
                <a href="{{ route('budget-alerts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Alertas
                </a>
                <a href="{{ route('budget-alerts.export-report-pdf') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Resumen de Alertas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h2>{{ $alertStats['critical'] }}</h2>
                                        <p class="mb-0">Alertas Críticas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <h2>{{ $alertStats['high'] }}</h2>
                                        <p class="mb-0">Alertas Altas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h2>{{ $alertStats['medium'] }}</h2>
                                        <p class="mb-0">Alertas Medias</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h2>{{ $alertStats['low'] }}</h2>
                                        <p class="mb-0">Alertas Bajas</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6>Total de Alertas: {{ $alertStats['total'] }}</h6>
                            <div class="progress" style="height: 25px;">
                                @if($alertStats['total'] > 0)
                                    <div class="progress-bar bg-danger" style="width: {{ ($alertStats['critical'] / $alertStats['total']) * 100 }}%">
                                        {{ $alertStats['critical'] }}
                                    </div>
                                    <div class="progress-bar bg-warning" style="width: {{ ($alertStats['high'] / $alertStats['total']) * 100 }}%">
                                        {{ $alertStats['high'] }}
                                    </div>
                                    <div class="progress-bar bg-info" style="width: {{ ($alertStats['medium'] / $alertStats['total']) * 100 }}%">
                                        {{ $alertStats['medium'] }}
                                    </div>
                                    <div class="progress-bar bg-success" style="width: {{ ($alertStats['low'] / $alertStats['total']) * 100 }}%">
                                        {{ $alertStats['low'] }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6>Estado de Reconocimiento</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3>{{ $alertStats['acknowledged'] }}</h3>
                                            <p class="mb-0">Reconocidas</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3>{{ $alertStats['unacknowledged'] }}</h3>
                                            <p class="mb-0">Pendientes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Filtros de Reporte</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('budget-alerts.report') }}" method="GET">
                            <div class="form-group mb-3">
                                <label for="date_from">Desde:</label>
                                <input type="date" id="date_from" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="date_to">Hasta:</label>
                                <input type="date" id="date_to" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="severity">Severidad:</label>
                                <select id="severity" name="severity" class="form-control">
                                    <option value="">Todas</option>
                                    <option value="{{ \App\Models\BudgetAlert::SEVERITY_CRITICAL }}" {{ request('severity') == \App\Models\BudgetAlert::SEVERITY_CRITICAL ? 'selected' : '' }}>Crítica</option>
                                    <option value="{{ \App\Models\BudgetAlert::SEVERITY_HIGH }}" {{ request('severity') == \App\Models\BudgetAlert::SEVERITY_HIGH ? 'selected' : '' }}>Alta</option>
                                    <option value="{{ \App\Models\BudgetAlert::SEVERITY_MEDIUM }}" {{ request('severity') == \App\Models\BudgetAlert::SEVERITY_MEDIUM ? 'selected' : '' }}>Media</option>
                                    <option value="{{ \App\Models\BudgetAlert::SEVERITY_LOW }}" {{ request('severity') == \App\Models\BudgetAlert::SEVERITY_LOW ? 'selected' : '' }}>Baja</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="project_id">Proyecto:</label>
                                <select id="project_id" name="project_id" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="is_acknowledged">Estado:</label>
                                <select id="is_acknowledged" name="is_acknowledged" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('is_acknowledged') == '1' ? 'selected' : '' }}>Reconocidas</option>
                                    <option value="0" {{ request('is_acknowledged') == '0' ? 'selected' : '' }}>Pendientes</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <a href="{{ route('budget-alerts.report') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Reiniciar
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">Alertas de Presupuesto</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="alerts-report-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Proyecto</th>
                                        <th>Tipo</th>
                                        <th>Severidad</th>
                                        <th>Mensaje</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alerts as $alert)
                                        <tr class="{{ !$alert->is_acknowledged ? 'table-warning' : '' }}">
                                            <td>{{ $alert->id }}</td>
                                            <td>
                                                <a href="{{ route('projects.show', $alert->project_id) }}">
                                                    {{ $alert->project->name }}
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Análisis de Tendencias</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Alertas por Proyecto</h6>
                                        <canvas id="projectChart" width="100%" height="300"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Alertas por Severidad</h6>
                                        <canvas id="severityChart" width="100%" height="300"></canvas>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h6>Alertas por Fecha</h6>
                                        <canvas id="timelineChart" width="100%" height="200"></canvas>
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
            // Inicializar DataTable
            $('#alerts-report-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                order: [[5, 'desc']], // Ordenar por fecha descendente
                pageLength: 10
            });
            
            // Datos para gráficos
            var projectData = {!! json_encode($projectChartData) !!};
            var severityData = {!! json_encode($severityChartData) !!};
            var timelineData = {!! json_encode($timelineChartData) !!};
            
            // Gráfico de proyectos
            var projectCtx = document.getElementById('projectChart').getContext('2d');
            new Chart(projectCtx, {
                type: 'bar',
                data: {
                    labels: projectData.labels,
                    datasets: [{
                        label: 'Alertas por Proyecto',
                        data: projectData.data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            
            // Gráfico de severidad
            var severityCtx = document.getElementById('severityChart').getContext('2d');
            new Chart(severityCtx, {
                type: 'pie',
                data: {
                    labels: severityData.labels,
                    datasets: [{
                        data: severityData.data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
            
            // Gráfico de línea de tiempo
            var timelineCtx = document.getElementById('timelineChart').getContext('2d');
            new Chart(timelineCtx, {
                type: 'line',
                data: {
                    labels: timelineData.labels,
                    datasets: [{
                        label: 'Alertas por Fecha',
                        data: timelineData.data,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
