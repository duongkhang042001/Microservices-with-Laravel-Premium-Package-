<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\CompanyPeer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class CompanyPeerFactory extends Factory
{
    protected $model = CompanyPeer::class;

    public function definition()
    {
        return [
            'company_id' => fn () => Company::factory()->create(),
            'peer_id' => fn () => Company::factory()->create(),
            'ticker' => substr(Str::upper($this->faker->unique()->word), 0,  3) . $this->faker->numberBetween(1, 999),
        ];
    }
}
