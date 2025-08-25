<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetExpense extends Model
{
    use HasFactory;

    protected $table = 'budget_expenses';

    protected $fillable = [
        'budget_control_id',
        'amount',
        'description',
        'category_id',
        'expense_date',
        'invoice_id',
        'receipt_path',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'amount' => 'float',
        'expense_date' => 'datetime',
    ];

    /**
     * Get the budget control that owns the expense.
     */
    public function budgetControl(): BelongsTo
    {
        return $this->belongsTo(BudgetControl::class, 'budget_control_id');
    }

    /**
     * Get the expense category that owns the expense.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    /**
     * Get the invoice associated with the expense.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Get the user who created the expense.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include expenses for a specific project.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->whereHas('budgetControl', function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        });
    }

    /**
     * Scope a query to only include expenses for a specific category.
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to only include expenses within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    /**
     * Attach a receipt to the expense
     */
    public function attachReceipt($filePath)
    {
        $this->receipt_path = $filePath;
        $this->save();
        
        return $this;
    }
}
