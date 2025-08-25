@extends('layouts.app')

@section('title')
    Mensajes Internos
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-envelope"></i> Mensajes Internos
            </h1>
            <p class="mb-0 text-muted">Sistema de comunicación interna con notificaciones por email</p>
        </div>
        <div>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#broadcastModal">
                <i class="fas fa-broadcast-tower"></i> Difundir a Todos
            </button>
            <a href="{{ route('enhanced-messages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Mensaje
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Recibidos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['received'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-inbox fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">No Leídos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['unread'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Enviados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sent'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paper-plane fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Leídos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['read'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="messageTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="inbox-tab" data-toggle="tab" href="#inbox" role="tab">
                        <i class="fas fa-inbox"></i> Bandeja de Entrada
                        @if($stats['unread'] > 0)
                            <span class="badge badge-warning ml-1">{{ $stats['unread'] }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sent-tab" data-toggle="tab" href="#sent" role="tab">
                        <i class="fas fa-paper-plane"></i> Enviados
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="messageTabContent">
                <!-- Inbox Tab -->
                <div class="tab-pane fade show active" id="inbox" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-success" id="markAllReadBtn">
                                <i class="fas fa-check-double"></i> Marcar Todos como Leídos
                            </button>
                        </div>
                        <div>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar mensajes..." id="searchMessages">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover" id="messages-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%"><input type="checkbox" id="selectAll"></th>
                                    <th width="15%">De</th>
                                    <th width="35%">Asunto</th>
                                    <th width="10%">Prioridad</th>
                                    <th width="10%">Estado</th>
                                    <th width="15%">Fecha</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Sent Tab -->
                <div class="tab-pane fade" id="sent" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover" id="sent-messages-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="35%">Asunto</th>
                                    <th width="10%">Destinatarios</th>
                                    <th width="10%">Leídos</th>
                                    <th width="10%">Prioridad</th>
                                    <th width="15%">Fecha</th>
                                    <th width="20%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Broadcast Modal -->
<div class="modal fade" id="broadcastModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-broadcast-tower text-success"></i> Difundir Mensaje a Todos
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="broadcastForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Este mensaje será enviado a todos los usuarios activos del sistema y se notificará por email.
                    </div>
                    
                    <div class="form-group">
                        <label for="broadcast_subject">Asunto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="broadcast_subject" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="broadcast_priority">Prioridad <span class="text-danger">*</span></label>
                        <select class="form-control" id="broadcast_priority" name="priority" required>
                            <option value="low">Baja</option>
                            <option value="medium" selected>Media</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="broadcast_message">Mensaje <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="broadcast_message" name="message" rows="6" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="broadcast_attachments">Archivos Adjuntos</label>
                        <input type="file" class="form-control-file" id="broadcast_attachments" name="attachments[]" multiple>
                        <small class="form-text text-muted">Máximo 10MB por archivo</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-broadcast-tower"></i> Difundir Mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables
    let messagesTable = $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('enhanced-messages.index') }}",
        columns: [
            {data: 'id', name: 'id', orderable: false, searchable: false, render: function(data) {
                return '<input type="checkbox" class="message-checkbox" value="' + data + '">';
            }},
            {data: 'sender_name', name: 'sender.name'},
            {data: 'subject', name: 'subject'},
            {data: 'priority_badge', name: 'priority', orderable: false, searchable: false},
            {data: 'is_read', name: 'is_read', orderable: false, searchable: false},
            {data: 'created_at_formatted', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[5, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        }
    });

    let sentTable = $('#sent-messages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('enhanced-messages.sent') }}",
        columns: [
            {data: 'subject', name: 'subject'},
            {data: 'recipients_count', name: 'recipients_count'},
            {data: 'read_count', name: 'read_count'},
            {data: 'priority_badge', name: 'priority', orderable: false, searchable: false},
            {data: 'created_at_formatted', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[4, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        }
    });

    // Tab switching
    $('#sent-tab').on('click', function() {
        setTimeout(function() {
            sentTable.ajax.reload();
        }, 100);
    });

    // Select all functionality
    $('#selectAll').change(function() {
        $('.message-checkbox').prop('checked', this.checked);
    });

    // Mark all as read
    $('#markAllReadBtn').click(function() {
        $.post("{{ route('enhanced-messages.mark-all-read') }}", {
            _token: $('meta[name="csrf-token"]').attr('content')
        }).done(function(response) {
            if (response.success) {
                showAlert('success', response.message);
                messagesTable.ajax.reload();
                updateUnreadCount();
            }
        });
    });

    // Broadcast form
    $('#broadcastForm').submit(function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('enhanced-messages.broadcast') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#broadcastModal').modal('hide');
                    $('#broadcastForm')[0].reset();
                    showAlert('success', response.message);
                    sentTable.ajax.reload();
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = 'Error al enviar el mensaje';
                if (errors) {
                    errorMsg = Object.values(errors).flat().join('<br>');
                }
                showAlert('error', errorMsg);
            }
        });
    });

    // Update unread count periodically
    function updateUnreadCount() {
        $.get("{{ route('enhanced-messages.unread-count') }}", function(data) {
            if (data.count > 0) {
                $('#inbox-tab .badge').text(data.count).show();
            } else {
                $('#inbox-tab .badge').hide();
            }
        });
    }

    // Update every 30 seconds
    setInterval(updateUnreadCount, 30000);
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
