<?php

namespace App\Events\Score;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreCompaniesSucceeded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $scheduleId)
    {
    }
}
