<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'dimensions',
        'default_elements',
        'preview_image',
        'style_properties',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'default_elements' => 'array',
        'style_properties' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the design projects using this template.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(DesignProject::class, 'template_id');
    }

    /**
     * Get the user who created this template.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get only active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
