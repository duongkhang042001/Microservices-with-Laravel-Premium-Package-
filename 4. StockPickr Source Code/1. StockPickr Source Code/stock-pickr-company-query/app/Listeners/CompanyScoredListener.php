<?php

namespace App\Listeners;

use App\Events\CompanyScored;
use App\Repositories\ScoreQueueRepository;

class CompanyScoredListener
{
    public function __construct(
        private ScoreQueueRepository $scoreQueue
    ) {}

    public function handle(CompanyScored $event)
    {
        $this->scoreQueue->enqueue(json_encode($event->data));
    }
}
