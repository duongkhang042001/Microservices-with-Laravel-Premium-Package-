<?php

namespace StockPickr\Common\Events\MarketData;

use StockPickr\Common\Containers\MarketData\MarketDataUpsertFailedContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

class MarketDataUpdateFailedEvent extends Event
{
    public string $type = Events::MARKET_DATA_UPDATE_FAILED;
    public string $scheduleId;
    public MarketDataUpsertFailedContainer $data;

    public function __construct(string $scheduleId, MarketDataUpsertFailedContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;
    }
}