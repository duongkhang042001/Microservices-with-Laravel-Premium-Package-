<?php

namespace Tests\Feature\Events;

use App\Events\CompanyUpserted;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use StockPickr\Common\Testing\Factories\CompanyUpsertedContainerFactory;

class CompanyUpsertedTest extends TestCase
{
    use RefreshDatabase;

    private CompanyUpsertedContainerFactory $factor;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = resolve(CompanyUpsertedContainerFactory::class);
    }

    /** @test */
    public function it_should_create_a_company()
    {
        event(new CompanyUpserted($this->factory->createCompanyUpsertedContainer([
            'ticker' => 'TST',
            'sector' => [
                'id' => 1,
                'name' => 'Tech'
            ]
        ]), false));

        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST',
            'sector' => 'Tech',
            'total_scores' => null,
        ]);
    }

    /** @test */
    public function it_should_update_a_company()
    {
        Company::factory([
            'ticker' => 'TST',
            'sector' => 'IT'
        ])->create();

        event(new CompanyUpserted($this->factory->createCompanyUpsertedContainer([
            'ticker' => 'TST',
            'sector' => [
                'id' => 1,
                'name' => 'Tech'
            ]
        ]), true));

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST',
            'sector' => 'Tech',
        ]);
    }
}
