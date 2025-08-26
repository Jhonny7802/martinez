@extends('layouts.app')

@section('title')
    {{ __('Mensajes Enviados') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div>
                <h3 class="page-title text-white">{{ __('Mensajes Enviados') }}</h3>
            </div>
            <div>
                <a href="{{ route('enhanced-messages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Nuevo Mensaje') }}
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="priority_filter">{{ __('Filtrar por Prioridad') }}</label>
                                    <select class="form-control" id="priority_filter">
                                        <option value="">{{ __('Todas las prioridades') }}</option>
                                        <option value="low">{{ __('Baja') }}</option>
                                        <option value="medium">{{ __('Media') }}</option>
                                        <option value="high">{{ __('Alta') }}</option>
                                        <option value="urgent">{{ __('Urgente') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_filter">{{ __('Filtrar por Fecha') }}</label>
                                    <input type="date" class="form-control" id="date_filter">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="search_filter">{{ __('Buscar') }}</label>
                                    <input type="text" class="form-control" id="search_filter" placeholder="{{ __('Buscar por asunto o destinatario...') }}">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="sentMessagesTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Destinatario(s)') }}</th>
                                        <th>{{ __('Asunto') }}</th>
                                        <th>{{ __('Prioridad') }}</th>
                                        <th>{{ __('Fecha Enviado') }}</th>
                                        <th>{{ __('Estado') }}</th>
                                        <th>{{ __('Acciones') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($messages as $message)
                                        <tr>
                                            <td>
                                                @if($message->recipients && is_array($message->recipients))
                                                    @foreach($message->recipients as $recipientId)
                                                        @php
                                                            $recipient = \App\Models\User::find($recipientId);
                                                        @endphp
                                                        @if($recipient)
                                                            <span class="badge badge-info mr-1">{{ $recipient->name }}</span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">{{ __('Sin destinatarios') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $message->subject }}</strong>
                                                @if($message->has_attachments)
                                                    <i class="fas fa-paperclip text-muted ml-1"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($message->priority)
                                                    @case('urgent')
                                                        <span class="badge badge-danger">{{ __('Urgente') }}</span>
                                                        @break
                                                    @case('high')
                                                        <span class="badge badge-warning">{{ __('Alta') }}</span>
                                                        @break
                                                    @case('medium')
                                                        <span class="badge badge-info">{{ __('Media') }}</span>
                                                        @break
                                                    @case('low')
                                                        <span class="badge badge-secondary">{{ __('Baja') }}</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ __('Normal') }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($message->is_broadcast)
                                                    <span class="badge badge-primary">{{ __('Difusión') }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ __('Enviado') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('enhanced-messages.show', $message->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="{{ __('Ver mensaje') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(!$message->is_broadcast)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-info" 
                                                                onclick="forwardMessage({{ $message->id }})"
                                                                title="{{ __('Reenviar') }}">
                                                            <i class="fas fa-share"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteMessage({{ $message->id }})"
                                                            title="{{ __('Eliminar') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>{{ __('No has enviado mensajes aún') }}</p>
                                                <a href="{{ route('enhanced-messages.create') }}" class="btn btn-primary">
                                                    {{ __('Enviar primer mensaje') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($messages->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $messages->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirmar Eliminación') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ __('¿Estás seguro de que deseas eliminar este mensaje? Esta acción no se puede deshacer.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">{{ __('Eliminar') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#sentMessagesTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
            },
            order: [[3, 'desc']], // Ordenar por fecha descendente
            columnDefs: [
                { orderable: false, targets: [5] } // Deshabilitar ordenamiento en columna de acciones
            ]
        });

        // Filtros
        $('#priority_filter').on('change', function() {
            var priority = $(this).val();
            $('#sentMessagesTable').DataTable().column(2).search(priority).draw();
        });

        $('#search_filter').on('keyup', function() {
            $('#sentMessagesTable').DataTable().search($(this).val()).draw();
        });

        $('#date_filter').on('change', function() {
            var date = $(this).val();
            $('#sentMessagesTable').DataTable().column(3).search(date).draw();
        });
    });

    let messageToDelete = null;

    function deleteMessage(messageId) {
        messageToDelete = messageId;
        $('#deleteModal').modal('show');
    }

    $('#confirmDelete').on('click', function() {
        if (messageToDelete) {
            $.ajax({
                url: `/enhanced-messages/${messageToDelete}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("Error al eliminar el mensaje") }}');
                }
            });
        }
    });

    function forwardMessage(messageId) {
        window.location.href = `/enhanced-messages/create?forward=${messageId}`;
    }
</script>
@endsection
