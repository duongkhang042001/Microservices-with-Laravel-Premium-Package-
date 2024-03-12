<?php

namespace StockPickr\Common\Events\MarketData;

use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

class CreateMarketDataEvent extends Event
{
    public string $type = Events::CREATE_MARKET_DATA;
    public string $scheduleId;
    public UpsertMarketDataContainer $data;

    public function __construct(string $scheduleId, UpsertMarketDataContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;
    }
}