<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipients',
        'subject',
        'message',
        'priority',
        'attachments',
        'read_by',
        'is_broadcast',
        'parent_message_id',
        'message_type',
    ];

    protected $casts = [
        'recipients' => 'array',
        'attachments' => 'array',
        'read_by' => 'array',
        'is_broadcast' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Message type constants
    const TYPE_NORMAL = 'normal';
    const TYPE_REPLY = 'reply';
    const TYPE_FORWARD = 'forward';
    const TYPE_BROADCAST = 'broadcast';

    /**
     * Get the sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the parent message if this is a reply or forward
     */
    public function parentMessage()
    {
        return $this->belongsTo(InternalMessage::class, 'parent_message_id');
    }

    /**
     * Get replies to this message
     */
    public function replies()
    {
        return $this->hasMany(InternalMessage::class, 'parent_message_id');
    }

    /**
     * Check if message is read by specific user
     */
    public function isReadBy($userId)
    {
        $readBy = $this->read_by ?? [];
        return in_array($userId, $readBy);
    }

    /**
     * Mark message as read by user
     */
    public function markAsReadBy($userId)
    {
        $readBy = $this->read_by ?? [];
        if (!in_array($userId, $readBy)) {
            $readBy[] = $userId;
            $this->update(['read_by' => $readBy]);
        }
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'Baja',
            self::PRIORITY_MEDIUM => 'Media',
            self::PRIORITY_HIGH => 'Alta',
            self::PRIORITY_URGENT => 'Urgente',
            default => 'Media'
        };
    }

    /**
     * Get priority color class
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'success',
            self::PRIORITY_MEDIUM => 'info',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_URGENT => 'danger',
            default => 'info'
        };
    }

    /**
     * Get formatted recipients list
     */
    public function getRecipientsListAttribute()
    {
        if (!$this->recipients) {
            return collect();
        }

        return User::whereIn('id', $this->recipients)->get();
    }

    /**
     * Scope for messages sent to a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereJsonContains('recipients', $userId)
              ->orWhere('sender_id', $userId);
        });
    }

    /**
     * Scope for unread messages for a user
     */
    public function scopeUnreadForUser($query, $userId)
    {
        return $query->whereJsonContains('recipients', $userId)
                    ->where(function($q) use ($userId) {
                        $q->whereNull('read_by')
                          ->orWhereJsonDoesntContain('read_by', $userId);
                    });
    }

    /**
     * Scope for messages by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for broadcast messages
     */
    public function scopeBroadcast($query)
    {
        return $query->where('is_broadcast', true);
    }
}
