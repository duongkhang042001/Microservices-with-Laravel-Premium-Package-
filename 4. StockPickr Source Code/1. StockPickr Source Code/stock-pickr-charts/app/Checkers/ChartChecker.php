<?php

namespace App\Checkers;

use App\ChartConfigs\ChartFactory;
use App\Models\Chart;
use Illuminate\Support\Facades\DB;
use PragmaRX\Health\Checkers\Base;

class ChartChecker extends Base
{
    public function check()
    {
        $factory = resolve(ChartFactory::class);
        $chartCount = count($factory->createGroup('all'));

        $result = Chart::select(['ticker', DB::raw('count(*) as count')])
            ->groupBy('ticker')
            ->get();

        $tickersWithErrors = [];
        foreach ($result as $item) {
            if ($item->count !== $chartCount) {
                $tickersWithErrors[] = $item->ticker;
            }
        }

        return $this->makeResult(
            count($tickersWithErrors) === 0,
            count($tickersWithErrors) === 0 ? '' : "Tickers missing some charts: " . implode(' ', $tickersWithErrors)
        );
    }
}
