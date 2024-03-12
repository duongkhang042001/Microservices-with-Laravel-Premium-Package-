<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Support\Collection;

class LeaderboardRepository
{
    /**
     * @return Collection<Company>
     */
    public function getLeaderboard(int $limit, int $offset): Collection
    {
        $columns = ['ticker', 'name', 'total_scores', 'total_score_percent', 'position'];
        return Company::select($columns)
            ->whereNotNull('position')
            ->orderBy('position')
            ->orderBy('ticker')
            ->limit($limit)
            ->offset($offset * $limit)
            ->get();
    }
}
