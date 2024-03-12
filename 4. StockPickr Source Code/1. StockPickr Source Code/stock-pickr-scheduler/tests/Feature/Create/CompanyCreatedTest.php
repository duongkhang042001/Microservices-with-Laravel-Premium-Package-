<?php

namespace Tests\Feature\Create;

use App\Events\Company\CompanyUpserted;
use App\Models\CompanySchedule;
use App\Models\CompanyUpdate;
use App\Repositories\CompanyScheduleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyCreatedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_set_schedule_to_succeeded()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpserted($this->createCompanyUpsertedContainer([
            'ticker' => 'TST'
        ]), false, $schedule->id));

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'state'     => CompanyScheduleRepository::STATE_SUCCEEDED
        ]);
    }

    /** @test */
    public function it_should_set_failed_schedule_to_succeeded()
    {
        /**
         * Testcase:
         * 1. Scheduler sent a company:create event for example
         * 2. Companies service failed to create
         * 3. Scheduler set to failed
         * 4. Companies service restarted, and is retrying the company:create event
         * 5. It succeeds and sends back a copmany:created event
         * 6. Scheduler has to query the failed schedule and set it to succeeded
         */
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_FAILED
            ])
            ->create();

        event(new CompanyUpserted($this->createCompanyUpsertedContainer([
            'ticker' => 'TST'
        ]), false, $schedule->id));

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'state'     => CompanyScheduleRepository::STATE_SUCCEEDED
        ]);
    }

    /** @test */
    public function it_should_create_a_company_update_record_with_financials()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpserted($this->createCompanyUpsertedContainer([
            'ticker' => 'TST'
        ]), false, $schedule->id));

        $companyUpdate = CompanyUpdate::where('ticker', 'TST')
                            ->firstOrFail();

        $this->assertEquals(now()->format('Y-m-d H'), $companyUpdate->financials_updated_at->format('Y-m-d H'));
    }

    /** @test */
    public function it_should_leave_market_data_updated_at_null()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpserted($this->createCompanyUpsertedContainer([
            'ticker' => 'TST'
        ]), false, $schedule->id));

        $companyUpdate = CompanyUpdate::where('ticker', 'TST')
                            ->firstOrFail();

        $this->assertNull($companyUpdate->market_data_updated_at);
    }
}
