<?php

namespace Tests\Feature;

use App\Models\CompanySchedule;
use App\Repositories\CompanyScheduleRepository;
use App\Services\SchedulerService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchedulerServiceTest extends TestCase
{
    use RefreshDatabase;

    private SchedulerService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(SchedulerService::class);
    }

    /** @test */
    public function it_should_not_start_a_command_if_a_schedule_is_in_progress()
    {
        CompanySchedule::factory()
            ->state([
                'state' => CompanyScheduleRepository::STATE_IN_PROGRESS
            ])
            ->create();

        $this->assertFalse($this->service->canStartScheduledCommand());
    }

    /** @test */
    public function it_should_start_a_command_if_there_is_no_in_progress_schedules()
    {
        CompanySchedule::factory()
            ->state([
                'state' => CompanyScheduleRepository::STATE_SUCCEEDED
            ])
            ->create();

        $this->assertTrue($this->service->canStartScheduledCommand());
    }
}
