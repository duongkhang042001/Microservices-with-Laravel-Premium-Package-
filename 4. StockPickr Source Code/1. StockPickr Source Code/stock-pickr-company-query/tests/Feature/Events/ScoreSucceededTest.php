<?php

namespace Tests\Feature\Events;

use App\Events\CompanyScored;
use App\Events\ScoreSucceeded;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use StockPickr\Common\Containers\Score\CompanyScoredContainer;

class ScoreSucceededTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_update_companies()
    {
        Company::factory(['ticker' => 'TST1', 'sector' => 'Tech'])->create();
        Company::factory(['ticker' => 'TST2', 'sector' => 'Tech'])->create();
        Company::factory(['ticker' => 'TST3', 'sector' => 'Tech'])->create();

        event(new CompanyScored(CompanyScoredContainer::fromJsonArray([
            'ticker' => 'TST1',
            'totalScores' => 76,
            'totalScorePercent' => 0.754,
            'totalSectorScores' => 70,
            'totalSectorScorePercent' => 0.694,
            'position' => 1,
            'positionPercentile' => 0.63,
        ])));
        event(new CompanyScored(CompanyScoredContainer::fromJsonArray([
            'ticker' => 'TST2',
            'totalScores' => 76,
            'totalScorePercent' => 0.754,
            'totalSectorScores' => 70,
            'totalSectorScorePercent' => 0.694,
            'position' => 2,
            'positionPercentile' => 0.63,
        ])));
        event(new CompanyScored(CompanyScoredContainer::fromJsonArray([
            'ticker' => 'TST3',
            'totalScores' => 76,
            'totalScorePercent' => 0.754,
            'totalSectorScores' => 70,
            'totalSectorScorePercent' => 0.694,
            'position' => 3,
            'positionPercentile' => 0.63,
        ])));

        event(new ScoreSucceeded());

        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST1',
            'position' => 1
        ]);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST2',
            'position' => 2
        ]);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST3',
            'position' => 3
        ]);
    }

    /** @test */
    public function it_should_flush_cache()
    {
        Cache::shouldReceive('flush');
        event(new ScoreSucceeded());
    }
}
