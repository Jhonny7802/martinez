<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BudgetAlert;
use App\Models\BudgetExpense;

class BudgetControl extends Model
{
    use HasFactory;

    protected $table = 'budget_controls';

    protected $fillable = [
        'project_id',
        'total_budget',
        'current_spent',
        'remaining_budget',
        'budget_status',
        'last_updated',
        'alert_threshold',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'total_budget' => 'float',
        'current_spent' => 'float',
        'remaining_budget' => 'float',
        'alert_threshold' => 'float',
        'last_updated' => 'datetime',
    ];

    // Budget status constants
    const STATUS_HEALTHY = 1;
    const STATUS_WARNING = 2;
    const STATUS_CRITICAL = 3;
    const STATUS_EXCEEDED = 4;

    // Budget status text
    const STATUS_TEXT = [
        self::STATUS_HEALTHY => 'Saludable',
        self::STATUS_WARNING => 'Advertencia',
        self::STATUS_CRITICAL => 'Crítico',
        self::STATUS_EXCEEDED => 'Excedido'
    ];

    // Budget status badge classes
    const STATUS_BADGE = [
        self::STATUS_HEALTHY => 'bg-success',
        self::STATUS_WARNING => 'bg-warning',
        self::STATUS_CRITICAL => 'bg-danger',
        self::STATUS_EXCEEDED => 'bg-dark'
    ];

    /**
     * Get the project that owns the budget control.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the budget expenses for this budget control.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(BudgetExpense::class, 'budget_control_id');
    }

    /**
     * Get the budget alerts for this budget control.
     */
    public function alerts(): HasMany
    {
        return $this->hasMany(BudgetAlert::class, 'budget_control_id');
    }

    /**
     * Calculate the percentage of budget spent
     */
    public function getPercentageSpentAttribute(): float
    {
        if ($this->total_budget <= 0) {
            return 0;
        }
        
        return min(100, round(($this->current_spent / $this->total_budget) * 100, 2));
    }

    /**
     * Calculate the percentage of budget remaining
     */
    public function getPercentageRemainingAttribute(): float
    {
        return max(0, 100 - $this->percentage_spent);
    }

    /**
     * Update budget status based on current spending
     */
    public function updateBudgetStatus(): void
    {
        $percentageSpent = $this->getPercentageSpentAttribute();
        
        if ($percentageSpent >= 100) {
            $this->budget_status = self::STATUS_EXCEEDED;
        } elseif ($percentageSpent >= 90) {
            $this->budget_status = self::STATUS_CRITICAL;
        } elseif ($percentageSpent >= $this->alert_threshold) {
            $this->budget_status = self::STATUS_WARNING;
        } else {
            $this->budget_status = self::STATUS_HEALTHY;
        }
        
        $this->save();
    }

    /**
     * Add an expense to this budget control
     */
    public function addExpense(float $amount, string $description, int $categoryId): BudgetExpense
    {
        $expense = BudgetExpense::create([
            'budget_control_id' => $this->id,
            'amount' => $amount,
            'description' => $description,
            'category_id' => $categoryId,
            'created_by' => auth()->id()
        ]);

        // Update the current spent amount
        $this->current_spent += $amount;
        $this->remaining_budget = max(0, $this->total_budget - $this->current_spent);
        $this->last_updated = now();
        $this->save();

        // Update the budget status
        $this->updateBudgetStatus();

        return $expense;
    }

    /**
     * Check if budget is exceeding threshold and create alert if needed
     */
    public function checkBudgetThreshold(): ?BudgetAlert
    {
        $percentageSpent = $this->getPercentageSpentAttribute();
        
        if ($percentageSpent >= $this->alert_threshold && $this->budget_status >= self::STATUS_WARNING) {
            // Create a budget alert
            return BudgetAlert::create([
                'budget_control_id' => $this->id,
                'project_id' => $this->project_id,
                'alert_type' => 'threshold_exceeded',
                'severity' => $this->budget_status,
                'message' => "Presupuesto al {$percentageSpent}% del total asignado",
                'is_acknowledged' => false,
                'created_by' => auth()->id() ?? 1
            ]);
        }
        
        return null;
    }
}
