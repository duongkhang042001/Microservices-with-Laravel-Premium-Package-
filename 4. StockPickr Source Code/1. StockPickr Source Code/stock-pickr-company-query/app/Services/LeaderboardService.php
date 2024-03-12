<?php

namespace App\Services;

use App\Repositories\LeaderboardRepository;
use Illuminate\Support\Collection;
use App\Models\Company;

class LeaderboardService
{
    public function __construct(private LeaderboardRepository $leaderboard)
    {
    }

    /**
     * @return Collection<Company>
     */
    public function getLeaderboard(int $limit, int $offset): Collection
    {
        return $this->leaderboard->getLeaderboard($limit, $offset);
    }
}
