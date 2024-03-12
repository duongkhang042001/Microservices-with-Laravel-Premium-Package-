<?php

namespace App\Http\Controllers;

use App\Actions\CreateOrderAction;
use App\Exceptions\ProductInventoryExceededException;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, CreateOrderAction $createOrder)
    {
        try {
            $order = $createOrder->execute(
                Product::findOrFail($request->getProductId()),
                $request->getQuantity(),
            );

            return new OrderResource($order);
        } catch (ProductInventoryExceededException $ex) {
            return response([
                'errors' => ['quantity' => $ex->getMessage()]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
