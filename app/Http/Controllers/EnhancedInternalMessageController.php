<?php

namespace App\Http\Controllers;

use App\Models\InternalMessage;
use App\Models\User;
use App\Services\InternalMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class EnhancedInternalMessageController extends AppBaseController
{
    protected $messageService;

    public function __construct(InternalMessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $messages = InternalMessage::with(['sender'])
                ->whereJsonContains('recipients', Auth::id())
                ->select('internal_messages.*');

            return DataTables::of($messages)
                ->addColumn('sender_name', function ($message) {
                    return $message->sender->name ?? 'Sistema';
                })
                ->addColumn('is_read', function ($message) {
                    $readBy = json_decode($message->read_by, true) ?? [];
                    $isRead = in_array(Auth::id(), $readBy);
                    return $isRead ? '<span class="badge badge-success">Leído</span>' : '<span class="badge badge-warning">No leído</span>';
                })
                ->addColumn('priority_badge', function ($message) {
                    return '<span class="badge bg-' . $message->priority_color . '">' . $message->priority_label . '</span>';
                })
                ->addColumn('created_at_formatted', function ($message) {
                    return $message->created_at->format('d/m/Y H:i');
                })
                ->addColumn('actions', function ($message) {
                    return view('internal_messages.enhanced.actions', compact('message'))->render();
                })
                ->rawColumns(['is_read', 'priority_badge', 'actions'])
                ->make(true);
        }

        $stats = $this->messageService->getMessageStats(Auth::user());
        return view('internal_messages.enhanced.index', compact('stats'));
    }

    public function create()
    {
        $users = User::where('status', 'active')
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();
        
        return view('internal_messages.enhanced.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments.*' => 'file|max:10240' // 10MB max per file
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message_attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }

        $messageData = [
            'recipients' => $request->recipients,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'attachments' => !empty($attachments) ? $attachments : null
        ];

        $message = $this->messageService->sendMessage($messageData, Auth::user());

        activity()->performedOn($message)->causedBy(Auth::user())
            ->useLog('Internal Message Sent')
            ->log('Message sent: ' . $message->subject);

        return $this->sendSuccess('Mensaje enviado exitosamente');
    }

    public function show(InternalMessage $internalMessage)
    {
        // Check if user is recipient
        $recipients = json_decode($internalMessage->recipients, true) ?? [];
        if (!in_array(Auth::id(), $recipients) && $internalMessage->sender_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este mensaje');
        }

        // Mark as read if user is recipient
        if (in_array(Auth::id(), $recipients)) {
            $this->messageService->markAsRead($internalMessage, Auth::user());
        }

        $internalMessage->load(['sender']);
        return view('internal_messages.enhanced.show', compact('internalMessage'));
    }

    public function reply(Request $request, InternalMessage $internalMessage)
    {
        $request->validate([
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments.*' => 'file|max:10240'
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message_attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }

        $replyData = [
            'message' => $request->message,
            'priority' => $request->priority,
            'attachments' => !empty($attachments) ? $attachments : null
        ];

        $reply = $this->messageService->replyToMessage($internalMessage, $replyData, Auth::user());

        return $this->sendSuccess('Respuesta enviada exitosamente');
    }

    public function forward(Request $request, InternalMessage $internalMessage)
    {
        $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
            'message' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments.*' => 'file|max:10240'
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message_attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }

        $forwardData = [
            'recipients' => $request->recipients,
            'message' => $request->message ?? '',
            'priority' => $request->priority,
            'attachments' => !empty($attachments) ? $attachments : null
        ];

        $forwarded = $this->messageService->forwardMessage($internalMessage, $forwardData, Auth::user());

        return $this->sendSuccess('Mensaje reenviado exitosamente');
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments.*' => 'file|max:10240'
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message_attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }

        $broadcastData = [
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'attachments' => !empty($attachments) ? $attachments : null
        ];

        $broadcast = $this->messageService->createBroadcast($broadcastData, Auth::user());

        return $this->sendSuccess('Mensaje difundido a todos los usuarios');
    }

    public function sent(Request $request)
    {
        if ($request->ajax()) {
            $messages = InternalMessage::where('sender_id', Auth::id())
                ->select('internal_messages.*');

            return DataTables::of($messages)
                ->addColumn('recipients_count', function ($message) {
                    $recipients = json_decode($message->recipients, true) ?? [];
                    return count($recipients);
                })
                ->addColumn('read_count', function ($message) {
                    $readBy = json_decode($message->read_by, true) ?? [];
                    return count($readBy);
                })
                ->addColumn('priority_badge', function ($message) {
                    return '<span class="badge bg-' . $message->priority_color . '">' . $message->priority_label . '</span>';
                })
                ->addColumn('created_at_formatted', function ($message) {
                    return $message->created_at->format('d/m/Y H:i');
                })
                ->addColumn('actions', function ($message) {
                    return view('internal_messages.enhanced.sent_actions', compact('message'))->render();
                })
                ->rawColumns(['priority_badge', 'actions'])
                ->make(true);
        }

        $messages = InternalMessage::where('sender_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('internal_messages.enhanced.sent', compact('messages'));
    }

    public function getUnreadCount()
    {
        $count = $this->messageService->getUnreadCount(Auth::user());
        return response()->json(['count' => $count]);
    }

    public function getRecentMessages()
    {
        $messages = $this->messageService->getRecentMessages(Auth::user(), 5);
        return response()->json($messages);
    }

    public function downloadAttachment(InternalMessage $internalMessage, $attachmentIndex)
    {
        $attachments = json_decode($internalMessage->attachments, true) ?? [];
        
        if (!isset($attachments[$attachmentIndex])) {
            abort(404, 'Archivo no encontrado');
        }

        $attachment = $attachments[$attachmentIndex];
        
        if (!Storage::disk('public')->exists($attachment['path'])) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        return Storage::disk('public')->download($attachment['path'], $attachment['name']);
    }

    public function markAllAsRead()
    {
        $messages = InternalMessage::whereJsonContains('recipients', Auth::id())
            ->where(function ($query) {
                $query->whereJsonDoesntContain('read_by', Auth::id())
                      ->orWhereNull('read_by');
            })
            ->get();

        foreach ($messages as $message) {
            $this->messageService->markAsRead($message, Auth::user());
        }

        return $this->sendSuccess('Todos los mensajes marcados como leídos');
    }

    public function destroy(InternalMessage $internalMessage)
    {
        // Only sender can delete the message
        if ($internalMessage->sender_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este mensaje');
        }

        // Delete attachments if any
        if ($internalMessage->attachments) {
            $attachments = json_decode($internalMessage->attachments, true) ?? [];
            foreach ($attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment['path'])) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        activity()->performedOn($internalMessage)->causedBy(Auth::user())
            ->useLog('Internal Message Deleted')
            ->log('Message deleted: ' . $internalMessage->subject);

        $internalMessage->delete();

        return $this->sendSuccess('Mensaje eliminado exitosamente');
    }
}
