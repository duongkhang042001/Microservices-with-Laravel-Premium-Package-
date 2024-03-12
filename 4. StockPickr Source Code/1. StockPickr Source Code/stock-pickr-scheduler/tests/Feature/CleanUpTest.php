<?php

namespace Tests\Feature;

use App\Models\CompanySchedule;
use App\Repositories\CompanyScheduleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CleanUpTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_cleanup_stucked_schedules()
    {
        CompanySchedule::factory()
            ->state([
                'ticker'        => 'STUCK1',
                'state'         => CompanyScheduleRepository::STATE_IN_PROGRESS,
                'started_at'    => now()->subDay()
            ])
            ->create();

        CompanySchedule::factory()
            ->state([
                'ticker'        => 'STUCK2',
                'state'         => CompanyScheduleRepository::STATE_IN_PROGRESS,
                'started_at'    => now()->subDay()
            ])
            ->create();

        CompanySchedule::factory()
            ->state([
                'ticker'        => 'OK',
                'state'         => CompanyScheduleRepository::STATE_IN_PROGRESS,
                'started_at'    => now()->subMinutes(3)
            ])
            ->create();

        $this->artisan('cleanup');

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'STUCK1',
            'state'     => CompanyScheduleRepository::STATE_FAILED
        ]);
        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'STUCK2',
            'state'     => CompanyScheduleRepository::STATE_FAILED
        ]);
    }

    /** @test */
    public function it_should_not_cleanup_valid_in_progress_schedules()
    {
        CompanySchedule::factory()
            ->state([
                'ticker'        => 'OK',
                'state'         => CompanyScheduleRepository::STATE_IN_PROGRESS,
                'started_at'    => now()->subMinutes(3)
            ])
            ->create();

        $this->artisan('cleanup');

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'OK',
            'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
        ]);
    }
}
