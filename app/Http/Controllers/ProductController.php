<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Queries\ProductDataTable;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
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
}
