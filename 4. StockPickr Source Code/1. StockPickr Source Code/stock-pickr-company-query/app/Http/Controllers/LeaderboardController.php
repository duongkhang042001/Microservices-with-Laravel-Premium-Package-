<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetLeaderboardRequest;
use App\Http\Resources\CompanyResource;
use App\Services\LeaderboardService;
use StockPickr\Common\Services\CacheService;

class LeaderboardController extends Controller
{
    public function __construct(
        private LeaderboardService $leaderboardService,
        private CacheService $cacheService
    ) {}

    public function get(GetLeaderboardRequest $request)
    {
        $limit = $request->getLimit();
        $offset = $request->getOffset();

        return $this->cacheService->getOrRemember(
            "leaderboard-$limit-$offset",
            function () use ($limit, $offset) {
                $leaderboard = $this->leaderboardService->getLeaderboard(
                    $limit,
                    $offset
                );

                return [
                    'data' => CompanyResource::collection($leaderboard)
                ];
            });
    }
}
