<?php

namespace Tests\Feature\CreateMarketData;

use App\Events\MarketData\MarketDataUpsertFailed;
use App\Models\CompanySchedule;
use App\Repositories\CompanyScheduleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use StockPickr\Common\Containers\MarketData\MarketDataUpsertFailedContainer;

class MarketDataCreateFailedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_set_schedule_to_failed()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_MARKET_DATA,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new MarketDataUpsertFailed(MarketDataUpsertFailedContainer::create('TST', 'Error while updating'), false, $schedule->id));

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'event'     => CompanyScheduleRepository::EVENT_CREATE_MARKET_DATA,
            'state'     => CompanyScheduleRepository::STATE_FAILED,
            'payload'   => 'Error while updating'
        ]);
    }

    /** @test */
    public function it_should_not_create_a_company_update_record()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_MARKET_DATA,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new MarketDataUpsertFailed(MarketDataUpsertFailedContainer::create('TST', 'Error while updating'), false, $schedule->id));

        $this->assertDatabaseCount('company_updates', 0);
    }
}
