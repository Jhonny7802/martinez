@extends('layouts.app')
@section('title')
    {{ __('messages.dashboard') }} - Sistema de Construcción
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            transition: width 0.6s ease;
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .construction-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .metric-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header construction-bg rounded-3 p-4 mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-hard-hat me-3" style="font-size: 2rem;"></i>
                <div>
                    <h1 class="mb-1">Dashboard de Construcción Martinez</h1>
                    <p class="mb-0 opacity-75">Sistema Integral de Gestión de Proyectos</p>
                </div>
            </div>
        </div>
        @include('flash::message')
        <!-- Métricas Principales de Construcción -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="metric-card construction-card">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon text-primary me-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Proyectos Activos</h6>
                            <h3 class="mb-0 text-primary">{{ $projectStatusCount['in_progress'] ?? 0 }}</h3>
                            <small class="text-success"><i class="fas fa-arrow-up"></i> En construcción</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="metric-card construction-card">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon text-success me-3">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Ingresos del Mes</h6>
                            <h3 class="mb-0 text-success">${{ number_format($monthWiseRecords['income'][date('F')] ?? 0, 2) }}</h3>
                            <small class="text-muted">Facturas pagadas</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="metric-card construction-card">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon text-warning me-3">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Personal Activo</h6>
                            <h3 class="mb-0 text-warning">{{ $memberCount['active_members'] ?? 0 }}</h3>
                            <small class="text-muted">Trabajadores</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="metric-card construction-card">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon text-info me-3">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Presupuestos</h6>
                            <h3 class="mb-0 text-info">{{ $estimateStatusCount['total_estimates'] ?? 0 }}</h3>
                            <small class="text-success">{{ $estimateStatusCount['accepted'] ?? 0 }} aprobados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado de Proyectos con Indicadores HTML -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card metric-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Estado de Proyectos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $totalProjects = $projectStatusCount['total_projects'] ?? 1;
                                $statuses = [
                                    'not_started' => ['label' => 'Sin Iniciar', 'color' => 'danger', 'icon' => 'fa-pause'],
                                    'in_progress' => ['label' => 'En Progreso', 'color' => 'primary', 'icon' => 'fa-play'],
                                    'on_hold' => ['label' => 'En Pausa', 'color' => 'warning', 'icon' => 'fa-pause'],
                                    'finished' => ['label' => 'Terminados', 'color' => 'success', 'icon' => 'fa-check']
                                ];
                            @endphp
                            @foreach($statuses as $key => $status)
                                @php
                                    $count = $projectStatusCount[$key] ?? 0;
                                    $percentage = $totalProjects > 0 ? ($count * 100) / $totalProjects : 0;
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="status-indicator bg-{{ $status['color'] }}"></span>
                                        <strong class="me-auto">{{ $status['label'] }}</strong>
                                        <span class="badge bg-{{ $status['color'] }}">{{ $count }}</span>
                                    </div>
                                    <div class="progress progress-modern">
                                        <div class="progress-bar progress-bar-modern bg-{{ $status['color'] }}" 
                                             style="width: {{ $percentage }}%" 
                                             title="{{ number_format($percentage, 1) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($percentage, 1) }}% del total</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card metric-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Resumen Financiero</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalIncome = array_sum($monthWiseRecords['income'] ?? []);
                            $totalExpenses = array_sum($monthWiseRecords['expenses'] ?? []);
                            $profit = $totalIncome - $totalExpenses;
                            $profitPercentage = $totalIncome > 0 ? ($profit * 100) / $totalIncome : 0;
                        @endphp
                        <div class="text-center mb-3">
                            <h4 class="text-{{ $profit >= 0 ? 'success' : 'danger' }}">
                                ${{ number_format($profit, 2) }}
                            </h4>
                            <small class="text-muted">Ganancia del Año</small>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Ingresos</small>
                                <small class="text-success">${{ number_format($totalIncome, 2) }}</small>
                            </div>
                            <div class="progress progress-modern">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Gastos</small>
                                <small class="text-danger">${{ number_format($totalExpenses, 2) }}</small>
                            </div>
                            <div class="progress progress-modern">
                                <div class="progress-bar bg-danger" 
                                     style="width: {{ $totalIncome > 0 ? ($totalExpenses * 100) / $totalIncome : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tarjetas de Módulos Principales -->
        <div class="row mb-4">
            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-3">
                <div class="card metric-card construction-card">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center">
                        <i class="fas fa-file-invoice text-primary me-3" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0">
                            <a href="{{route('invoices.index')}}" class="text-decoration-none">Facturación CAI</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 col-md-3">
                                <div class="mb-2">
                                    <h4 class="text-warning mb-1">{{ $invoiceStatusCount['drafted'] ?? 0 }}</h4>
                                    <small class="text-muted">Borrador</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mb-2">
                                    <h4 class="text-danger mb-1">{{ $invoiceStatusCount['unpaid'] ?? 0 }}</h4>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mb-2">
                                    <h4 class="text-info mb-1">{{ $invoiceStatusCount['partially_paid'] ?? 0 }}</h4>
                                    <small class="text-muted">Parciales</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mb-2">
                                    <h4 class="text-success mb-1">{{ $invoiceStatusCount['paid'] ?? 0 }}</h4>
                                    <small class="text-muted">Pagadas</small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h3 class="text-primary mb-0">{{ $invoiceStatusCount['total_invoices'] ?? 0 }}</h3>
                            <small class="text-muted">Total de Facturas</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-3">
                <div class="card metric-card construction-card">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center">
                        <i class="fas fa-calculator text-info me-3" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0">
                            <a href="{{route('estimates.index')}}" class="text-decoration-none">Presupuestos</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <h4 class="text-warning mb-1">{{ $estimateStatusCount['drafted'] ?? 0 }}</h4>
                                <small class="text-muted">Borrador</small>
                            </div>
                            <div class="col-6 mb-2">
                                <h4 class="text-primary mb-1">{{ $estimateStatusCount['sent'] ?? 0 }}</h4>
                                <small class="text-muted">Enviados</small>
                            </div>
                            <div class="col-6 mb-2">
                                <h4 class="text-success mb-1">{{ $estimateStatusCount['accepted'] ?? 0 }}</h4>
                                <small class="text-muted">Aceptados</small>
                            </div>
                            <div class="col-6 mb-2">
                                <h4 class="text-danger mb-1">{{ $estimateStatusCount['expired'] ?? 0 }}</h4>
                                <small class="text-muted">Vencidos</small>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h3 class="text-info mb-0">{{ $estimateStatusCount['total_estimates'] ?? 0 }}</h3>
                            <small class="text-muted">Total de Presupuestos</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-3">
                <div class="card metric-card construction-card">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center">
                        <i class="fas fa-scroll text-success me-3" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0">
                            <a href="{{route('proposals.index')}}" class="text-decoration-none">Propuestas Técnicas</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <h4 class="text-warning mb-1">{{ $proposalStatusCount['drafted'] ?? 0 }}</h4>
                                <small class="text-muted">Borrador</small>
                            </div>
                            <div class="col-6 mb-2">
                                <h4 class="text-danger mb-1">{{ $proposalStatusCount['open'] ?? 0 }}</h4>
                                <small class="text-muted">Abiertas</small>
                            </div>
                            <div class="col-6 mb-2">
                                <h4 class="text-success mb-1">{{ $proposalStatusCount['accepted'] ?? 0 }}</h4>
                                <small class="text-muted">Aceptadas</small>
                            </div>
                            <div class="col-6 mb-2">
                                <h4 class="text-info mb-1">{{ $proposalStatusCount['declined'] ?? 0 }}</h4>
                                <small class="text-muted">Rechazadas</small>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h3 class="text-success mb-0">{{ $proposalStatusCount['total_proposals'] ?? 0 }}</h3>
                            <small class="text-muted">Total de Propuestas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulos Adicionales -->
        <div class="row mb-4">
            {{-- Enhanced Inventory Management Card --}}
            @can('manage_materials')
                <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-3">
                    <div class="card metric-card construction-card">
                        <div class="card-header bg-transparent border-0 d-flex align-items-center">
                            <i class="fas fa-boxes text-primary me-3" style="font-size: 1.5rem;"></i>
                            <h5 class="mb-0">
                                <a href="{{route('material-requisitions.index')}}" class="text-decoration-none">Inventario y Materiales</a>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-2">
                                    <h4 class="text-danger mb-1" id="lowStockCount">{{ $inventoryAlerts['low_stock_count'] ?? 0 }}</h4>
                                    <small class="text-muted">Stock Bajo</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <h4 class="text-warning mb-1" id="outOfStockCount">{{ $inventoryAlerts['out_of_stock_count'] ?? 0 }}</h4>
                                    <small class="text-muted">Agotados</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <h4 class="text-info mb-1" id="pendingRequisitions">{{ $inventoryAlerts['pending_requisitions'] ?? 0 }}</h4>
                                    <small class="text-muted">Requisiciones</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <h4 class="text-success mb-1" id="totalValue">${{ number_format($materialsStats['total_value'] ?? 0, 0) }}</h4>
                                    <small class="text-muted">Valor Total</small>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <h3 class="text-primary mb-0" id="totalMaterials">{{ $materialsStats['total_materials'] ?? 0 }}</h3>
                                <small class="text-muted">Materiales Activos</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            {{-- Enhanced Internal Messages Card --}}
            @can('manage_internal_messages')
                <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-3">
                    <div class="card metric-card construction-card">
                        <div class="card-header bg-transparent border-0 d-flex align-items-center">
                            <i class="fas fa-comments text-info me-3" style="font-size: 1.5rem;"></i>
                            <h5 class="mb-0">
                                <a href="{{route('enhanced-messages.index')}}" class="text-decoration-none">Mensajes Internos</a>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-2">
                                    <h4 class="text-danger mb-1" id="unreadMessages">{{ $messageStats['unread_count'] ?? 0 }}</h4>
                                    <small class="text-muted">Sin Leer</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <h4 class="text-success mb-1" id="todayMessages">{{ $messageStats['today_count'] ?? 0 }}</h4>
                                    <small class="text-muted">Hoy</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <h4 class="text-warning mb-1" id="sentMessages">{{ $messageStats['sent_count'] ?? 0 }}</h4>
                                    <small class="text-muted">Enviados</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <h4 class="text-info mb-1" id="totalMessages">{{ $messageStats['total_count'] ?? 0 }}</h4>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <a href="{{ route('enhanced-messages.create') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit me-1"></i>Nuevo Mensaje
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-3">
                <div class="card metric-card construction-card">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center">
                        <i class="fas fa-users-cog text-warning me-3" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0">
                            <a href="{{route('members.index')}}" class="text-decoration-none">Personal de Obra</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="mb-3">
                                    <h3 class="text-success mb-1">{{ $memberCount['active_members'] ?? 0 }}</h3>
                                    <small class="text-muted">Activos</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h3 class="text-danger mb-1">{{ $memberCount['deactive_members'] ?? 0 }}</h3>
                                    <small class="text-muted">Inactivos</small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h3 class="text-warning mb-0">{{ $memberCount['total_members'] ?? 0 }}</h3>
                            <small class="text-muted">Total de Personal</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-3">
                <div class="card metric-card construction-card">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center">
                        <i class="fas fa-handshake text-secondary me-3" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0">
                            <a href="{{route('customers.index')}}" class="text-decoration-none">Clientes</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="fas fa-users text-secondary" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                            <h2 class="text-secondary mb-1">{{ $customerCount['total_customers'] ?? 0 }}</h2>
                            <small class="text-muted">Clientes Registrados</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-3">
                <div class="card metric-card construction-card">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center">
                        <i class="fas fa-tools text-dark me-3" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0">Herramientas Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{route('projects.create')}}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-2"></i>Nuevo Proyecto
                            </a>
                            <a href="{{route('estimates.create')}}" class="btn btn-info btn-sm">
                                <i class="fas fa-calculator me-2"></i>Crear Presupuesto
                            </a>
                            <a href="{{route('invoices.create')}}" class="btn btn-success btn-sm">
                                <i class="fas fa-file-invoice me-2"></i>Nueva Factura
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- Actividad Reciente y Contratos -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card metric-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Rendimiento Semanal</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $weekDays = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                            $maxPayment = max(array_values($currentWeekInvoices ?? [1]));
                        @endphp
                        <div class="row">
                            @foreach($weekDays as $day)
                                @php
                                    $payment = $currentWeekInvoices[$day] ?? 0;
                                    $percentage = $maxPayment > 0 ? ($payment * 100) / $maxPayment : 0;
                                @endphp
                                <div class="col">
                                    <div class="text-center mb-2">
                                        <small class="text-muted">{{ substr($day, 0, 3) }}</small>
                                    </div>
                                    <div class="progress progress-modern mb-2" style="height: 80px; writing-mode: bt-lr; transform: rotate(180deg);">
                                        <div class="progress-bar bg-primary" style="height: {{ $percentage }}%" title="${{ number_format($payment, 2) }}"></div>
                                    </div>
                                    <div class="text-center">
                                        <small class="text-success">${{ number_format($payment, 0) }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <small class="text-muted">Ingresos por Día de la Semana</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card metric-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Actividad Mensual</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $currentMonthIncome = $monthWiseRecords['income'][date('F')] ?? 0;
                            $currentMonthExpenses = $monthWiseRecords['expenses'][date('F')] ?? 0;
                            $monthlyProfit = $currentMonthIncome - $currentMonthExpenses;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Ingresos {{ date('F') }}</small>
                                <small class="text-success">${{ number_format($currentMonthIncome, 2) }}</small>
                            </div>
                            <div class="progress progress-modern">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Gastos {{ date('F') }}</small>
                                <small class="text-danger">${{ number_format($currentMonthExpenses, 2) }}</small>
                            </div>
                            <div class="progress progress-modern">
                                <div class="progress-bar bg-danger" style="width: {{ $currentMonthIncome > 0 ? ($currentMonthExpenses * 100) / $currentMonthIncome : 0 }}%"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h4 class="text-{{ $monthlyProfit >= 0 ? 'success' : 'danger' }} mb-1">
                                ${{ number_format($monthlyProfit, 2) }}
                            </h4>
                            <small class="text-muted">Ganancia del Mes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contratos y Actividad Reciente -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card metric-card">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Contratos por Vencer</h5>
                        <div class="d-flex align-items-center">
                            <label class="me-2 mb-0">Mes:</label>
                            {!! Form::select('month', $months, $currentMonth,['class' => 'form-select form-select-sm', 'id' => 'monthId', 'style' => 'width: auto;']) !!}
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
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script>
        // Dashboard moderno sin Chart.js - Solo funcionalidad esencial
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
            
            // Filtro de mes para contratos
            $('#monthId').on('change', function() {
                let month = $(this).val();
                $.ajax({
                    url: '{{ route('dashboard.contract-month-filter') }}',
                    type: 'GET',
                    data: { month: month },
                    success: function(response) {
                        updateContractsTable(response.data);
                    }
                });
            });
            
            // Función para actualizar tabla de contratos
            function updateContractsTable(contracts) {
                let tbody = $('.expiring-contracts');
                tbody.empty();
                
                if (contracts.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No hay contratos para este mes
                            </td>
                        </tr>
                    `);
                    return;
                }
                
                contracts.forEach(function(contract) {
                    let startDate = contract.start_date ? new Date(contract.start_date).toLocaleDateString('es-ES') : 'N/A';
                    let endDate = contract.end_date ? new Date(contract.end_date).toLocaleDateString('es-ES') : 'N/A';
                    let customerName = contract.customer ? contract.customer.company_name : 'Cliente no especificado';
                    
                    // Calcular días restantes
                    let daysLeft = 0;
                    let statusBadge = '';
                    if (contract.end_date) {
                        let endDateObj = new Date(contract.end_date);
                        let today = new Date();
                        daysLeft = Math.ceil((endDateObj - today) / (1000 * 60 * 60 * 24));
                        
                        if (daysLeft < 0) {
                            statusBadge = '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Vencido</span>';
                        } else if (daysLeft <= 7) {
                            statusBadge = `<span class="badge bg-warning"><i class="fas fa-exclamation me-1"></i>${daysLeft} días</span>`;
                        } else {
                            statusBadge = `<span class="badge bg-success"><i class="fas fa-check me-1"></i>${daysLeft} días</span>`;
                        }
                    }
                    
                    tbody.append(`
                        <tr>
                            <td>${contract.subject || 'Sin asunto'}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-muted me-2"></i>
                                    ${customerName}
                                </div>
                            </td>
                            <td><span class="badge bg-info">${startDate}</span></td>
                            <td><span class="badge bg-warning">${endDate}</span></td>
                            <td>${statusBadge}</td>
                        </tr>
                    `);
                });
            }
            
            // Animaciones para las métricas
            $('.metric-card').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );
            
            // Actualizar métricas cada 5 minutos
            setInterval(function() {
                // Aquí se puede agregar lógica para actualizar métricas en tiempo real
                console.log('Actualizando métricas del dashboard...');
            }, 300000); // 5 minutos
        });
    </script>
@endsection
