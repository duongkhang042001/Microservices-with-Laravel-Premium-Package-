<?php

namespace Tests\Feature\Update;

use App\Events\Company\CompanyUpsertFailed;
use App\Models\CompanySchedule;
use App\Repositories\CompanyScheduleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyUpdateFailedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_set_schedule_to_failed()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_UPDATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpsertFailed($this->createCompanyUpsertFailedContainer('TST', 'Error while updating'), true, $schedule->id));

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'event'     => CompanyScheduleRepository::EVENT_UPDATE_COMPANY,
            'state'     => CompanyScheduleRepository::STATE_FAILED,
            'payload'   => 'Error while updating'
        ]);
    }

    /** @test */
    public function it_should_not_create_a_company_update_record()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_UPDATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpsertFailed($this->createCompanyUpsertFailedContainer('TST', 'Error while updating'), true, $schedule->id));

        $this->assertDatabaseCount('company_updates', 0);
    }
}
