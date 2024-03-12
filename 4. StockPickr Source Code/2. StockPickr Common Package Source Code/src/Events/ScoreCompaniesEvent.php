<?php

namespace StockPickr\Common\Events;

class ScoreCompaniesEvent extends Event
{
    public string $type = Events::SCORE_COMPANIES;
    public string $scheduleId;

    public function __construct(string $scheduleId)
    {
        $this->scheduleId = $scheduleId;
    }
}