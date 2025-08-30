<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'customer_id',
        'template_id',
        'description',
        'dimensions',
        'color_scheme',
        'deadline',
        'budget',
        'priority',
        'status',
        'preview_image',
        'final_design',
        'preview_generated_at',
        'completed_at',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'deadline' => 'date',
        'budget' => 'decimal:2',
        'preview_generated_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the customer that owns the design project.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the template used for this project.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(DesignTemplate::class);
    }

    /**
     * Get the design elements for this project.
     */
    public function elements(): HasMany
    {
        return $this->hasMany(DesignElement::class)->orderBy('layer_order');
    }

    /**
     * Get the user who created this project.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get status label with color coding.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Borrador',
            'in_progress' => 'En Progreso',
            'review' => 'En Revisión',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => 'Desconocido'
        };
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'in_progress' => 'primary',
            'review' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'urgent' => 'Urgente',
            default => 'Media'
        };
    }

    /**
     * Get priority color for UI.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'info'
        };
    }

    /**
     * Check if project is editable.
     */
    public function isEditable(): bool
    {
        return !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Mark project as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }
}
