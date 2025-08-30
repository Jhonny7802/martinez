@extends('layouts.app')

@section('title')
    Editar Proyecto: {{ $designProject->project_name }}
@endsection

@section('page_css')
    <style>
        .form-modern {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .form-section {
            border-left: 4px solid #007bff;
            padding-left: 1.5rem;
            margin-bottom: 2rem;
        }
        .template-card {
            border: 2px solid transparent;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .template-card:hover {
            border-color: #007bff;
            transform: scale(1.02);
        }
        .template-card.selected {
            border-color: #007bff;
            background: #f8f9ff;
        }
        .element-builder {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
        }
        .btn-modern {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .status-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }
        .status-option {
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .status-option:hover {
            border-color: #007bff;
        }
        .status-option.selected {
            border-color: #007bff;
            background: #f8f9ff;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-edit text-warning mr-2"></i>
                    Editar Proyecto de Diseño
                </h2>
                <p class="text-muted mb-0">{{ $designProject->project_name }}</p>
            </div>
            <a href="{{ route('design-projects.show', $designProject) }}" class="btn btn-outline-secondary btn-modern">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <form action="{{ route('design-projects.update', $designProject) }}" method="POST" id="editDesignForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-modern">
                        <!-- Información Básica -->
                        <div class="form-section">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-info-circle mr-2"></i>Información Básica
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Nombre del Proyecto *</label>
                                        <input type="text" name="project_name" class="form-control @error('project_name') is-invalid @enderror" 
                                               value="{{ old('project_name', $designProject->project_name) }}" required>
                                        @error('project_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Cliente *</label>
                                        <select name="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                                            <option value="">Seleccionar cliente</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" 
                                                    {{ old('customer_id', $designProject->customer_id) == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Descripción</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="Describe el proyecto de diseño...">{{ old('description', $designProject->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Estado del Proyecto -->
                        <div class="form-section">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-tasks mr-2"></i>Estado del Proyecto
                            </h4>
                            <div class="status-selector">
                                <div class="status-option {{ $designProject->status == 'draft' ? 'selected' : '' }}" 
                                     onclick="selectStatus('draft')">
                                    <i class="fas fa-file-alt fa-2x text-secondary mb-2"></i>
                                    <br>
                                    <strong>Borrador</strong>
                                </div>
                                <div class="status-option {{ $designProject->status == 'in_progress' ? 'selected' : '' }}" 
                                     onclick="selectStatus('in_progress')">
                                    <i class="fas fa-cog fa-2x text-primary mb-2"></i>
                                    <br>
                                    <strong>En Progreso</strong>
                                </div>
                                <div class="status-option {{ $designProject->status == 'review' ? 'selected' : '' }}" 
                                     onclick="selectStatus('review')">
                                    <i class="fas fa-search fa-2x text-warning mb-2"></i>
                                    <br>
                                    <strong>En Revisión</strong>
                                </div>
                                <div class="status-option {{ $designProject->status == 'completed' ? 'selected' : '' }}" 
                                     onclick="selectStatus('completed')">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <br>
                                    <strong>Completado</strong>
                                </div>
                                <div class="status-option {{ $designProject->status == 'cancelled' ? 'selected' : '' }}" 
                                     onclick="selectStatus('cancelled')">
                                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                    <br>
                                    <strong>Cancelado</strong>
                                </div>
                            </div>
                            <input type="hidden" name="status" id="statusInput" value="{{ $designProject->status }}">
                        </div>

                        <!-- Configuración de Diseño -->
                        <div class="form-section">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-cogs mr-2"></i>Configuración de Diseño
                            </h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Dimensiones *</label>
                                        <select name="dimensions" class="form-control @error('dimensions') is-invalid @enderror" required>
                                            <option value="1920x1080" {{ old('dimensions', $designProject->dimensions) == '1920x1080' ? 'selected' : '' }}>1920x1080 (HD)</option>
                                            <option value="1200x630" {{ old('dimensions', $designProject->dimensions) == '1200x630' ? 'selected' : '' }}>1200x630 (Social Media)</option>
                                            <option value="210x297" {{ old('dimensions', $designProject->dimensions) == '210x297' ? 'selected' : '' }}>210x297 (A4)</option>
                                            <option value="420x594" {{ old('dimensions', $designProject->dimensions) == '420x594' ? 'selected' : '' }}>420x594 (A2)</option>
                                            <option value="90x50" {{ old('dimensions', $designProject->dimensions) == '90x50' ? 'selected' : '' }}>90x50 (Tarjeta)</option>
                                        </select>
                                        @error('dimensions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Prioridad *</label>
                                        <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                            <option value="low" {{ old('priority', $designProject->priority) == 'low' ? 'selected' : '' }}>Baja</option>
                                            <option value="medium" {{ old('priority', $designProject->priority) == 'medium' ? 'selected' : '' }}>Media</option>
                                            <option value="high" {{ old('priority', $designProject->priority) == 'high' ? 'selected' : '' }}>Alta</option>
                                            <option value="urgent" {{ old('priority', $designProject->priority) == 'urgent' ? 'selected' : '' }}>Urgente</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Plantilla</label>
                                        <select name="template_id" class="form-control @error('template_id') is-invalid @enderror">
                                            <option value="">Sin plantilla</option>
                                            @foreach($templates as $template)
                                                <option value="{{ $template->id }}" 
                                                    {{ old('template_id', $designProject->template_id) == $template->id ? 'selected' : '' }}>
                                                    {{ $template->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('template_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-modern">
                        <!-- Configuración del Proyecto -->
                        <h4 class="text-primary mb-3">
                            <i class="fas fa-calendar-alt mr-2"></i>Configuración del Proyecto
                        </h4>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">Fecha Límite</label>
                            <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror" 
                                   value="{{ old('deadline', $designProject->deadline ? $designProject->deadline->format('Y-m-d') : '') }}" 
                                   min="{{ date('Y-m-d') }}">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Presupuesto</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" name="budget" class="form-control @error('budget') is-invalid @enderror" 
                                       value="{{ old('budget', $designProject->budget) }}" min="0" step="0.01">
                            </div>
                            @error('budget')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Esquema de Colores</label>
                            @php
                                $colors = explode(',', $designProject->color_scheme);
                                $primaryColor = $colors[0] ?? '#007bff';
                                $secondaryColor = $colors[1] ?? '#6c757d';
                            @endphp
                            <div class="d-flex align-items-center">
                                <input type="color" name="primary_color" class="form-control mr-2" 
                                       style="width: 60px; height: 40px;" value="{{ $primaryColor }}">
                                <input type="color" name="secondary_color" class="form-control" 
                                       style="width: 60px; height: 40px;" value="{{ $secondaryColor }}">
                                <input type="hidden" name="color_scheme" id="colorScheme" value="{{ $designProject->color_scheme }}">
                            </div>
                        </div>

                        <!-- Elementos Actuales -->
                        <div class="mt-4">
                            <h5 class="font-weight-bold mb-3">Elementos Actuales</h5>
                            @if($designProject->elements->count() > 0)
                                <div class="list-group">
                                    @foreach($designProject->elements as $element)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge badge-info mr-2">{{ $element->element_type_label }}</span>
                                                <small>{{ Str::limit($element->content, 20) }}</small>
                                            </div>
                                            <div>
                                                @if($element->is_visible)
                                                    <i class="fas fa-eye text-success" title="Visible"></i>
                                                @else
                                                    <i class="fas fa-eye-slash text-muted" title="Oculto"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted text-center py-3">
                                    <i class="fas fa-puzzle-piece fa-2x mb-2"></i>
                                    <br>No hay elementos
                                </p>
                            @endif
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-modern w-100 mb-2">
                                <i class="fas fa-save mr-2"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('design-projects.show', $designProject) }}" class="btn btn-outline-secondary btn-modern w-100">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('page_js')
<script>
    function selectStatus(status) {
        // Remove previous selection
        $('.status-option').removeClass('selected');
        
        // Add selection to clicked status
        event.target.closest('.status-option').classList.add('selected');
        
        // Set status value
        document.getElementById('statusInput').value = status;
    }

    // Update color scheme when colors change
    document.addEventListener('DOMContentLoaded', function() {
        const primaryColor = document.querySelector('input[name="primary_color"]');
        const secondaryColor = document.querySelector('input[name="secondary_color"]');
        const colorScheme = document.getElementById('colorScheme');

        function updateColorScheme() {
            colorScheme.value = primaryColor.value + ',' + secondaryColor.value;
        }

        primaryColor.addEventListener('change', updateColorScheme);
        secondaryColor.addEventListener('change', updateColorScheme);
    });
</script>
@endsection
