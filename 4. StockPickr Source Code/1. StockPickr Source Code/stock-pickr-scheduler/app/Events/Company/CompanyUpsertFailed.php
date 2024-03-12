<?php

namespace App\Events\Company;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\CompanyUpsertFailedContainer;

class CompanyUpsertFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public CompanyUpsertFailedContainer $data,
        public bool $isUpdate,
        public string $scheduleId
    ) {}
}
