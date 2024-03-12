<?php

namespace Tests\Feature\Update;

use App\Repositories\CompanyScheduleRepository;
use App\Services\CompanyProviderService;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use StockPickr\Common\Containers\UpsertCompanyContainer as CompanyContainer;

class UpdateCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_company_schedule_and_publish_an_update_company_event()
    {
        $this->mockCompanyProvider('TST');
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishUpdateCompany');
        });

        $this->artisan('company:update');

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'event'     => CompanyScheduleRepository::EVENT_UPDATE_COMPANY,
            'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
        ]);
    }

    private function mockCompanyProvider(string $ticker)
    {
        $this->mock(CompanyProviderService::class, function (MockInterface $mock) use ($ticker) {
            $mock->shouldReceive('getNextCompanyForUpdate')
                ->andReturn(CompanyContainer::from([
                    'ticker'    => $ticker,
                    'name'      => 'Test Inc.',
                    'sector'    => 'Tech',
                    'financialStatements' => [
                        'incomeStatements'  => [],
                        'balanceSheets'     => [],
                        'cashFlows'         => []
                    ]
                ]));
        });
    }
}
