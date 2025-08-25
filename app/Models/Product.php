<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property float $rate
 * @property int|null $tax_1_id
 * @property int|null $tax_2_id
 * @property int $item_group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TaxRate|null $firstTax
 * @property-read ProductGroup $group
 * @property-read TaxRate|null $secondTax
 *
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereItemGroupId($value)
 * @method static Builder|Product whereRate($value)
 * @method static Builder|Product whereTax1Id($value)
 * @method static Builder|Product whereTax2Id($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Product extends Model
{
    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'rate',
        'tax_1_id',
        'tax_2_id',
        'item_group_id',
        'stock_quantity',
        'minimum_stock',
        'maximum_stock',
        'unit_of_measure',
        'location',
        'cost_price',
        'supplier',
        'barcode',
        'status',
        'notes',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|unique:items,title',
        'rate' => 'required',
        'description' => 'nullable',
        'tax_1_id' => 'nullable',
        'tax_2_id' => 'nullable',
        'item_group_id' => 'required',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'rate' => 'double',
        'tax_1_id' => 'integer',
        'tax_2_id' => 'integer',
        'item_group_id' => 'integer',
        'stock_quantity' => 'integer',
        'minimum_stock' => 'integer',
        'maximum_stock' => 'integer',
        'cost_price' => 'decimal:2',
    ];

    /**
     * @return BelongsTo
     */
    public function firstTax(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_1_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function secondTax(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_2_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class, 'item_group_id');
    }

    public function requisitionItems()
    {
        return $this->hasMany(MaterialRequisitionItem::class, 'item_id');
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'item_id');
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->minimum_stock;
    }

    public function isOutOfStock()
    {
        return $this->stock_quantity <= 0;
    }

    public function getStockStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getStockStatusColorAttribute()
    {
        return [
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'in_stock' => 'success'
        ][$this->stock_status] ?? 'secondary';
    }

    public function getStockStatusLabelAttribute()
    {
        return [
            'out_of_stock' => 'Sin Stock',
            'low_stock' => 'Stock Bajo',
            'in_stock' => 'En Stock'
        ][$this->stock_status] ?? 'N/A';
    }

    public static function getLowStockItems($limit = null)
    {
        $query = self::whereColumn('stock_quantity', '<=', 'minimum_stock')
                     ->where('status', 'active');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    public static function getOutOfStockItems($limit = null)
    {
        $query = self::where('stock_quantity', '<=', 0)
                     ->where('status', 'active');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
}
