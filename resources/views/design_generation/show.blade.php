@extends('layouts.app')

@section('title')
    {{ $designProject->project_name }}
@endsection

@section('page_css')
    <style>
        .project-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .info-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
        }
        .timeline-item {
            border-left: 3px solid #e9ecef;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            width: 12px;
            height: 12px;
            background: #007bff;
            border-radius: 50%;
            position: absolute;
            left: -7.5px;
            top: 0.5rem;
        }
        .design-canvas {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            min-height: 400px;
            position: relative;
            overflow: hidden;
        }
        .design-element {
            position: absolute;
            border: 1px solid #007bff;
            background: rgba(0,123,255,0.1);
            cursor: move;
            border-radius: 4px;
        }
        .btn-modern {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Project Header -->
        <div class="project-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2 font-weight-bold">{{ $designProject->project_name }}</h1>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-building mr-2"></i>{{ $designProject->customer->company_name ?? 'Sin cliente' }}
                    </p>
                </div>
                <div class="col-md-4 text-md-right">
                    <span class="status-badge bg-{{ $designProject->status_color }}">
                        {{ $designProject->status_label }}
                    </span>
                    <br>
                    <small class="opacity-75 mt-2 d-block">
                        Creado: {{ $designProject->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Design Canvas -->
            <div class="col-lg-8">
                <div class="info-card card">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-paint-brush text-primary mr-2"></i>Canvas de Diseño
                            </h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm btn-modern mr-2" onclick="generatePreview()">
                                    <i class="fas fa-magic mr-1"></i>Generar Vista Previa
                                </button>
                                <button class="btn btn-outline-success btn-sm btn-modern" onclick="exportDesign()">
                                    <i class="fas fa-download mr-1"></i>Exportar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="design-canvas" id="designCanvas">
                            @if($designProject->preview_image)
                                <img src="{{ asset('storage/' . $designProject->preview_image) }}" 
                                     class="img-fluid w-100" alt="Vista previa del diseño">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center">
                                        <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">Vista previa no disponible</h5>
                                        <p class="text-muted">Genera una vista previa para ver el diseño</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Design Elements Overlay -->
                            @foreach($designProject->elements as $element)
                                <div class="design-element" 
                                     style="left: {{ $element->position_x }}px; top: {{ $element->position_y }}px; 
                                            width: {{ $element->width }}px; height: {{ $element->height }}px;"
                                     title="{{ $element->element_type_label }}: {{ $element->content }}">
                                    <small class="p-1">{{ $element->element_type_label }}</small>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Dimensiones: {{ $designProject->dimensions }} | 
                                Elementos: {{ $designProject->elements->count() }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Elements List -->
                <div class="info-card card">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-list text-primary mr-2"></i>Elementos del Diseño
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($designProject->elements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Contenido</th>
                                            <th>Posición</th>
                                            <th>Tamaño</th>
                                            <th>Capa</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($designProject->elements->sortBy('layer_order') as $element)
                                            <tr>
                                                <td>
                                                    <span class="badge badge-info">{{ $element->element_type_label }}</span>
                                                </td>
                                                <td>{{ Str::limit($element->content, 30) }}</td>
                                                <td>{{ $element->position_x }}, {{ $element->position_y }}</td>
                                                <td>{{ $element->width }}x{{ $element->height }}</td>
                                                <td>{{ $element->layer_order }}</td>
                                                <td>
                                                    @if($element->is_visible)
                                                        <i class="fas fa-eye text-success" title="Visible"></i>
                                                    @else
                                                        <i class="fas fa-eye-slash text-muted" title="Oculto"></i>
                                                    @endif
                                                    @if($element->is_locked)
                                                        <i class="fas fa-lock text-warning ml-1" title="Bloqueado"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay elementos</h5>
                                <p class="text-muted">Edita el proyecto para agregar elementos de diseño</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Project Info Sidebar -->
            <div class="col-lg-4">
                <!-- Project Details -->
                <div class="info-card card">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-info-circle text-primary mr-2"></i>Detalles del Proyecto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Estado:</strong>
                            <span class="status-badge bg-{{ $designProject->status_color }} ml-2">
                                {{ $designProject->status_label }}
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Prioridad:</strong>
                            <span class="badge badge-{{ $designProject->priority_color }} ml-2">
                                {{ $designProject->priority_label }}
                            </span>
                        </div>

                        @if($designProject->deadline)
                            <div class="mb-3">
                                <strong>Fecha Límite:</strong>
                                <br>
                                <span class="text-{{ $designProject->deadline->isPast() ? 'danger' : 'muted' }}">
                                    {{ $designProject->deadline->format('d/m/Y') }}
                                    @if($designProject->deadline->isPast())
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                    @endif
                                </span>
                            </div>
                        @endif

                        <div class="mb-3">
                            <strong>Presupuesto:</strong>
                            <br>
                            <span class="h5 text-success">${{ number_format($designProject->budget, 2) }}</span>
                        </div>

                        @if($designProject->template)
                            <div class="mb-3">
                                <strong>Plantilla:</strong>
                                <br>
                                <span class="text-muted">{{ $designProject->template->name }}</span>
                            </div>
                        @endif

                        @if($designProject->createdBy)
                            <div class="mb-3">
                                <strong>Creado por:</strong>
                                <br>
                                <span class="text-muted">{{ $designProject->createdBy->name }}</span>
                            </div>
                        @endif

                        @if($designProject->description)
                            <div class="mb-3">
                                <strong>Descripción:</strong>
                                <p class="text-muted mt-1">{{ $designProject->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="info-card card">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-tools text-primary mr-2"></i>Acciones
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($designProject->isEditable())
                            <a href="{{ route('design-projects.edit', $designProject) }}" 
                               class="btn btn-warning btn-modern w-100 mb-2">
                                <i class="fas fa-edit mr-2"></i>Editar Proyecto
                            </a>
                        @endif

                        <button class="btn btn-info btn-modern w-100 mb-2" onclick="generatePreview()">
                            <i class="fas fa-magic mr-2"></i>Generar Vista Previa
                        </button>

                        <button class="btn btn-success btn-modern w-100 mb-2" onclick="exportDesign()">
                            <i class="fas fa-download mr-2"></i>Exportar Diseño
                        </button>

                        @if($designProject->status !== 'completed')
                            <button class="btn btn-outline-success btn-modern w-100 mb-2" onclick="markAsCompleted()">
                                <i class="fas fa-check mr-2"></i>Marcar como Completado
                            </button>
                        @endif

                        <a href="{{ route('design-projects.index') }}" class="btn btn-outline-secondary btn-modern w-100">
                            <i class="fas fa-arrow-left mr-2"></i>Volver a Proyectos
                        </a>
                    </div>
                </div>

                <!-- Project Timeline -->
                <div class="info-card card">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-history text-primary mr-2"></i>Cronología
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-item">
                            <strong>Proyecto Creado</strong>
                            <br>
                            <small class="text-muted">{{ $designProject->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        
                        @if($designProject->preview_generated_at)
                            <div class="timeline-item">
                                <strong>Vista Previa Generada</strong>
                                <br>
                                <small class="text-muted">{{ $designProject->preview_generated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        @endif

                        @if($designProject->completed_at)
                            <div class="timeline-item">
                                <strong>Proyecto Completado</strong>
                                <br>
                                <small class="text-muted">{{ $designProject->completed_at->format('d/m/Y H:i') }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
<script>
    function generatePreview() {
        $.ajax({
            url: `{{ route('design-projects.generate-preview', $designProject) }}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                toastr.info('Generando vista previa...');
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function(xhr) {
                toastr.error('Error al generar vista previa');
            }
        });
    }

    function exportDesign() {
        window.open(`{{ route('design-projects.export', $designProject) }}?format=pdf`, '_blank');
    }

    function markAsCompleted() {
        if(confirm('¿Marcar este proyecto como completado?')) {
            $.ajax({
                url: `{{ route('design-projects.update', $designProject) }}`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 'completed',
                    project_name: '{{ $designProject->project_name }}',
                    customer_id: '{{ $designProject->customer_id }}',
                    template_id: '{{ $designProject->template_id }}',
                    dimensions: '{{ $designProject->dimensions }}',
                    color_scheme: '{{ $designProject->color_scheme }}',
                    priority: '{{ $designProject->priority }}'
                },
                success: function(response) {
                    toastr.success('Proyecto marcado como completado');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    toastr.error('Error al actualizar el proyecto');
                }
            });
        }
    }
</script>
@endsection
