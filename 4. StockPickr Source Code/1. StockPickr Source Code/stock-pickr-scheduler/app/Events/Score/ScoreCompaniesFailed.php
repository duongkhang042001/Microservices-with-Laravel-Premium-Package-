<?php

namespace App\Events\Score;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\ScoreCompaniesFailedContainer;

class ScoreCompaniesFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ScoreCompaniesFailedContainer $data, public string $scheduleId)
    {
    }
}
