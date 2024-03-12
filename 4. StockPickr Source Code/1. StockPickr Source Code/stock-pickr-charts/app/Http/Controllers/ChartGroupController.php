<?php

namespace App\Http\Controllers;

use App\Exceptions\ChartGroupNotFoundException;
use StockPickr\Common\Services\CacheService;
use App\Services\ChartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ChartGroupController extends Controller
{
    public function __construct(
        private ChartService $chartService,
        private CacheService $cacheService
    ) {}

    public function get(string $ticker, string $group): JsonResponse
    {
        try {
            $key = "chart-group-$ticker-$group";
            $data = $this->cacheService->getOrRemember($key, fn () => $this->chartService->getChartGroup($ticker, $group));

            return response()->json([
                'data' => $data
            ]);
        } catch (ChartGroupNotFoundException $ex) {
            return response()->json(['errors' => ['chart-group' => $ex->getMessage()]], Response::HTTP_NOT_FOUND);
        }
    }
}
