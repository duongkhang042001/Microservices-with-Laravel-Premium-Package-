<?php

namespace App\Events\Charts;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\Charts\ChartsUpsertFailedContainer;

class ChartsCreateFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ChartsUpsertFailedContainer $data
    ) {}
}
