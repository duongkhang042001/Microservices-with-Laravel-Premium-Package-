<?php

namespace StockPickr\Common\Events;

use StockPickr\Common\Containers\CompanyUpsertFailedContainer;

class CompanyUpdateFailedEvent extends Event
{
    public string $type = Events::COMPANY_UPDATE_FAILED;
    public string $scheduleId;
    public CompanyUpsertFailedContainer $data;

    public function __construct(string $scheduleId, CompanyUpsertFailedContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;   
    }
}