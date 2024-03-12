<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;

class UpsertMarketData
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public UpsertMarketDataContainer $data,
        public bool $isUpdate,
        public string $scheduleId
    ) {}
}
