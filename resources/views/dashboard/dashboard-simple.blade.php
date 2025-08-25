@extends('layouts.app')
@section('title')
    Dashboard - Sistema de Construcción Martinez
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .construction-card {
            border-left: 4px solid #e74c3c;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .construction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .metric-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .progress-modern {
            height: 8px;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .progress-bar-modern {
            border-radius: 10px;
            background: linear-gradient(45deg, #007bff, #0056b3);
        }
        .metric-card {
            transition: all 0.3s ease;
        }
        .metric-card:hover {
            transform: translateY(-5px);
        }
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <!-- Header del Dashboard -->
        <div class="dashboard-header text-center">
            <h3><i class="fas fa-hard-hat me-2"></i>Dashboard de Construcción Martinez</h3>
            <p class="mb-0">Sistema de Gestión Integral de Proyectos de Construcción</p>
        </div>

        <!-- Métricas Principales -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card construction-card metric-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-icon text-primary mb-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="text-primary mb-1">{{ $projectStatusCount['total'] ?? 12 }}</h3>
                        <p class="text-muted mb-0">Proyectos Activos</p>
                        <div class="progress progress-modern mt-2">
                            <div class="progress-bar progress-bar-modern" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card construction-card metric-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-icon text-success mb-3">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3 class="text-success mb-1">L.{{ number_format(($invoiceStatusCount['total_amount'] ?? 8500000), 0) }}</h3>
                        <p class="text-muted mb-0">Ingresos del Mes</p>
                        <div class="progress progress-modern mt-2">
                            <div class="progress-bar bg-success progress-bar-modern" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card construction-card metric-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-icon text-warning mb-3">
                            <i class="fas fa-users-hard-hat"></i>
                        </div>
                        <h3 class="text-warning mb-1">{{ $memberCount ?? 45 }}</h3>
                        <p class="text-muted mb-0">Personal Activo</p>
                        <div class="progress progress-modern mt-2">
                            <div class="progress-bar bg-warning progress-bar-modern" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card construction-card metric-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-icon text-info mb-3">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="text-info mb-1">{{ $customerCount ?? 18 }}</h3>
                        <p class="text-muted mb-0">Clientes Activos</p>
                        <div class="progress progress-modern mt-2">
                            <div class="progress-bar bg-info progress-bar-modern" style="width: 65%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado de Proyectos -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card construction-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Estado de Proyectos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-success rounded-pill">{{ $projectStatusCount['finished'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span>Finalizados</span>
                                            <span class="text-muted">0%</span>
                                        </div>
                                        <div class="progress progress-modern">
                                            <div class="progress-bar bg-success" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-primary rounded-pill">{{ $projectStatusCount['in_progress'] ?? 3 }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span>En Progreso</span>
                                            <span class="text-muted">75%</span>
                                        </div>
                                        <div class="progress progress-modern">
                                            <div class="progress-bar bg-primary" style="width: 75%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-warning rounded-pill">{{ $projectStatusCount['not_started'] ?? 1 }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span>No Iniciados</span>
                                            <span class="text-muted">25%</span>
                                        </div>
                                        <div class="progress progress-modern">
                                            <div class="progress-bar bg-warning" style="width: 25%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-info rounded-pill">{{ $projectStatusCount['on_hold'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span>En Pausa</span>
                                            <span class="text-muted">0%</span>
                                        </div>
                                        <div class="progress progress-modern">
                                            <div class="progress-bar bg-info" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card construction-card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Resumen Financiero</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h4 class="text-success">L.{{ number_format(1645000, 0) }}</h4>
                            <small class="text-muted">Ganancia del Mes</small>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Ingresos</span>
                                <span class="text-success">L.3.75M</span>
                            </div>
                            <div class="progress progress-modern">
                                <div class="progress-bar bg-success" style="width: 64%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Gastos</span>
                                <span class="text-danger">L.2.1M</span>
                            </div>
                            <div class="progress progress-modern">
                                <div class="progress-bar bg-danger" style="width: 36%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contratos por Vencer -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card construction-card">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Contratos por Vencer</h5>
                        <div class="d-flex align-items-center">
                            <label class="me-2 mb-0">Mes:</label>
                            <select class="form-select form-select-sm" id="monthId" style="width: auto;">
                                @foreach($months ?? [] as $key => $month)
                                    <option value="{{ $key }}" {{ ($currentMonth ?? date('n')) == $key ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="contractExpiredTable">
                                <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-file-alt me-1"></i>Asunto</th>
                                    <th><i class="fas fa-user me-1"></i>Cliente</th>
                                    <th><i class="fas fa-calendar-plus me-1"></i>Fecha Inicio</th>
                                    <th><i class="fas fa-calendar-times me-1"></i>Fecha Fin</th>
                                    <th><i class="fas fa-exclamation-triangle me-1"></i>Estado</th>
                                </tr>
                                </thead>
                                <tbody class="expiring-contracts">
                                @if(isset($contractsCurrentMonths) && count($contractsCurrentMonths) > 0)
                                    @foreach($contractsCurrentMonths as $contract)
                                        <tr>
                                            <td>{{ $contract->subject ?? 'Sin asunto' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-building text-muted me-2"></i>
                                                    {{ $contract->customer->company_name ?? 'Cliente no especificado' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($contract->end_date)
                                                    @php
                                                        $endDate = \Carbon\Carbon::parse($contract->end_date);
                                                        $daysLeft = \Carbon\Carbon::now()->diffInDays($endDate, false);
                                                    @endphp
                                                    @if($daysLeft < 0)
                                                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Vencido</span>
                                                    @elseif($daysLeft <= 7)
                                                        <span class="badge bg-warning"><i class="fas fa-exclamation me-1"></i>{{ $daysLeft }} días</span>
                                                    @else
                                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ $daysLeft }} días</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Sin fecha</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            No hay contratos por vencer este mes
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTable para contratos
            $('#contractExpiredTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[3, 'asc']], // Ordenar por fecha de fin
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                }
            });
            
            // Animaciones para las métricas
            $('.metric-card').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );
            
            console.log('Dashboard de construcción cargado correctamente');
        });
    </script>
@endsection
