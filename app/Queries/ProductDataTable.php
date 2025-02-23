<?php

namespace App\Queries;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TagDataTable
 */
class ProductDataTable
{
    /**
     * @param  array  $input
     * @return Product
     */
    public function get($input = [])
    {
        /** @var Product $query */
        $query = Product::with(['group', 'firstTax', 'secondTax'])->select('items.*')->latest();

        $query->when(isset($input['group']) && $input['group'] != ProductGroup::pluck('name', 'id'),
            function (Builder $q) use ($input) {
                $q->where('item_group_id', '=', $input['group']);
            });

        return $query;
    }
}
