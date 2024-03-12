<?php

namespace Tests\Feature\Create;

use App\Events\Company\CompanyUpsertFailed;
use App\Models\CompanySchedule;
use App\Models\CompanyUpdate;
use App\Repositories\CompanyScheduleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class CompanyCreateFailedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_set_schedule_to_failed()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpsertFailed($this->createCompanyUpsertFailedContainer('TST', 'Error while creating'), false, $schedule->id));

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'state'     => CompanyScheduleRepository::STATE_FAILED,
            'payload'   => 'Error while creating'
        ]);
    }

    /** @test */
    public function it_should_create_a_denied_company()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpsertFailed($this->createCompanyUpsertFailedContainer('TST', 'Error while creating'), false, $schedule->id));

        $this->assertDatabaseHas('denied_companies', [
            'ticker'    => 'TST',
            'reason'    => 'Error while creating'
        ]);
    }

    /** @test */
    public function it_should_create_two_denied_companies_if_ticker_has_dot()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
                'ticker'    => 'TST.L', // Mint pl AZN.L
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpsertFailed($this->createCompanyUpsertFailedContainer('TST.L', 'Error while creating'), false, $schedule->id));

        $this->assertDatabaseHas('denied_companies', [
            'ticker'    => 'TST',
            'reason'    => 'Error while creating'
        ]);
        $this->assertDatabaseHas('denied_companies', [
            'ticker'    => 'TST.L',
            'reason'    => 'Error while creating'
        ]);
    }

    /** @test */
    public function it_should_not_create_a_company_update_record()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
                'ticker'    => 'TST',
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new CompanyUpsertFailed($this->createCompanyUpsertFailedContainer('TST', 'Error while creating'), false, $schedule->id));

        $this->assertDatabaseCount('company_updates', 0);
    }

    /** @test */
    public function it_should_remove_company_updates_for_a_failed_company()
    {
        CompanyUpdate::factory(['ticker' => 'TST'])->create();
        $schedule = CompanySchedule::factory([
            'ticker' => 'TST',
            'event' => CompanyScheduleRepository::EVENT_CREATE_COMPANY
        ])->create();

        event(new CompanyUpsertFailed($this->createCompanyUpsertFailedContainer('TST', 'Error while creating'), false, $schedule->id));

        $this->assertDatabaseMissing('company_updates', [
            'ticker' => 'TST'
        ]);
    }

    /** @test */
    public function it_should_publish_remove_company_event()
    {
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishRemoveCompany')
                ->with('asdf');
        });

        $schedule = CompanySchedule::factory([
            'ticker' => 'TST',
            'event' => CompanyScheduleRepository::EVENT_CREATE_COMPANY
        ])->create();

        event(new CompanyUpsertFailed($this->createCompanyUpsertFailedContainer('TST', 'Error while creating'), false, $schedule->id));
    }
}
