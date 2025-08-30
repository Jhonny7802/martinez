<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'design_project_id',
        'element_type',
        'content',
        'position_x',
        'position_y',
        'width',
        'height',
        'layer_order',
        'style_properties',
        'is_locked',
        'is_visible'
    ];

    protected $casts = [
        'position_x' => 'decimal:2',
        'position_y' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'style_properties' => 'array',
        'is_locked' => 'boolean',
        'is_visible' => 'boolean'
    ];

    /**
     * Get the design project that owns this element.
     */
    public function designProject(): BelongsTo
    {
        return $this->belongsTo(DesignProject::class);
    }

    /**
     * Get element type label.
     */
    public function getElementTypeLabelAttribute(): string
    {
        return match($this->element_type) {
            'text' => 'Texto',
            'image' => 'Imagen',
            'shape' => 'Forma',
            'logo' => 'Logo',
            'icon' => 'Icono',
            default => 'Desconocido'
        };
    }

    /**
     * Scope to get only visible elements.
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope to get elements by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('element_type', $type);
    }
}
