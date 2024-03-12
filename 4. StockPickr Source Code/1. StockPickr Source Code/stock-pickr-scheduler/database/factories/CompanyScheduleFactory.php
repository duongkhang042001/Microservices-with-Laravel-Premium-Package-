<?php

namespace Database\Factories;

use App\Models\CompanySchedule;
use App\Repositories\CompanyScheduleRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyScheduleFactory extends Factory
{
    protected $model = CompanySchedule::class;

    public function definition()
    {
        $states = [CompanyScheduleRepository::STATE_IN_PROGRESS, CompanyScheduleRepository::STATE_SUCCEEDED, CompanyScheduleRepository::STATE_FAILED];
        $events = [CompanyScheduleRepository::EVENT_CREATE_COMPANY];

        $state = collect($states)->random();

        return [
            'event'         => collect($events)->random(),
            'ticker'        => Str::upper(Str::random(3)) . rand(100, 999),
            'state'         => $state,
            'started_at'    => now()->subMinutes(10),
            'finished_at'   => $state === CompanyScheduleRepository::STATE_IN_PROGRESS ? null : now()
        ];
    }
}
