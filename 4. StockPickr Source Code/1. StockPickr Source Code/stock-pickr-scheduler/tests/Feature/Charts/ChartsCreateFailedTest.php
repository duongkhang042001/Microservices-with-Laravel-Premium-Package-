<?php

namespace Tests\Feature\Metrics;

use App\Events\Charts\ChartsCreateFailed;
use App\Models\CompanySchedule;
use App\Models\CompanyUpdate;
use App\Repositories\CompanyScheduleRepository;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use StockPickr\Common\Containers\Charts\ChartsUpsertFailedContainer;

class ChartsCreateFailedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_deny_failed_company()
    {
        CompanySchedule::factory([
            'ticker' => 'AAPL',
            'event' => CompanyScheduleRepository::EVENT_CREATE_COMPANY
        ])->create();

        event(new ChartsCreateFailed(ChartsUpsertFailedContainer::from([
            'ticker' => 'AAPL',
            'message' => 'Invalid data'
        ])));

        $this->assertDatabaseHas('denied_companies', [
            'ticker' => 'AAPL'
        ]);
    }

    /** @test */
    public function it_should_remove_company_updates_for_a_failed_company()
    {
        CompanyUpdate::factory(['ticker' => 'AAPL'])->create();
        CompanySchedule::factory([
            'ticker' => 'AAPL',
            'event' => CompanyScheduleRepository::EVENT_CREATE_COMPANY
        ])->create();

        event(new ChartsCreateFailed(ChartsUpsertFailedContainer::from([
            'ticker' => 'AAPL',
            'message' => 'Invalid data'
        ])));

        $this->assertDatabaseMissing('company_updates', [
            'ticker' => 'AAPL'
        ]);
    }

    /** @test */
    public function it_should_set_create_company_schedule_to_failed()
    {
        CompanySchedule::factory([
            'ticker' => 'AAPL',
            'event' => CompanyScheduleRepository::EVENT_CREATE_COMPANY
        ])->create();

        event(new ChartsCreateFailed(ChartsUpsertFailedContainer::from([
            'ticker' => 'AAPL',
            'message' => 'Invalid data'
        ])));

        $this->assertDatabaseHas('company_schedules', [
            'ticker' => 'AAPL',
            'state' => CompanyScheduleRepository::STATE_FAILED
        ]);
    }

    /** @test */
    public function it_should_publish_remove_company_event()
    {
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishRemoveCompany')
                ->with('AAPL');
        });

        CompanySchedule::factory([
            'ticker' => 'AAPL',
            'event' => CompanyScheduleRepository::EVENT_CREATE_COMPANY
        ])->create();

        event(new ChartsCreateFailed(ChartsUpsertFailedContainer::from([
            'ticker' => 'AAPL',
            'message' => 'Invalid data'
        ])));
    }
}
