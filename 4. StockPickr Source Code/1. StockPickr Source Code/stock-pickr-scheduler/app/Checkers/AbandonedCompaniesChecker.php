<?php

namespace App\Checkers;

use App\Models\CompanyUpdate;
use Carbon\Carbon;
use PragmaRX\Health\Checkers\Base;

class AbandonedCompaniesChecker extends Base
{
    public function check()
    {
        $tresholdDateForMarketData = Carbon::now()->subWeek();
        $tresholdDateForFinancials = Carbon::now()->subMonths(6);

        $tickers = CompanyUpdate::select('ticker')
            ->where('market_data_updated_at', '<=', $tresholdDateForMarketData)
            ->orWhere('financials_updated_at', '<=', $tresholdDateForFinancials)
            ->get()
            ->pluck('ticker');

        return $this->makeResult(
            $tickers->count() === 0,
            $tickers->count() === 0 ? '' : 'There are abandoned companies without updates: ' . $tickers->implode(' ')
        );
    }
}
