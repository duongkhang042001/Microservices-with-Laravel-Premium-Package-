<?php

namespace Tests\Feature\UpdateMarketData;

use App\Events\MarketData\MarketDataUpserted;
use App\Models\CompanySchedule;
use App\Models\CompanyUpdate;
use App\Repositories\CompanyScheduleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarketDataUpdatedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_set_schedule_to_succeeded()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_UPDATE_MARKET_DATA,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new MarketDataUpserted($this->createUpsertMarketDataContainer([
            'ticker'        => 'TST'
        ]), true, $schedule->id));

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'event'     => CompanyScheduleRepository::EVENT_UPDATE_MARKET_DATA,
            'state'     => CompanyScheduleRepository::STATE_SUCCEEDED
        ]);
    }

    /** @test */
    public function it_should_update_an_existing_company_update_record_only_for_share_data()
    {
        $oldUpdatedAt = now()->subWeek();
        CompanyUpdate::factory()
            ->state([
                'ticker'                    => 'TST',
                'market_data_updated_at'    => $oldUpdatedAt,
                'financials_updated_at'     => $oldUpdatedAt,
            ])
            ->create();

        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_UPDATE_MARKET_DATA,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new MarketDataUpserted($this->createUpsertMarketDataContainer([
            'ticker'        => 'TST'
        ]), true, $schedule->id));

        $companyUpdate = CompanyUpdate::where('ticker', 'TST')
                            ->firstOrFail();

        $this->assertEquals(now()->format('Y-m-d H'), $companyUpdate->market_data_updated_at->format('Y-m-d H'));
        $this->assertEquals($oldUpdatedAt->format('Y-m-d H:i'), $companyUpdate->financials_updated_at->format('Y-m-d H:i'));
    }

    /** @test */
    public function it_should_create_a_new_company_update_record_if_somehow_does_not_exist_only_for_share_data()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_UPDATE_MARKET_DATA,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new MarketDataUpserted($this->createUpsertMarketDataContainer([
            'ticker'        => 'TST'
        ]), true, $schedule->id));

        $companyUpdate = CompanyUpdate::where('ticker', 'TST')
                            ->firstOrFail();

        $this->assertEquals(now()->format('Y-m-d H'), $companyUpdate->market_data_updated_at->format('Y-m-d H'));
        $this->assertNull($companyUpdate->financials_updated_at);
    }
}
