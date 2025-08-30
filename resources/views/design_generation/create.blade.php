@extends('layouts.app')

@section('title')
    Nuevo Proyecto de Diseño
@endsection

@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-panel.css') }}">
    <style>
        .form-modern {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2rem;
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
        }
        .color-picker {
            width: 50px;
            height: 40px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-plus-circle text-primary mr-2"></i>
                    Nuevo Proyecto de Diseño
                </h2>
                <p class="text-muted mb-0">Crea un proyecto de diseño profesional</p>
            </div>
            <a href="{{ route('design-projects.index') }}" class="btn btn-outline-secondary btn-modern">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <form action="{{ route('design-projects.store') }}" method="POST" id="designForm">
            @csrf
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
                                               value="{{ old('project_name') }}" required>
                                        @error('project_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_id" class="font-weight-bold">Cliente</label>
                                        <select name="customer_id" id="customer_id" class="form-control">
                                            <option value="">Sin cliente asignado</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Descripción</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="Describe el proyecto de diseño...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                            <option value="1920x1080" {{ old('dimensions') == '1920x1080' ? 'selected' : '' }}>1920x1080 (HD)</option>
                                            <option value="1200x630" {{ old('dimensions') == '1200x630' ? 'selected' : '' }}>1200x630 (Social Media)</option>
                                            <option value="210x297" {{ old('dimensions') == '210x297' ? 'selected' : '' }}>210x297 (A4)</option>
                                            <option value="420x594" {{ old('dimensions') == '420x594' ? 'selected' : '' }}>420x594 (A2)</option>
                                            <option value="90x50" {{ old('dimensions') == '90x50' ? 'selected' : '' }}>90x50 (Tarjeta)</option>
                                        </select>
                                        @error('dimensions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Esquema de Colores *</label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" name="primary_color" class="color-picker mr-2" value="#007bff">
                                            <input type="color" name="secondary_color" class="color-picker mr-2" value="#6c757d">
                                            <input type="hidden" name="color_scheme" id="colorScheme" value="#007bff,#6c757d">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Prioridad *</label>
                                        <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plantillas -->
                        <div class="form-section">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-layer-group mr-2"></i>Seleccionar Plantilla
                            </h4>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Generación Automática:</strong> Si no seleccionas una plantilla, se generará automáticamente un diseño de casa con elementos predefinidos.
                            </div>
                            <div class="row" id="templatesContainer">
                                <div class="col-md-4 mb-3">
                                    <div class="template-card card border-success" onclick="selectTemplate(0)">
                                        <div class="card-body text-center">
                                            <i class="fas fa-home fa-3x text-success mb-2"></i>
                                            <h6 class="font-weight-bold">Diseño Automático de Casa</h6>
                                            <small class="text-muted">Generación automática</small>
                                            <br>
                                            <small class="text-success">Recomendado</small>
                                        </div>
                                    </div>
                                </div>
                                @foreach($templates as $template)
                                    <div class="col-md-4 mb-3">
                                        <div class="template-card card" onclick="selectTemplate({{ $template->id }})">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-image fa-3x text-primary mb-2"></i>
                                                <h6 class="font-weight-bold">{{ $template->name }}</h6>
                                                <small class="text-muted">{{ $template->category }}</small>
                                                <br>
                                                <small class="text-info">{{ $template->dimensions }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="template_id" id="templateId">
                            @error('template_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Elementos de Diseño -->
                        <div class="form-section">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-puzzle-piece mr-2"></i>Elementos de Diseño
                            </h4>
                            <div class="alert alert-success">
                                <i class="fas fa-magic mr-2"></i>
                                <strong>Generación Automática:</strong> Los elementos de diseño se crearán automáticamente al guardar el proyecto. Incluirá casa principal, techo, ventanas, puerta, garaje y jardín.
                            </div>
                            <div id="elementsContainer">
                                <p class="text-muted">Los elementos se generarán automáticamente para crear un diseño completo de casa.</p>
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
                                   value="{{ old('deadline') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
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
                                       value="{{ old('budget', 0) }}" min="0" step="0.01">
                            </div>
                            @error('budget')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Vista Previa -->
                        <div class="mt-4">
                            <h5 class="font-weight-bold mb-3">Vista Previa</h5>
                            <div id="designPreview" class="border rounded p-3 bg-light text-center" style="min-height: 200px;">
                                <i class="fas fa-eye fa-3x text-muted mb-2"></i>
                                <p class="text-muted">Selecciona una plantilla para ver la vista previa</p>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-modern w-100 mb-2">
                                <i class="fas fa-save mr-2"></i>Crear Proyecto
                            </button>
                            <a href="{{ route('design-projects.index') }}" class="btn btn-outline-secondary btn-modern w-100">
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
    let elementCount = 1;

    function selectTemplate(templateId) {
        // Remove previous selection
        $('.template-card').removeClass('selected');
        
        // Add selection to clicked template
        event.target.closest('.template-card').classList.add('selected');
        
        // Set template ID
        document.getElementById('templateId').value = templateId;
        
        // Update preview
        updatePreview();
    }

    function addElement() {
        const container = document.getElementById('elementsContainer');
        const elementHtml = `
            <div class="element-builder">
                <div class="row">
                    <div class="col-md-3">
                        <label class="font-weight-bold">Tipo</label>
                        <select name="design_elements[${elementCount}][type]" class="form-control" required>
                            <option value="text">Texto</option>
                            <option value="image">Imagen</option>
                            <option value="shape">Forma</option>
                            <option value="logo">Logo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">Contenido</label>
                        <input type="text" name="design_elements[${elementCount}][content]" class="form-control" placeholder="Texto o URL" required>
                    </div>
                    <div class="col-md-2">
                        <label class="font-weight-bold">X</label>
                        <input type="number" name="design_elements[${elementCount}][position_x]" class="form-control" value="0" required>
                    </div>
                    <div class="col-md-2">
                        <label class="font-weight-bold">Y</label>
                        <input type="number" name="design_elements[${elementCount}][position_y]" class="form-control" value="0" required>
                    </div>
                    <div class="col-md-1">
                        <label class="font-weight-bold">Ancho</label>
                        <input type="number" name="design_elements[${elementCount}][width]" class="form-control" value="100" required>
                    </div>
                    <div class="col-md-1">
                        <label class="font-weight-bold">Alto</label>
                        <input type="number" name="design_elements[${elementCount}][height]" class="form-control" value="50" required>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm mt-2" onclick="removeElement(this)">
                    <i class="fas fa-trash mr-1"></i>Eliminar
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', elementHtml);
        elementCount++;
    }

    function removeElement(button) {
        button.closest('.element-builder').remove();
    }

    function updatePreview() {
        const preview = document.getElementById('designPreview');
        const templateId = document.getElementById('templateId').value;
        
        if (templateId) {
            preview.innerHTML = `
                <div class="bg-primary text-white p-3 rounded">
                    <i class="fas fa-magic fa-2x mb-2"></i>
                    <h6>Plantilla Seleccionada</h6>
                    <p class="mb-0">Vista previa disponible después de crear el proyecto</p>
                </div>
            `;
        }
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
