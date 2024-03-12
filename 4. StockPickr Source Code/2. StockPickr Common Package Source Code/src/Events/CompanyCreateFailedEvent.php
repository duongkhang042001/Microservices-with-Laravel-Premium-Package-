<?php

namespace StockPickr\Common\Events;

use StockPickr\Common\Containers\CompanyUpsertFailedContainer;

class CompanyCreateFailedEvent extends Event
{
    public string $type = Events::COMPANY_CREATE_FAILED;
    public string $scheduleId;
    public CompanyUpsertFailedContainer $data;

    public function __construct(string $scheduleId, CompanyUpsertFailedContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;   
    }
}