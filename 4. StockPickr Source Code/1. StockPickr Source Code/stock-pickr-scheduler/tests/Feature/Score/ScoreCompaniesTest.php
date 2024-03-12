<?php

namespace Tests\Feature\Score;

use App\Repositories\CompanyScheduleRepository;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class ScoreCompaniesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_company_schedule_and_publish_a_score_companies_event()
    {
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishScoreCompanies');
        });

        $this->artisan('company:score');

        $this->assertDatabaseHas('company_schedules', [
            'event'     => CompanyScheduleRepository::EVENT_SCORE_COMPANIES,
            'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
        ]);
    }
}
