<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RemoveCompany
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $ticker)
    {
    }
}
