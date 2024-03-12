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

    private CompanyUpsertedContainerFactory $factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = resolve(CompanyUpsertedContainerFactory::class);
    }

    /** @test */
    public function it_should_create_a_company()
    {
        $data = $this->factory->createCompanyUpsertedContainer([
            'ticker' => 'TST',
        ]);
        $data->sector->name = 'Tech';
        $data->name = 'Test, Inc.';

        event(new CompanyUpserted($data));

        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST',
            'name' => 'Test, Inc.',
            'sector' => 'Tech'
        ]);
    }

    /** @test */
    public function it_should_update_an_existing_company()
    {
        Company::factory(['ticker' => 'TST', 'sector' => 'Tech'])->create();
        $data = $this->factory->createCompanyUpsertedContainer([
            'ticker' => 'TST',
        ]);
        $data->sector->name = 'More Tech';
        $data->name = 'Testing, Inc.';

        event(new CompanyUpserted($data));

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST',
            'name' => 'Testing, Inc.',
            'sector' => 'More Tech'
        ]);
    }
}
