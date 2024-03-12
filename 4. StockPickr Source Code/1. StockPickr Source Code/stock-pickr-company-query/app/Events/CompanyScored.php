<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\Score\CompanyScoredContainer;

class CompanyScored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public CompanyScoredContainer $data)
    {
    }
}
