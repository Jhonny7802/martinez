<?php

namespace App\Services;

use App\Models\InternalMessage;
use App\Models\User;
use App\Mail\InternalMessageNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InternalMessageService
{
    /**
     * Send internal message with email notifications
     */
    public function sendMessage(array $data, User $sender)
    {
        // Create the internal message
        $message = InternalMessage::create([
            'sender_id' => $sender->id,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'priority' => $data['priority'] ?? 'medium',
            'recipients' => json_encode($data['recipients']),
            'attachments' => isset($data['attachments']) ? json_encode($data['attachments']) : null,
            'read_by' => json_encode([]),
            'is_broadcast' => count($data['recipients']) > 1
        ]);

        // Send email notifications to recipients
        foreach ($data['recipients'] as $recipientId) {
            $recipient = User::find($recipientId);
            if ($recipient && $recipient->email) {
                try {
                    Mail::to($recipient->email)->send(new InternalMessageNotification($message, $recipient));
                } catch (\Exception $e) {
                    Log::error('Failed to send email notification for internal message', [
                        'message_id' => $message->id,
                        'recipient_id' => $recipient->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return $message;
    }

    /**
     * Mark message as read by user
     */
    public function markAsRead(InternalMessage $message, User $user)
    {
        $readBy = json_decode($message->read_by, true) ?? [];
        
        if (!in_array($user->id, $readBy)) {
            $readBy[] = $user->id;
            $message->update(['read_by' => json_encode($readBy)]);
        }

        return $message;
    }

    /**
     * Get unread messages count for user
     */
    public function getUnreadCount(User $user)
    {
        return InternalMessage::whereJsonContains('recipients', $user->id)
            ->where(function ($query) use ($user) {
                $query->whereJsonDoesntContain('read_by', $user->id)
                      ->orWhereNull('read_by');
            })
            ->count();
    }

    /**
     * Get recent messages for user
     */
    public function getRecentMessages(User $user, $limit = 5)
    {
        return InternalMessage::with('sender')
            ->whereJsonContains('recipients', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Reply to a message
     */
    public function replyToMessage(InternalMessage $originalMessage, array $data, User $sender)
    {
        // Get original recipients and sender
        $originalRecipients = json_decode($originalMessage->recipients, true) ?? [];
        $originalSenderId = $originalMessage->sender_id;

        // Create reply recipients (original sender + original recipients, excluding current user)
        $replyRecipients = array_unique(array_merge($originalRecipients, [$originalSenderId]));
        $replyRecipients = array_filter($replyRecipients, function($id) use ($sender) {
            return $id != $sender->id;
        });

        // Create reply message
        $replyData = [
            'subject' => 'Re: ' . $originalMessage->subject,
            'message' => $data['message'],
            'priority' => $data['priority'] ?? $originalMessage->priority,
            'recipients' => array_values($replyRecipients),
            'attachments' => $data['attachments'] ?? null
        ];

        return $this->sendMessage($replyData, $sender);
    }

    /**
     * Forward a message
     */
    public function forwardMessage(InternalMessage $originalMessage, array $data, User $sender)
    {
        $forwardData = [
            'subject' => 'Fwd: ' . $originalMessage->subject,
            'message' => "--- Mensaje Reenviado ---\n" .
                        "De: " . $originalMessage->sender->name . "\n" .
                        "Fecha: " . $originalMessage->created_at->format('d/m/Y H:i') . "\n" .
                        "Asunto: " . $originalMessage->subject . "\n\n" .
                        $originalMessage->message . "\n\n" .
                        "--- Comentario ---\n" . $data['message'],
            'priority' => $data['priority'] ?? 'medium',
            'recipients' => $data['recipients'],
            'attachments' => $data['attachments'] ?? null
        ];

        return $this->sendMessage($forwardData, $sender);
    }

    /**
     * Create broadcast message
     */
    public function createBroadcast(array $data, User $sender)
    {
        // Get all active users except sender
        $allUsers = User::where('status', 'active')
            ->where('id', '!=', $sender->id)
            ->pluck('id')
            ->toArray();

        $broadcastData = array_merge($data, [
            'recipients' => $allUsers,
            'is_broadcast' => true
        ]);

        return $this->sendMessage($broadcastData, $sender);
    }

    /**
     * Get message statistics
     */
    public function getMessageStats(User $user)
    {
        $sent = InternalMessage::where('sender_id', $user->id)->count();
        $received = InternalMessage::whereJsonContains('recipients', $user->id)->count();
        $unread = $this->getUnreadCount($user);

        return [
            'sent' => $sent,
            'received' => $received,
            'unread' => $unread,
            'read' => $received - $unread
        ];
    }
}
