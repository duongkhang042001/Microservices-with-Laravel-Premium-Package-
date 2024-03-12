<?php

namespace StockPickr\Common\Events;

use StockPickr\Common\Containers\ScoreCompaniesFailedContainer;

class ScoreCompaniesFailedEvent extends Event
{
    public string $type = Events::COMPANY_SCORE_FAILED;
    public string $scheduleId;
    public ScoreCompaniesFailedContainer $data;

    public function __construct(string $scheduleId, ScoreCompaniesFailedContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;
    }
}