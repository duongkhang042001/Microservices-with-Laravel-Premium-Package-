<?php

namespace App\Http\Controllers;

use App\Actions\CreateInventoryAction;
use App\Http\Requests\StoreInventoryRequest;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Response;

class InventoryController extends Controller
{
    public function store(StoreInventoryRequest $request, CreateInventoryAction $createInventory)
    {
        $inventoryData = $createInventory->execute(
            Product::findOrFail($request->getProductId()),
            Warehouse::findOrFail($request->getWarehouseId()),
            $request->getQuantity(),
        );

        return response([
            'data' => $inventoryData,
        ], Response::HTTP_CREATED);
    }
}
