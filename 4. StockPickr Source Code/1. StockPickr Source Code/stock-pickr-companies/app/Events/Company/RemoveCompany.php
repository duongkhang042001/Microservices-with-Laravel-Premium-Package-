<?php

namespace App\Events\Company;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\UpsertCompanyContainer;

class RemoveCompany
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $ticker
    ) {}
}
