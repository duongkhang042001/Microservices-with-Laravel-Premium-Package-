<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Sector;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $industry = collect($this->faker->words(2))
            ->map(function ($word) {
                return Str::ucfirst($word);
            })
            ->implode(' ');

        return [
            'ticker'                        => substr(Str::upper($this->faker->unique()->word), 0,  3) . $this->faker->numberBetween(1, 999),
            'name'                          => $this->faker->company,
            'sector_id'                     => Sector::factory()->create(),
            'description'                   => implode(' ', $this->faker->paragraphs()),
            'industry'                      => $industry,
            'employees'                     => $this->faker->numberBetween(1000, 200000),
            'ceo'                           => $this->faker->name('male'),
        ];
    }
}
