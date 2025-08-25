<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetAlert extends Model
{
    use HasFactory;

    protected $table = 'budget_alerts';

    protected $fillable = [
        'budget_control_id',
        'project_id',
        'alert_type',
        'severity',
        'message',
        'is_acknowledged',
        'acknowledged_by',
        'acknowledged_at',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'is_acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    // Alert types
    const TYPE_THRESHOLD_EXCEEDED = 'threshold_exceeded';
    const TYPE_BUDGET_EXCEEDED = 'budget_exceeded';
    const TYPE_EXPENSE_ANOMALY = 'expense_anomaly';
    const TYPE_FORECAST_WARNING = 'forecast_warning';

    // Severity levels
    const SEVERITY_LOW = 1;
    const SEVERITY_MEDIUM = 2;
    const SEVERITY_HIGH = 3;
    const SEVERITY_CRITICAL = 4;

    // Severity badge classes
    const SEVERITY_BADGE = [
        self::SEVERITY_LOW => 'bg-success',
        self::SEVERITY_MEDIUM => 'bg-info',
        self::SEVERITY_HIGH => 'bg-warning',
        self::SEVERITY_CRITICAL => 'bg-danger'
    ];

    // Severity text
    const SEVERITY_TEXT = [
        self::SEVERITY_LOW => 'Baja',
        self::SEVERITY_MEDIUM => 'Media',
        self::SEVERITY_HIGH => 'Alta',
        self::SEVERITY_CRITICAL => 'Crítica'
    ];
    
    // Alert type text
    const ALERT_TYPE_TEXT = [
        self::TYPE_THRESHOLD_EXCEEDED => 'Umbral Excedido',
        self::TYPE_BUDGET_EXCEEDED => 'Presupuesto Excedido',
        self::TYPE_EXPENSE_ANOMALY => 'Anomalía de Gasto',
        self::TYPE_FORECAST_WARNING => 'Advertencia de Pronóstico'
    ];

    /**
     * Get the budget control that owns the alert.
     */
    public function budgetControl(): BelongsTo
    {
        return $this->belongsTo(BudgetControl::class, 'budget_control_id');
    }

    /**
     * Get the project that owns the alert.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the user who acknowledged the alert.
     */
    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    /**
     * Get the user who created the alert.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include unacknowledged alerts.
     */
    public function scopeUnacknowledged($query)
    {
        return $query->where('is_acknowledged', false);
    }

    /**
     * Scope a query to only include alerts for a specific project.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to only include alerts with a minimum severity.
     */
    public function scopeMinSeverity($query, $severity)
    {
        return $query->where('severity', '>=', $severity);
    }

    /**
     * Acknowledge the alert
     */
    public function acknowledge(int $userId = null): void
    {
        $this->is_acknowledged = true;
        $this->acknowledged_by = $userId ?? auth()->id();
        $this->acknowledged_at = now();
        $this->save();
    }

    /**
     * Get the severity badge HTML
     */
    public function getSeverityBadgeAttribute(): string
    {
        $class = self::SEVERITY_BADGE[$this->severity] ?? 'bg-secondary';
        $text = self::SEVERITY_TEXT[$this->severity] ?? 'Desconocido';
        
        return "<span class=\"badge {$class}\">{$text}</span>";
    }
}
