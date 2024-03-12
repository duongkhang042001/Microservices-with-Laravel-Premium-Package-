<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetProductInventoryRequest;
use App\Http\Resources\ProductResource;
use App\Models\Inventory;
use App\Models\Product;

class ProductInventoryController extends Controller
{
    public function index(GetProductInventoryRequest $request)
    {
        $products = Product::find($request->getProductIds());

        $inventories = Inventory::totalQuantities($products);

        return [
            'data' => $inventories,
        ];
    }

    public function get(Product $product)
    {
        return [
            'data' => new ProductResource($product),
        ];
    }
}
