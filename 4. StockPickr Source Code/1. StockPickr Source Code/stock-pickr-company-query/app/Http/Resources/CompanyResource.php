<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use StockPickr\Common\Formatters\Percent;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'ticker' => $this->ticker,
            'name' => $this->name,
            'fullName' => $this->full_name,
            'totalScores' => (int) $this->total_scores,
            'totalScorePercent' => Percent::create(['decimals' => 0])->format($this->total_score_percent),
            'position' => (int) $this->position
        ];
    }
}
