<?php

namespace Tests\Feature\Score;

use App\Events\Score\ScoreCompaniesFailed;
use App\Models\CompanySchedule;
use App\Repositories\CompanyScheduleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoreCompaniesFailedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_set_schedule_to_succeeded()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_SCORE_COMPANIES,
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new ScoreCompaniesFailed($this->createScoreCompaniesFailedContainer(), $schedule->id));

        $this->assertDatabaseHas('company_schedules', [
            'id'        => $schedule->id,
            'state'     => CompanyScheduleRepository::STATE_FAILED
        ]);
    }

    /** @test */
    public function it_should_set_the_given_schedule_to_succeeded()
    {
        $schedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_SCORE_COMPANIES,
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        $otherSchedule = CompanySchedule::factory()
            ->state([
                'event'     => CompanyScheduleRepository::EVENT_SCORE_COMPANIES,
                'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        event(new ScoreCompaniesFailed($this->createScoreCompaniesFailedContainer(), $schedule->id));

        $this->assertDatabaseHas('company_schedules', [
            'id'        => $schedule->id,
            'state'     => CompanyScheduleRepository::STATE_FAILED
        ]);

        $this->assertDatabaseHas('company_schedules', [
            'id'        => $otherSchedule->id,
            'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
        ]);
    }
}
