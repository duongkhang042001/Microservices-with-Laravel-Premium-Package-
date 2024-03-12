<?php

namespace App\Events\MarketData;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\MarketData\MarketDataUpsertFailedContainer;

class MarketDataUpsertFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public MarketDataUpsertFailedContainer $data,
        public bool $isUpdate,
        public string $scheduleId
    ) {}
}
