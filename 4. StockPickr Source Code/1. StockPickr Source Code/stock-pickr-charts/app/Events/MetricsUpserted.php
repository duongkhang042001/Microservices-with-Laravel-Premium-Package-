<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\Metrics\MetricsUpsertedContainer;

class MetricsUpserted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public MetricsUpsertedContainer $data)
    {
    }
}
