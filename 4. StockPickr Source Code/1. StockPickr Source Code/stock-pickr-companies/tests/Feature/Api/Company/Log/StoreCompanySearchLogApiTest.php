<?php

namespace Tests\Feature\Api\Company\Log;

use App\Enums\CompanyLogActions;
use App\Models\Company\Company;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery\MockInterface;

class StoreCompanySearchLogApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_201()
    {
        Company::factory()
            ->state(['ticker' => 'TST'])
            ->create();

        $response = $this->json('POST', '/api/v1/companies/logs', [
            'action'        => 'search',
            'payload'       => 'Test Inc.'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function it_should_store_a_log_for_company_search()
    {
        Company::factory()
            ->state(['ticker' => 'TST'])
            ->create();

        $this->json('POST', '/api/v1/companies/logs', [
            'action'  => CompanyLogActions::SEARCH,
            'payload' => 'Test Inc.'
        ]);

        $this->assertDatabaseHas('company_logs', [
            'payload'    => 'Test Inc.',
            'action'     => CompanyLogActions::SEARCH
        ]);
    }

    /** @test */
    public function it_should_publish_a_company_log_created_event()
    {
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishCompanyLogCreated')
                ->with('view', 'TST');
        });

        Company::factory()
            ->state(['ticker' => 'TST'])
            ->create();

        $this->json('POST', '/api/v1/companies/logs', [
            'action'  => 'view',
            'payload' => 'TST'
        ]);
    }
}
