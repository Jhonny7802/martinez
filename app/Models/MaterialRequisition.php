<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialRequisition extends Model
{
    protected $fillable = [
        'requisition_number',
        'project_id',
        'requested_by',
        'approved_by',
        'status',
        'priority',
        'required_date',
        'purpose',
        'notes',
        'total_cost',
        'approved_at',
        'delivered_at'
    ];

    protected $casts = [
        'required_date' => 'date',
        'approved_at' => 'datetime',
        'delivered_at' => 'datetime',
        'total_cost' => 'decimal:2'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_APPROVED => 'Aprobado',
            self::STATUS_REJECTED => 'Rechazado',
            self::STATUS_DELIVERED => 'Entregado',
            self::STATUS_CANCELLED => 'Cancelado'
        ];
    }

    public static function getPriorities()
    {
        return [
            self::PRIORITY_LOW => 'Baja',
            self::PRIORITY_MEDIUM => 'Media',
            self::PRIORITY_HIGH => 'Alta',
            self::PRIORITY_URGENT => 'Urgente'
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MaterialRequisitionItem::class, 'requisition_id');
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute()
    {
        return self::getPriorities()[$this->priority] ?? $this->priority;
    }

    public function getStatusColorAttribute()
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'info',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_DELIVERED => 'success',
            self::STATUS_CANCELLED => 'secondary'
        ][$this->status] ?? 'secondary';
    }

    public function getPriorityColorAttribute()
    {
        return [
            self::PRIORITY_LOW => 'success',
            self::PRIORITY_MEDIUM => 'info',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_URGENT => 'danger'
        ][$this->priority] ?? 'secondary';
    }

    public static function generateRequisitionNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastRequisition = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastRequisition ? (int)substr($lastRequisition->requisition_number, -4) + 1 : 1;
        
        return 'REQ-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotalCost()
    {
        $this->total_cost = $this->items->sum(function ($item) {
            return $item->quantity_approved * $item->unit_cost;
        });
        $this->save();
    }
}
