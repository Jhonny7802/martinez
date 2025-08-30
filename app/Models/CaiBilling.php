<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CaiBilling extends Model
{
    use HasFactory;

    protected $fillable = [
        'cai_number',
        'invoice_number',
        'company_name',
        'customer_rtn',
        'customer_address',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'issue_date',
        'due_date',
        'status',
        'notes',
        'payment_method',
        'payment_date',
        'items'
    ];

    protected $casts = [
        'items' => 'array',
        'issue_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Generate a random CAI number
     */
    public static function generateCaiNumber()
    {
        do {
            // Generate CAI format: CAI-YYYYMMDD-XXXXXXXX (8 random digits)
            $date = now()->format('Ymd');
            $randomNumber = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            $caiNumber = "CAI-{$date}-{$randomNumber}";
        } while (self::where('cai_number', $caiNumber)->exists());

        return $caiNumber;
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -6) + 1 : 1;
        
        return 'INV-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method to auto-generate CAI and invoice numbers
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->cai_number)) {
                $model->cai_number = self::generateCaiNumber();
            }
            if (empty($model->invoice_number)) {
                $model->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

    /**
     * Relationship with Customer (using company_name)
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'company_name', 'company_name');
    }

    /**
     * Relationship with CaiBillingItems
     */
    public function items(): HasMany
    {
        return $this->hasMany(CaiBillingItem::class);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute()
    {
        return 'L. ' . number_format($this->total_amount, 2);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'issued' => 'warning',
            'paid' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Borrador',
            'issued' => 'Emitida',
            'paid' => 'Pagada',
            'cancelled' => 'Cancelada',
            default => 'Desconocido'
        };
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('issue_date', [$startDate, $endDate]);
    }

    /**
     * Calculate totals from items
     */
    public function calculateTotals()
    {
        $subtotal = 0;
        $taxAmount = 0;

        if ($this->items) {
            foreach ($this->items as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $subtotal += $itemTotal;
                
                if (isset($item['tax_rate']) && $item['tax_rate'] > 0) {
                    $taxAmount += $itemTotal * ($item['tax_rate'] / 100);
                }
            }
        }

        $this->subtotal = $subtotal;
        $this->tax_amount = $taxAmount;
        $this->total_amount = $subtotal + $taxAmount - $this->discount_amount;
    }
}
