<?php

namespace StockPickr\Common\Events;

class ScoreCompaniesSucceededEvent extends Event
{
    public string $type = Events::COMPANY_SCORE_SUCCEEDED;

    public function __construct(
        public string $scheduleId
    ) {}
}