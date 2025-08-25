<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Construcción Martinez</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header del Dashboard -->
        <div class="dashboard-header text-center">
            <h1><i class="fas fa-hard-hat me-3"></i>Dashboard de Construcción Martinez</h1>
            <p class="lead mb-0">Sistema de Gestión Integral de Proyectos de Construcción</p>
        </div>

        <!-- Métricas Principales -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card construction-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-icon text-primary mb-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="text-primary mb-1">12</h3>
                        <p class="text-muted mb-0">Proyectos Activos</p>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card construction-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-icon text-success mb-3">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3 class="text-success mb-1">L.8,500,000</h3>
                        <p class="text-muted mb-0">Ingresos del Mes</p>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card construction-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-icon text-warning mb-3">
                            <i class="fas fa-users-hard-hat"></i>
                        </div>
                        <h3 class="text-warning mb-1">45</h3>
                        <p class="text-muted mb-0">Personal Activo</p>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card construction-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-icon text-info mb-3">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="text-info mb-1">18</h3>
                        <p class="text-muted mb-0">Clientes Activos</p>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 65%"></div>
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
                                        <span class="badge bg-success rounded-pill">3</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span>Completados</span>
                                            <span class="text-muted">25%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: 25%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-primary rounded-pill">7</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span>En Progreso</span>
                                            <span class="text-muted">58%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" style="width: 58%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-warning rounded-pill">2</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span>Pendientes</span>
                                            <span class="text-muted">17%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" style="width: 17%"></div>
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
                            <h4 class="text-success">L.5,250,000</h4>
                            <small class="text-muted">Ganancia del Mes</small>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Ingresos</span>
                                <span class="text-success">L.8.5M</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Gastos</span>
                                <span class="text-danger">L.3.25M</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: 38%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proyectos Recientes -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card construction-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Proyectos Recientes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-building me-1"></i>Proyecto</th>
                                    <th><i class="fas fa-user me-1"></i>Cliente</th>
                                    <th><i class="fas fa-calendar me-1"></i>Fecha Inicio</th>
                                    <th><i class="fas fa-dollar-sign me-1"></i>Presupuesto</th>
                                    <th><i class="fas fa-tasks me-1"></i>Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Residencial Las Torres</td>
                                    <td>Constructora San Miguel</td>
                                    <td>15/10/2024</td>
                                    <td>L.15,000,000</td>
                                    <td><span class="badge bg-primary">En Progreso</span></td>
                                </tr>
                                <tr>
                                    <td>Centro Comercial Plaza Norte</td>
                                    <td>Desarrollos del Norte</td>
                                    <td>01/09/2024</td>
                                    <td>L.25,000,000</td>
                                    <td><span class="badge bg-primary">En Progreso</span></td>
                                </tr>
                                <tr>
                                    <td>Complejo Industrial La Ceiba</td>
                                    <td>Proyectos La Ceiba</td>
                                    <td>20/11/2024</td>
                                    <td>L.8,000,000</td>
                                    <td><span class="badge bg-warning">Pendiente</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        console.log('Dashboard de construcción cargado correctamente');
    </script>
</body>
</html>
