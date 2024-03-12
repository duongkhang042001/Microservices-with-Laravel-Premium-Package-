<?php

namespace App\Actions;

use App\Models\Warehouse;

class CreateWarehouseAction
{
    public function execute(string $name): Warehouse
    {
        return Warehouse::create([
            'name' => $name,
        ]);
    }
}
