<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje Interno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .message-info {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .priority-high {
            border-left-color: #dc3545;
        }
        .priority-urgent {
            border-left-color: #fd7e14;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .meta-info {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-envelope"></i> Sistema de Construcción Martinez</h1>
        <p>Nuevo Mensaje Interno</p>
    </div>

    <div class="content">
        <h2>Hola {{ $recipient->name }},</h2>
        
        <p>Has recibido un nuevo mensaje interno en el sistema de gestión de construcción.</p>

        <div class="message-info {{ $message->priority === 'high' ? 'priority-high' : ($message->priority === 'urgent' ? 'priority-urgent' : '') }}">
            <div class="meta-info">
                <strong>De:</strong> {{ $message->sender->name }}<br>
                <strong>Fecha:</strong> {{ $message->created_at->format('d/m/Y H:i') }}<br>
                <strong>Prioridad:</strong> 
                <span style="color: {{ $message->priority === 'urgent' ? '#fd7e14' : ($message->priority === 'high' ? '#dc3545' : '#28a745') }}">
                    {{ ucfirst($message->priority_label) }}
                </span>
            </div>

            <h3>{{ $message->subject }}</h3>
            
            <div style="margin: 15px 0; padding: 15px; background-color: #f8f9fa; border-radius: 3px;">
                {!! nl2br(e($message->message)) !!}
            </div>

            @if($message->attachments)
                <div style="margin-top: 15px;">
                    <strong>Archivos adjuntos:</strong>
                    <ul>
                        @foreach(json_decode($message->attachments, true) as $attachment)
                            <li>{{ $attachment['name'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('internal-messages.show', $message->id) }}" class="btn">
                Ver Mensaje Completo
            </a>
        </div>

        <div style="background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-top: 20px;">
            <h4 style="margin-top: 0;">Acciones Rápidas:</h4>
            <ul style="margin-bottom: 0;">
                <li><a href="{{ route('internal-messages.index') }}">Ver todos los mensajes</a></li>
                <li><a href="{{ route('internal-messages.create') }}">Enviar nuevo mensaje</a></li>
                <li><a href="{{ route('dashboard') }}">Ir al Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Este es un mensaje automático del Sistema de Gestión de Construcción Martinez.</p>
        <p>No responda a este correo. Para comunicarse, use el sistema interno de mensajes.</p>
        <p>&copy; {{ date('Y') }} Martinez Construction Management System</p>
    </div>
</body>
</html>
