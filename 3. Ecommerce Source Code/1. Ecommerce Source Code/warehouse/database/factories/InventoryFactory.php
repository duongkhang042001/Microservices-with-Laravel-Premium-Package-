<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition()
    {
        return [
            'product_id' => fn () => Product::factory()->create(),
            'warehouse_id' => fn () => Warehouse::factory()->create(),
            'quantity' => rand(0, 100),
        ];
    }
}
