<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $designProject->project_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .project-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .design-preview {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
        }
        .elements-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .elements-table th,
        .elements-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        .elements-table th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $designProject->project_name }}</h1>
        <p>Proyecto de Diseño - Martinez Construction</p>
    </div>

    <!-- Project Information -->
    <div class="project-info">
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <div>
                <strong>Cliente:</strong> {{ $designProject->customer->company_name ?? 'Sin cliente' }}
            </div>
            <div>
                <strong>Fecha de Creación:</strong> {{ $designProject->created_at->format('d/m/Y') }}
            </div>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <div>
                <strong>Estado:</strong> 
                <span class="status-badge" style="background: 
                    @if($designProject->status == 'completed') #28a745
                    @elseif($designProject->status == 'in_progress') #007bff
                    @elseif($designProject->status == 'review') #ffc107; color: #000
                    @elseif($designProject->status == 'cancelled') #dc3545
                    @else #6c757d @endif">
                    {{ $designProject->status_label }}
                </span>
            </div>
            <div>
                <strong>Prioridad:</strong> 
                <span class="status-badge" style="background: 
                    @if($designProject->priority == 'urgent') #dc3545
                    @elseif($designProject->priority == 'high') #ffc107; color: #000
                    @elseif($designProject->priority == 'medium') #17a2b8
                    @else #28a745 @endif">
                    {{ $designProject->priority_label }}
                </span>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between;">
            <div>
                <strong>Dimensiones:</strong> {{ $designProject->dimensions }}
            </div>
            <div>
                <strong>Presupuesto:</strong> ${{ number_format($designProject->budget, 2) }}
            </div>
        </div>

        @if($designProject->description)
            <div style="margin-top: 15px;">
                <strong>Descripción:</strong>
                <p>{{ $designProject->description }}</p>
            </div>
        @endif
    </div>

    <!-- Design Preview -->
    <div class="design-preview">
        @if($designProject->preview_image)
            <img src="{{ public_path('storage/' . $designProject->preview_image) }}" 
                 style="max-width: 100%; max-height: 400px;" alt="Vista previa del diseño">
        @else
            <div style="padding: 60px; color: #6c757d;">
                <h3>Vista Previa No Disponible</h3>
                <p>El diseño aún no ha sido generado</p>
            </div>
        @endif
    </div>

    <!-- Elements Details -->
    @if($designProject->elements->count() > 0)
        <h3 style="margin-top: 40px; color: #007bff;">Elementos del Diseño</h3>
        <table class="elements-table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Contenido</th>
                    <th>Posición (X, Y)</th>
                    <th>Tamaño (W x H)</th>
                    <th>Capa</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($designProject->elements->sortBy('layer_order') as $element)
                    <tr>
                        <td>{{ $element->element_type_label }}</td>
                        <td>{{ Str::limit($element->content, 40) }}</td>
                        <td>{{ $element->position_x }}, {{ $element->position_y }}</td>
                        <td>{{ $element->width }} x {{ $element->height }}</td>
                        <td>{{ $element->layer_order }}</td>
                        <td>
                            {{ $element->is_visible ? 'Visible' : 'Oculto' }}
                            {{ $element->is_locked ? ' | Bloqueado' : '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Template Information -->
    @if($designProject->template)
        <h3 style="margin-top: 40px; color: #007bff;">Información de la Plantilla</h3>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
            <p><strong>Nombre:</strong> {{ $designProject->template->name }}</p>
            <p><strong>Categoría:</strong> {{ $designProject->template->category }}</p>
            @if($designProject->template->description)
                <p><strong>Descripción:</strong> {{ $designProject->template->description }}</p>
            @endif
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Generado el {{ now()->format('d/m/Y H:i') }} | Martinez Construction - Sistema de Gestión</p>
        <p>Proyecto ID: {{ $designProject->id }} | Versión: 1.0</p>
    </div>
</body>
</html>
