<?php

namespace App\Http\Requests;

class StoreInventoryRequest extends ApiFormRequest
{
    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function rules()
    {
        return [
            'productId' => 'required|exists:products,id',
            'warehouseId' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric|min:1',
        ];
    }
}
