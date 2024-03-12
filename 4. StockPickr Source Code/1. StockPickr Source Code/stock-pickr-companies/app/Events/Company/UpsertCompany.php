<?php

namespace App\Events\Company;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\UpsertCompanyContainer;

class UpsertCompany
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public UpsertCompanyContainer $company,
        public bool $isUpdate,
        public string $scheduleId
    ) {}
}
