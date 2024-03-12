<?php

namespace Tests\Feature\Events;

use App\Events\CompanyScored;
use App\Models\Company;
use App\Repositories\ScoreQueueRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use StockPickr\Common\Containers\Score\CompanyScoredContainer;

class CompanyScoredTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_enqueue_company()
    {
        Company::factory(['ticker' => 'TST', 'sector' => 'Tech'])->create();
        event(new CompanyScored(CompanyScoredContainer::fromJsonArray([
            'ticker' => 'TST',
            'totalScores' => 76,
            'totalScorePercent' => 0.754,
            'totalSectorScores' => 70,
            'totalSectorScorePercent' => 0.694,
            'position' => 8,
            'positionPercentile' => 0.63,
        ])));

        /** @var ScoreQueueRepository $scoreQueue */
        $scoreQueue = app(ScoreQueueRepository::class);
        $json = $scoreQueue->dequeue();
        $container = CompanyScoredContainer::fromJsonArray(json_decode($json, true));

        $this->assertEquals('TST', $container->ticker);
        $this->assertEquals('0.754', $container->totalScorePercent);
        $this->assertEquals(8, $container->position);
    }
}
