<?php

namespace App\Listeners;

use App\Events\DeleteCompany;
use App\Services\AnalystService;
use App\Services\ShareDataService;
use Illuminate\Support\Facades\DB;

class DeleteCompanyListener
{
    public function __construct(
        private ShareDataService $shareDataService,
        private AnalystService $analystService
    ) {}

    public function handle(DeleteCompany $event): void
    {
        DB::transaction(function () use ($event) {
            $this->shareDataService->delete($event->ticker);
            $this->analystService->delete($event->ticker);
        });
    }
}
