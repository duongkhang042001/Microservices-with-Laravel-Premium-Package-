<?php

namespace StockPickr\Common\Containers\Score;

use StockPickr\Common\Containers\Container;

final class CompanyScoredContainer extends Container
{
    public string $ticker;
    public int $totalScores;
    public float $totalScorePercent;
    public int $totalSectorScores;
    public float $totalSectorScorePercent;
    public int $position;
    public float $positionPercentile;

    public static function from(array $data): static
    {
        $container = new static();
        $container->ticker = $data['ticker'];
        $container->totalScores = $data['total_scores'];
        $container->totalScorePercent = $data['total_score_percent'];
        $container->totalSectorScores = $data['total_sector_scores'];
        $container->totalSectorScorePercent = $data['total_sector_score_percent'];
        $container->position = $data['position'];
        $container->positionPercentile = $data['position_percentile'];

        return $container;
    }

    public static function fromJsonArray(array $data): static
    {
        $container = new static();
        $container->ticker = $data['ticker'];
        $container->totalScores = $data['totalScores'];
        $container->totalScorePercent = $data['totalScorePercent'];
        $container->totalSectorScores = $data['totalSectorScores'];
        $container->totalSectorScorePercent = $data['totalSectorScorePercent'];
        $container->position = $data['position'];
        $container->positionPercentile = $data['positionPercentile'];

        return $container;
    }
}