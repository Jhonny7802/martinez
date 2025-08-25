<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Alertas de Presupuesto</title>
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
        h3 {
            color: #4a5568;
            font-size: 16px;
            margin: 10px 0;
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
        .alerts-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .alerts-table th {
            background-color: #4a5568;
            color: white;
            text-align: left;
            padding: 10px;
        }
        .alerts-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .alerts-table tr:nth-child(even) {
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
        .status-critical {
            background-color: #e53e3e;
        }
        .status-high {
            background-color: #ed8936;
        }
        .status-medium {
            background-color: #3182ce;
        }
        .status-low {
            background-color: #48bb78;
        }
        .status-acknowledged {
            background-color: #48bb78;
        }
        .status-pending {
            background-color: #ed8936;
        }
        .summary-box {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #4a5568;
        }
        .summary-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-box {
            width: 23%;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
        }
        .stat-critical {
            background-color: #fed7d7;
            border: 1px solid #e53e3e;
        }
        .stat-high {
            background-color: #feebc8;
            border: 1px solid #ed8936;
        }
        .stat-medium {
            background-color: #bee3f8;
            border: 1px solid #3182ce;
        }
        .stat-low {
            background-color: #c6f6d5;
            border: 1px solid #48bb78;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
        }
        .recommendations {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #4a5568;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE ALERTAS DE PRESUPUESTO</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
        @if(request('date_from') || request('date_to'))
            <p>
                Período: 
                {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : 'Inicio' }} 
                - 
                {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : 'Actualidad' }}
            </p>
        @endif
    </div>

    <div class="summary-box">
        <h2>Resumen de Alertas</h2>
        <div class="summary-stats">
            <div class="stat-box stat-critical">
                <div class="stat-number">{{ $alertStats['critical'] }}</div>
                <div>Críticas</div>
            </div>
            <div class="stat-box stat-high">
                <div class="stat-number">{{ $alertStats['high'] }}</div>
                <div>Altas</div>
            </div>
            <div class="stat-box stat-medium">
                <div class="stat-number">{{ $alertStats['medium'] }}</div>
                <div>Medias</div>
            </div>
            <div class="stat-box stat-low">
                <div class="stat-number">{{ $alertStats['low'] }}</div>
                <div>Bajas</div>
            </div>
        </div>
        
        <table class="summary-table">
            <tr>
                <th>Total de Alertas:</th>
                <td>{{ $alertStats['total'] }}</td>
                <th>Alertas Reconocidas:</th>
                <td>{{ $alertStats['acknowledged'] }} ({{ $alertStats['total'] > 0 ? round(($alertStats['acknowledged'] / $alertStats['total']) * 100) : 0 }}%)</td>
            </tr>
            <tr>
                <th>Alertas Pendientes:</th>
                <td>{{ $alertStats['unacknowledged'] }} ({{ $alertStats['total'] > 0 ? round(($alertStats['unacknowledged'] / $alertStats['total']) * 100) : 0 }}%)</td>
                <th>Proyectos Afectados:</th>
                <td>{{ $alertStats['projects_affected'] }}</td>
            </tr>
        </table>
    </div>

    <h2>Proyectos con Alertas Críticas</h2>
    @if(count($criticalProjects) > 0)
        <table class="alerts-table">
            <thead>
                <tr>
                    <th>Proyecto</th>
                    <th>Alertas</th>
                    <th>Alertas Críticas</th>
                    <th>% Utilizado</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($criticalProjects as $project)
                    <tr>
                        <td>{{ $project['name'] }}</td>
                        <td>{{ $project['alerts_count'] }}</td>
                        <td>{{ $project['critical_count'] }}</td>
                        <td>{{ $project['budget_usage'] }}%</td>
                        <td>
                            @if($project['budget_status'])
                                <span class="status-badge status-{{ $project['budget_status'] == 'at_risk' ? 'critical' : ($project['budget_status'] == 'warning' ? 'high' : 'medium') }}">
                                    {{ $project['budget_status'] == 'at_risk' ? 'En Riesgo' : ($project['budget_status'] == 'warning' ? 'Advertencia' : 'Normal') }}
                                </span>
                            @else
                                <span class="status-badge status-medium">No Disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay proyectos con alertas críticas en el período seleccionado.</p>
    @endif

    <div class="page-break"></div>

    <h2>Listado de Alertas</h2>
    @if(count($alerts) > 0)
        <table class="alerts-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Proyecto</th>
                    <th>Tipo</th>
                    <th>Severidad</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alerts as $alert)
                    <tr>
                        <td>{{ $alert->id }}</td>
                        <td>{{ $alert->project->name }}</td>
                        <td>{{ $alert->alert_type ? (\App\Models\BudgetAlert::ALERT_TYPE_TEXT[$alert->alert_type] ?? 'Desconocido') : 'Presupuesto' }}</td>
                        <td>
                            @php
                                $severityClass = 'medium';
                                $severityText = 'Normal';
                                
                                if ($alert->severity == \App\Models\BudgetAlert::SEVERITY_EMERGENCY || $alert->severity == \App\Models\BudgetAlert::SEVERITY_CRITICAL) {
                                    $severityClass = 'critical';
                                    $severityText = 'Crítica';
                                } elseif ($alert->severity == \App\Models\BudgetAlert::SEVERITY_WARNING) {
                                    $severityClass = 'high';
                                    $severityText = 'Alta';
                                } elseif ($alert->severity == \App\Models\BudgetAlert::SEVERITY_INFO) {
                                    $severityClass = 'medium';
                                    $severityText = 'Media';
                                }
                            @endphp
                            <span class="status-badge status-{{ $severityClass }}">
                                {{ $severityText }}
                            </span>
                        </td>
                        <td>{{ $alert->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="status-badge status-{{ $alert->is_acknowledged ? 'acknowledged' : 'pending' }}">
                                {{ $alert->is_acknowledged ? 'Reconocida' : 'Pendiente' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay alertas en el período seleccionado.</p>
    @endif

    <div class="recommendations">
        <h2>Análisis y Recomendaciones</h2>
        
        <h3>Estado General de Alertas</h3>
        @if($alertStats['critical'] > 0)
            <p>El sistema presenta <strong>{{ $alertStats['critical'] }} alertas críticas</strong> que requieren atención inmediata. Se recomienda revisar urgentemente los presupuestos de los proyectos afectados.</p>
        @elseif($alertStats['high'] > 0)
            <p>El sistema presenta <strong>{{ $alertStats['high'] }} alertas de alta severidad</strong> que requieren atención pronta. Se recomienda revisar los presupuestos de los proyectos afectados en los próximos días.</p>
        @elseif($alertStats['medium'] > 0)
            <p>El sistema presenta <strong>{{ $alertStats['medium'] }} alertas de severidad media</strong>. Se recomienda monitorear los presupuestos de los proyectos afectados.</p>
        @else
            <p>El sistema no presenta alertas de severidad alta o crítica. Se recomienda continuar con el monitoreo regular de los presupuestos.</p>
        @endif
        
        <h3>Recomendaciones</h3>
        <ul>
            @if($alertStats['unacknowledged'] > 0)
                <li>Revisar y reconocer las {{ $alertStats['unacknowledged'] }} alertas pendientes.</li>
            @endif
            
            @if($alertStats['critical'] > 0 || $alertStats['high'] > 0)
                <li>Programar una reunión de revisión de presupuestos con los gerentes de proyecto.</li>
                <li>Evaluar la necesidad de ajustar los presupuestos de los proyectos con alertas críticas.</li>
                <li>Implementar medidas de control de gastos en los proyectos afectados.</li>
            @endif
            
            <li>Continuar monitoreando los umbrales de alerta en todos los proyectos activos.</li>
            <li>Documentar las acciones tomadas para resolver las alertas existentes.</li>
        </ul>
    </div>

    <div class="footer">
        <p>Sistema de Gestión de Construcción Martinez - Reporte generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</p>
        <p>Página 1 de 2</p>
    </div>
</body>
</html>
