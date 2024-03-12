<?php

namespace Tests\Feature\Api\Company\Log;

use App\Enums\CompanyLogActions;
use App\Models\Company\Company;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery\MockInterface;

class StoreCompanyViewLogApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_201()
    {
        Company::factory()
            ->state(['ticker' => 'TST'])
            ->create();

        $response = $this->json('POST', '/api/v1/companies/logs', [
            'action'  => 'view',
            'payload' => 'TST'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @test
     * @dataProvider invalidDataProvider
     */
    public function it_should_return_422_if_invalid_data(string $action, string $payload)
    {
        Company::factory()
            ->state(['ticker' => 'TST'])
            ->create();

        $response = $this->json('POST', '/api/v1/companies/logs', [
            'action'  => $action,
            'payload' => $payload
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_should_store_a_log_for_company_view()
    {
        Company::factory()
            ->state(['ticker' => 'TST'])
            ->create();

        $this->json('POST', '/api/v1/companies/logs', [
            'action'  => CompanyLogActions::VIEW,
            'payload' => 'TST'
        ]);

        $this->assertDatabaseHas('company_logs', [
            'payload'   => 'TST',
            'action'    => CompanyLogActions::VIEW
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

    public function invalidDataProvider()
    {
        return [
            ['', 'payload'],
            ['view', ''],
            ['invalid-action', 'payload']
        ];
    }
}
