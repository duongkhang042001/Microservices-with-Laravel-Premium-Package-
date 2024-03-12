<?php

namespace StockPickr\Common\Events\Score;

use StockPickr\Common\Containers\Score\CompanyScoredContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

final class CompanyScoredEvent extends Event
{
    public string $type = Events::COMPANY_SCORED;
    public CompanyScoredContainer $data;

    public function __construct(CompanyScoredContainer $data)
    {
        $this->data = $data;
    }
}