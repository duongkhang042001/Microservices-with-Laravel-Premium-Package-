<?php

namespace App\Http\Controllers;

use App\Actions\CreateWarehouseAction;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        return [
            'data' => WarehouseResource::collection(Warehouse::all()),
        ];
    }

    public function store(StoreWarehouseRequest $request, CreateWarehouseAction $createWarehouse)
    {
        return new WarehouseResource(
            $createWarehouse->execute($request->getName()),
        );
    }
}
