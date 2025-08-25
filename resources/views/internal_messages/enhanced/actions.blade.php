<div class="btn-group" role="group">
    <a href="{{ route('enhanced-messages.show', $message->id) }}" 
       class="btn btn-sm btn-info" title="Ver Mensaje">
        <i class="fas fa-eye"></i>
    </a>
    
    <button type="button" class="btn btn-sm btn-success reply-btn" 
            data-id="{{ $message->id }}" title="Responder">
        <i class="fas fa-reply"></i>
    </button>
    
    <button type="button" class="btn btn-sm btn-warning forward-btn" 
            data-id="{{ $message->id }}" title="Reenviar">
        <i class="fas fa-share"></i>
    </button>
    
    @php
        $readBy = json_decode($message->read_by, true) ?? [];
        $isRead = in_array(Auth::id(), $readBy);
    @endphp
    
    @if(!$isRead)
        <button type="button" class="btn btn-sm btn-outline-secondary mark-read-btn" 
                data-id="{{ $message->id }}" title="Marcar como Leído">
            <i class="fas fa-check"></i>
        </button>
    @endif
</div>

<script>
$(document).ready(function() {
    $('.reply-btn').click(function() {
        let messageId = $(this).data('id');
        window.location.href = `/admin/enhanced-messages/${messageId}/reply`;
    });
    
    $('.forward-btn').click(function() {
        let messageId = $(this).data('id');
        window.location.href = `/admin/enhanced-messages/${messageId}/forward`;
    });
    
    $('.mark-read-btn').click(function() {
        let messageId = $(this).data('id');
        let btn = $(this);
        
        $.post(`/admin/enhanced-messages/${messageId}/mark-read`, {
            _token: $('meta[name="csrf-token"]').attr('content')
        }).done(function(response) {
            if (response.success) {
                btn.remove();
                $('#messages-table').DataTable().ajax.reload(null, false);
            }
        });
    });
});
</script>
