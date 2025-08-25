@extends('layouts.app')

@section('title')
    Nuevo Mensaje Interno
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit"></i> Nuevo Mensaje Interno
            </h1>
            <p class="mb-0 text-muted">Enviar mensaje con notificación automática por email</p>
        </div>
        <div>
            <a href="{{ route('enhanced-messages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <form id="messageForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Detalles del Mensaje</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="recipients">Destinatarios <span class="text-danger">*</span></label>
                            <select class="form-control" id="recipients" name="recipients[]" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Seleccione uno o más destinatarios. Mantenga Ctrl presionado para selección múltiple.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="subject">Asunto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject" name="subject" required
                                   placeholder="Ingrese el asunto del mensaje">
                        </div>

                        <div class="form-group">
                            <label for="priority">Prioridad <span class="text-danger">*</span></label>
                            <select class="form-control" id="priority" name="priority" required>
                                <option value="low">🟢 Baja - Información general</option>
                                <option value="medium" selected>🟡 Media - Asunto normal</option>
                                <option value="high">🟠 Alta - Requiere atención</option>
                                <option value="urgent">🔴 Urgente - Acción inmediata</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Mensaje <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="8" required
                                      placeholder="Escriba su mensaje aquí..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="attachments">Archivos Adjuntos</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="attachments" name="attachments[]" multiple>
                                <label class="custom-file-label" for="attachments">Seleccionar archivos...</label>
                            </div>
                            <small class="form-text text-muted">
                                Máximo 10MB por archivo. Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP
                            </small>
                            <div id="file-list" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Acciones</h6>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-paper-plane"></i> Enviar Mensaje
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-block" id="saveDraftBtn">
                            <i class="fas fa-save"></i> Guardar Borrador
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-block" onclick="window.history.back()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>

                <!-- Quick Recipients -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">Selección Rápida</h6>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-sm btn-outline-info btn-block" id="selectAllUsers">
                            <i class="fas fa-users"></i> Todos los Usuarios
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info btn-block" id="selectManagers">
                            <i class="fas fa-user-tie"></i> Solo Gerentes
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info btn-block" id="selectSupervisors">
                            <i class="fas fa-hard-hat"></i> Solo Supervisores
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-block" id="clearSelection">
                            <i class="fas fa-times"></i> Limpiar Selección
                        </button>
                    </div>
                </div>

                <!-- Message Templates -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Plantillas Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <select class="form-control" id="messageTemplates">
                            <option value="">Seleccionar plantilla...</option>
                            <option value="meeting">Convocatoria a Reunión</option>
                            <option value="delay">Notificación de Retraso</option>
                            <option value="completion">Finalización de Tarea</option>
                            <option value="urgent">Asunto Urgente</option>
                            <option value="maintenance">Mantenimiento Programado</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-success btn-block mt-2" id="applyTemplate">
                            <i class="fas fa-magic"></i> Aplicar Plantilla
                        </button>
                    </div>
                </div>

                <!-- Preview -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">Vista Previa</h6>
                    </div>
                    <div class="card-body">
                        <div id="message-preview">
                            <p class="text-muted">La vista previa aparecerá aquí mientras escribe...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for recipients
    $('#recipients').select2({
        placeholder: 'Seleccionar destinatarios...',
        allowClear: true
    });

    // File input handling
    $('#attachments').change(function() {
        let files = this.files;
        let fileList = $('#file-list');
        fileList.empty();
        
        if (files.length > 0) {
            let list = '<ul class="list-unstyled">';
            for (let i = 0; i < files.length; i++) {
                let size = (files[i].size / 1024 / 1024).toFixed(2);
                list += `<li><i class="fas fa-paperclip"></i> ${files[i].name} (${size} MB)</li>`;
            }
            list += '</ul>';
            fileList.html(list);
        }
        
        // Update label
        let label = files.length > 1 ? `${files.length} archivos seleccionados` : 
                   files.length === 1 ? files[0].name : 'Seleccionar archivos...';
        $('.custom-file-label').text(label);
    });

    // Quick recipient selection
    $('#selectAllUsers').click(function() {
        $('#recipients option').prop('selected', true);
        $('#recipients').trigger('change');
    });

    $('#selectManagers').click(function() {
        $('#recipients option').prop('selected', false);
        $('#recipients option').each(function() {
            if ($(this).text().toLowerCase().includes('gerente') || 
                $(this).text().toLowerCase().includes('manager')) {
                $(this).prop('selected', true);
            }
        });
        $('#recipients').trigger('change');
    });

    $('#selectSupervisors').click(function() {
        $('#recipients option').prop('selected', false);
        $('#recipients option').each(function() {
            if ($(this).text().toLowerCase().includes('supervisor')) {
                $(this).prop('selected', true);
            }
        });
        $('#recipients').trigger('change');
    });

    $('#clearSelection').click(function() {
        $('#recipients').val(null).trigger('change');
    });

    // Message templates
    const templates = {
        meeting: {
            subject: 'Convocatoria a Reunión - [Fecha]',
            message: 'Estimados compañeros,\n\nPor medio de la presente, los convoco a una reunión que se llevará a cabo:\n\nFecha: [Fecha]\nHora: [Hora]\nLugar: [Lugar]\nTema: [Tema]\n\nAgradezco confirmar su asistencia.\n\nSaludos cordiales.',
            priority: 'medium'
        },
        delay: {
            subject: 'Notificación de Retraso en Proyecto',
            message: 'Estimado equipo,\n\nLes informo que el proyecto [Nombre del Proyecto] presenta un retraso debido a [Motivo del retraso].\n\nNueva fecha estimada: [Nueva fecha]\n\nEstaremos tomando las medidas necesarias para minimizar el impacto.\n\nGracias por su comprensión.',
            priority: 'high'
        },
        completion: {
            subject: 'Tarea Completada - [Nombre de la Tarea]',
            message: 'Estimados,\n\nLes informo que la tarea "[Nombre de la Tarea]" ha sido completada exitosamente.\n\nDetalles:\n- Fecha de finalización: [Fecha]\n- Resultados: [Resultados]\n- Próximos pasos: [Próximos pasos]\n\nQuedo atento a cualquier consulta.',
            priority: 'low'
        },
        urgent: {
            subject: 'URGENTE - Requiere Atención Inmediata',
            message: 'ATENCIÓN URGENTE\n\nSe requiere atención inmediata para el siguiente asunto:\n\n[Descripción del problema]\n\nAcciones requeridas:\n1. [Acción 1]\n2. [Acción 2]\n3. [Acción 3]\n\nPor favor, confirmar recepción y acciones tomadas a la brevedad.',
            priority: 'urgent'
        },
        maintenance: {
            subject: 'Mantenimiento Programado - [Sistema/Equipo]',
            message: 'Estimados usuarios,\n\nLes informamos que se realizará mantenimiento programado:\n\nSistema/Equipo: [Nombre]\nFecha: [Fecha]\nHora de inicio: [Hora inicio]\nDuración estimada: [Duración]\n\nDurante este período, el servicio no estará disponible.\n\nGracias por su comprensión.',
            priority: 'medium'
        }
    };

    $('#applyTemplate').click(function() {
        let templateKey = $('#messageTemplates').val();
        if (templateKey && templates[templateKey]) {
            let template = templates[templateKey];
            $('#subject').val(template.subject);
            $('#message').val(template.message);
            $('#priority').val(template.priority);
            updatePreview();
        }
    });

    // Live preview
    function updatePreview() {
        let subject = $('#subject').val() || '[Sin asunto]';
        let message = $('#message').val() || '[Sin mensaje]';
        let priority = $('#priority option:selected').text();
        
        let preview = `
            <div class="border-left-primary p-3" style="border-left: 4px solid #007bff;">
                <h6 class="font-weight-bold">${subject}</h6>
                <small class="text-muted">Prioridad: ${priority}</small>
                <div class="mt-2" style="white-space: pre-wrap;">${message}</div>
            </div>
        `;
        
        $('#message-preview').html(preview);
    }

    $('#subject, #message, #priority').on('input change', updatePreview);

    // Form submission
    $('#messageForm').submit(function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        // Show loading
        let submitBtn = $(this).find('button[type="submit"]');
        let originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Enviando...').prop('disabled', true);
        
        $.ajax({
            url: "{{ route('enhanced-messages.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    setTimeout(function() {
                        window.location.href = "{{ route('enhanced-messages.index') }}";
                    }, 1500);
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = 'Error al enviar el mensaje';
                if (errors) {
                    errorMsg = Object.values(errors).flat().join('<br>');
                }
                showAlert('error', errorMsg);
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Initialize preview
    updatePreview();
});

function showAlert(type, message) {
    let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    let alert = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>`;
    $('.container-fluid').prepend(alert);
}
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
