<?php

namespace Database\Factories\Company;

use App\Models\Company\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SectorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sector::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words(3, true);
        return [
            'name'  => $name,
            'slug'  => Str::slug($name)
        ];
    }
}
