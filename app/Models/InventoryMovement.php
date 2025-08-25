<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $fillable = [
        'item_id',
        'movement_type',
        'quantity',
        'previous_stock',
        'new_stock',
        'unit_cost',
        'reference_type',
        'reference_id',
        'user_id',
        'notes'
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2'
    ];

    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';
    const TYPE_ADJUSTMENT = 'adjustment';
    const TYPE_TRANSFER = 'transfer';

    public static function getTypes()
    {
        return [
            self::TYPE_IN => 'Entrada',
            self::TYPE_OUT => 'Salida',
            self::TYPE_ADJUSTMENT => 'Ajuste',
            self::TYPE_TRANSFER => 'Transferencia'
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute()
    {
        return self::getTypes()[$this->movement_type] ?? $this->movement_type;
    }

    public function getTypeColorAttribute()
    {
        return [
            self::TYPE_IN => 'success',
            self::TYPE_OUT => 'danger',
            self::TYPE_ADJUSTMENT => 'warning',
            self::TYPE_TRANSFER => 'info'
        ][$this->movement_type] ?? 'secondary';
    }

    public static function recordMovement($itemId, $type, $quantity, $userId, $notes = null, $unitCost = null, $referenceType = null, $referenceId = null)
    {
        $item = Product::find($itemId);
        if (!$item) return false;

        $previousStock = $item->stock_quantity ?? 0;
        
        $newStock = match($type) {
            self::TYPE_IN => $previousStock + $quantity,
            self::TYPE_OUT => $previousStock - $quantity,
            self::TYPE_ADJUSTMENT => $quantity, // For adjustments, quantity is the new total
            self::TYPE_TRANSFER => $previousStock - $quantity,
            default => $previousStock
        };

        // Update item stock
        $item->update(['stock_quantity' => $newStock]);

        // Record movement
        return self::create([
            'item_id' => $itemId,
            'movement_type' => $type,
            'quantity' => abs($quantity),
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'unit_cost' => $unitCost,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'user_id' => $userId,
            'notes' => $notes
        ]);
    }
}
