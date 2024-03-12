<?php

namespace App\Events\Metrics;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\Metrics\MetricsUpsertFailedContainer;

class MetricsCreateFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public MetricsUpsertFailedContainer $data
    ) {}
}
