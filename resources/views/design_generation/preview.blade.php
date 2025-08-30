@extends('layouts.app')

@section('title')
    Vista Previa: {{ $designProject->project_name }}
@endsection

@section('page_css')
    <style>
        .preview-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .design-canvas {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            position: relative;
            margin: 0 auto;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        .design-element {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed #007bff;
            background: rgba(0,123,255,0.05);
            font-size: 12px;
            color: #007bff;
            font-weight: bold;
        }
        .toolbar {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .btn-modern {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .zoom-controls {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: white;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-eye text-info mr-2"></i>
                    Vista Previa del Diseño
                </h2>
                <p class="text-muted mb-0">{{ $designProject->project_name }}</p>
            </div>
            <a href="{{ route('design-projects.show', $designProject) }}" class="btn btn-outline-secondary btn-modern">
                <i class="fas fa-arrow-left mr-2"></i>Volver al Proyecto
            </a>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="font-weight-bold mr-3">Dimensiones:</span>
                        <span class="badge badge-primary">{{ $designProject->dimensions }}</span>
                        <span class="font-weight-bold ml-4 mr-3">Estado:</span>
                        <span class="badge badge-{{ $designProject->status_color }}">{{ $designProject->status_label }}</span>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-outline-primary btn-sm btn-modern mr-2" onclick="toggleGrid()">
                        <i class="fas fa-th mr-1"></i>Cuadrícula
                    </button>
                    <button class="btn btn-outline-success btn-sm btn-modern mr-2" onclick="exportDesign()">
                        <i class="fas fa-download mr-1"></i>Exportar
                    </button>
                    <button class="btn btn-primary btn-sm btn-modern" onclick="regeneratePreview()">
                        <i class="fas fa-sync mr-1"></i>Regenerar
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Container -->
        <div class="preview-container">
            <div class="position-relative">
                <!-- Zoom Controls -->
                <div class="zoom-controls">
                    <button class="btn btn-sm btn-outline-secondary" onclick="zoomOut()">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="mx-2" id="zoomLevel">100%</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="zoomIn()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Design Canvas -->
                @php
                    $dimensions = explode('x', $designProject->dimensions);
                    $width = $dimensions[0] ?? 1920;
                    $height = $dimensions[1] ?? 1080;
                    $scale = min(800 / $width, 600 / $height, 1);
                    $canvasWidth = $width * $scale;
                    $canvasHeight = $height * $scale;
                @endphp

                <div class="design-canvas" id="designCanvas" 
                     style="width: {{ $canvasWidth }}px; height: {{ $canvasHeight }}px;">
                    
                    @if($designProject->preview_image)
                        <img src="{{ asset('storage/' . $designProject->preview_image) }}" 
                             class="img-fluid w-100 h-100" style="object-fit: contain;" alt="Vista previa">
                    @else
                        <!-- Background -->
                        @php
                            $colors = explode(',', $designProject->color_scheme);
                            $primaryColor = $colors[0] ?? '#ffffff';
                            $secondaryColor = $colors[1] ?? '#f8f9fa';
                        @endphp
                        <div style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%); 
                                    width: 100%; height: 100%; position: absolute;"></div>

                        <!-- Design Elements -->
                        @foreach($designProject->elements->where('is_visible', true) as $element)
                            <div class="design-element" 
                                 style="left: {{ $element->position_x * $scale }}px; 
                                        top: {{ $element->position_y * $scale }}px; 
                                        width: {{ $element->width * $scale }}px; 
                                        height: {{ $element->height * $scale }}px;
                                        z-index: {{ $element->layer_order }};">
                                @if($element->element_type === 'text')
                                    {{ $element->content }}
                                @elseif($element->element_type === 'image')
                                    <i class="fas fa-image"></i>
                                @elseif($element->element_type === 'shape')
                                    <i class="fas fa-square"></i>
                                @elseif($element->element_type === 'logo')
                                    <i class="fas fa-copyright"></i>
                                @else
                                    <i class="fas fa-star"></i>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <!-- Grid Overlay -->
                    <div id="gridOverlay" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
                                                 background-image: linear-gradient(rgba(0,0,0,.1) 1px, transparent 1px),
                                                                   linear-gradient(90deg, rgba(0,0,0,.1) 1px, transparent 1px);
                                                 background-size: 20px 20px; pointer-events: none;"></div>
                </div>
            </div>

            <!-- Element Details -->
            @if($designProject->elements->count() > 0)
                <div class="mt-4">
                    <h5 class="font-weight-bold mb-3">
                        <i class="fas fa-list text-primary mr-2"></i>Elementos del Diseño
                    </h5>
                    <div class="row">
                        @foreach($designProject->elements as $element)
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge badge-info">{{ $element->element_type_label }}</span>
                                            <div>
                                                @if($element->is_visible)
                                                    <i class="fas fa-eye text-success" title="Visible"></i>
                                                @else
                                                    <i class="fas fa-eye-slash text-muted" title="Oculto"></i>
                                                @endif
                                                @if($element->is_locked)
                                                    <i class="fas fa-lock text-warning ml-1" title="Bloqueado"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="mb-1"><strong>Contenido:</strong> {{ Str::limit($element->content, 25) }}</p>
                                        <p class="mb-1"><strong>Posición:</strong> {{ $element->position_x }}, {{ $element->position_y }}</p>
                                        <p class="mb-0"><strong>Tamaño:</strong> {{ $element->width }}x{{ $element->height }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('page_js')
<script>
    let currentZoom = 1;

    function toggleGrid() {
        const grid = document.getElementById('gridOverlay');
        grid.style.display = grid.style.display === 'none' ? 'block' : 'none';
    }

    function zoomIn() {
        currentZoom = Math.min(currentZoom + 0.1, 2);
        updateZoom();
    }

    function zoomOut() {
        currentZoom = Math.max(currentZoom - 0.1, 0.5);
        updateZoom();
    }

    function updateZoom() {
        const canvas = document.getElementById('designCanvas');
        canvas.style.transform = `scale(${currentZoom})`;
        canvas.style.transformOrigin = 'top left';
        document.getElementById('zoomLevel').textContent = Math.round(currentZoom * 100) + '%';
    }

    function exportDesign() {
        window.open(`{{ route('design-projects.export', $designProject) }}?format=pdf`, '_blank');
    }

    function regeneratePreview() {
        $.ajax({
            url: `{{ route('design-projects.generate-preview', $designProject) }}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                toastr.info('Regenerando vista previa...');
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
                toastr.error('Error al regenerar vista previa');
            }
        });
    }
</script>
@endsection
