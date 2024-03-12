<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => fn () => Category::factory()->create(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(1),
            'price' => $this->faker->randomFloat(2, 1, 999)
        ];
    }
}
