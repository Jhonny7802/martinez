<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRequisitionItem extends Model
{
    protected $fillable = [
        'requisition_id',
        'item_id',
        'quantity_requested',
        'quantity_approved',
        'quantity_delivered',
        'unit_cost',
        'total_cost',
        'specifications'
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(MaterialRequisition::class, 'requisition_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    public function getPendingQuantityAttribute()
    {
        return $this->quantity_approved - $this->quantity_delivered;
    }

    public function getDeliveryPercentageAttribute()
    {
        if ($this->quantity_approved == 0) return 0;
        return round(($this->quantity_delivered / $this->quantity_approved) * 100, 2);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            if ($item->unit_cost && $item->quantity_approved) {
                $item->total_cost = $item->unit_cost * $item->quantity_approved;
            }
        });
    }
}
