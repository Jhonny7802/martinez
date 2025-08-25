<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\InventoryMovement;
use App\Queries\ProductDataTable;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ProductController extends AppBaseController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new ProductDataTable())->get($request->only(['group'])))->make(true);
        }

        $data = $this->productRepository->getSyncListForItem();

        return view('products.index', compact('data'));
    }

    /**
     * @param  CreateProductRequest  $request
     * @return mixed
     */
    public function store(CreateProductRequest $request)
    {
        $input = $request->all();
        $input['rate'] = removeCommaFromNumbers($input['rate']);

        $product = $this->productRepository->create($input);

        activity()->performedOn($product)->causedBy(getLoggedInUser())
            ->useLog('New Product created.')->log($product->title.' Product created.');

        return $this->sendSuccess(__('messages.products.product_saved_successfully'));
    }

    /**
     * @param  Product  $product
     * @return mixed
     */
    public function edit(Product $product)
    {
        $product = $this->productRepository->getProduct($product->id);

        return $this->sendResponse($product, 'Product retrieved successfully.');
    }

    /**
     * @param  Product  $product
     * @param  UpdateProductRequest  $request
     * @return mixed
     */
    public function update(Product $product, UpdateProductRequest $request)
    {
        $input = $request->all();
        $input['rate'] = removeCommaFromNumbers($input['rate']);

        $product = $this->productRepository->update($input, $product->id);

        activity()->performedOn($product)->causedBy(getLoggedInUser())
            ->useLog('Product updated.')->log($product->title.' Product updated.');

        return $this->sendSuccess(__('messages.products.product_updated_successfully'));
    }

    /**
     * @param  Product  $product
     * @return mixed
     */
    public function destroy(Product $product)
    {
        activity()->performedOn($product)->causedBy(getLoggedInUser())
            ->useLog('Product deleted.')->log($product->title.' Product deleted.');

        $product->delete();

        return $this->sendSuccess('Product deleted successfully.');
    }

    /**
     * Get low stock items
     */
    public function getLowStock()
    {
        $lowStockItems = Product::getLowStockItems(10);
        return response()->json($lowStockItems);
    }

    /**
     * Get out of stock items
     */
    public function getOutOfStock()
    {
        $outOfStockItems = Product::getOutOfStockItems(10);
        return response()->json($outOfStockItems);
    }

    /**
     * Get inventory movements for a product
     */
    public function getMovements(Product $product)
    {
        $movements = $product->inventoryMovements()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($movements);
    }

    /**
     * Adjust stock for a product
     */
    public function adjustStock(Request $request, Product $product)
    {
        $request->validate([
            'adjustment_type' => 'required|in:set,add,subtract',
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255'
        ]);

        $previousStock = $product->stock_quantity;
        $newStock = match($request->adjustment_type) {
            'set' => $request->quantity,
            'add' => $previousStock + $request->quantity,
            'subtract' => max(0, $previousStock - $request->quantity),
            default => $previousStock
        };

        $product->update(['stock_quantity' => $newStock]);

        // Record movement
        InventoryMovement::create([
            'item_id' => $product->id,
            'movement_type' => 'adjustment',
            'quantity' => abs($newStock - $previousStock),
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reference_type' => 'manual_adjustment',
            'user_id' => Auth::id(),
            'notes' => $request->reason
        ]);

        activity()->performedOn($product)->causedBy(Auth::user())
            ->useLog('Stock Adjustment')
            ->log("Stock adjusted from {$previousStock} to {$newStock}. Reason: {$request->reason}");

        return $this->sendSuccess('Stock ajustado exitosamente');
    }
}
