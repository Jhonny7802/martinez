@extends('layouts.app')

@section('title')
    Proyectos de Diseño
@endsection

@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-panel.css') }}">
    <style>
        .design-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .design-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
        }
        .priority-indicator {
            width: 4px;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
        }
        .filter-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .btn-modern {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-palette text-primary mr-2"></i>
                    Proyectos de Diseño
                </h2>
                <p class="text-muted mb-0">Gestiona y crea proyectos de diseño profesionales</p>
            </div>
            <a href="{{ route('design-projects.create') }}" class="btn btn-primary btn-modern">
                <i class="fas fa-plus mr-2"></i>Nuevo Proyecto
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $designProjects->total() }}</h4>
                            <small>Total Proyectos</small>
                        </div>
                        <i class="fas fa-project-diagram fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-warning">{{ $designProjects->where('status', 'in_progress')->count() }}</h4>
                                <small class="text-muted">En Progreso</small>
                            </div>
                            <i class="fas fa-cog fa-spin fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-success">{{ $designProjects->where('status', 'completed')->count() }}</h4>
                                <small class="text-muted">Completados</small>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-danger">{{ $designProjects->where('priority', 'urgent')->count() }}</h4>
                                <small class="text-muted">Urgentes</small>
                            </div>
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('design-projects.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Estado</label>
                        <select name="status" class="form-control">
                            <option value="">Todos los estados</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>En Revisión</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Cliente</label>
                        <select name="customer_id" class="form-control">
                            <option value="">Todos los clientes</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Buscar</label>
                        <input type="text" name="search" class="form-control" placeholder="Nombre del proyecto o descripción..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-modern w-100">
                            <i class="fas fa-search mr-1"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Projects Grid -->
        <div class="row">
            @forelse($designProjects as $project)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="design-card card h-100 position-relative">
                        <div class="priority-indicator bg-{{ $project->priority_color }}"></div>
                        
                        @if($project->preview_image)
                            <img src="{{ asset('storage/' . $project->preview_image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Preview">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 font-weight-bold">{{ $project->project_name }}</h5>
                                <span class="status-badge bg-{{ $project->status_color }} text-white">
                                    {{ $project->status_label }}
                                </span>
                            </div>
                            
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-building mr-1"></i>{{ $project->customer->company_name ?? 'Sin cliente' }}
                            </p>
                            
                            @if($project->description)
                                <p class="card-text">{{ Str::limit($project->description, 80) }}</p>
                            @endif

                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-muted">Prioridad</small>
                                    <br>
                                    <span class="badge badge-{{ $project->priority_color }}">{{ $project->priority_label }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Presupuesto</small>
                                    <br>
                                    <strong>${{ number_format($project->budget, 2) }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Fecha límite</small>
                                    <br>
                                    <small>{{ $project->deadline ? $project->deadline->format('d/m/Y') : 'Sin fecha' }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('design-projects.show', $project) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($project->isEditable())
                                    <a href="{{ route('design-projects.edit', $project) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="generatePreview({{ $project->id }})">
                                    <i class="fas fa-magic"></i>
                                </button>
                                @if($project->isEditable())
                                    <form method="POST" action="{{ route('design-projects.destroy', $project) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Estás seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-palette fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay proyectos de diseño</h4>
                            <p class="text-muted mb-4">Comienza creando tu primer proyecto de diseño</p>
                            <a href="{{ route('design-projects.create') }}" class="btn btn-primary btn-modern">
                                <i class="fas fa-plus mr-2"></i>Crear Primer Proyecto
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($designProjects->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $designProjects->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@section('page_js')
<script>
    function generatePreview(projectId) {
        $.ajax({
            url: `/admin/design-projects/${projectId}/generate-preview`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                toastr.error('Error al generar vista previa');
            }
        });
    }
</script>
@endsection
