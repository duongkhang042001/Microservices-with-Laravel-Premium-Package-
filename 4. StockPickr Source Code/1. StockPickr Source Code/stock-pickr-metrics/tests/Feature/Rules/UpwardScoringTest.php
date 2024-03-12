<?php

namespace Tests\Feature\Rules;

use App\Enums\Scores;
use App\Events\ScoreCompanies;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpwardScoringTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_score_to_A()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'current_ratio' => 4
        ], $company);
        $this->createCompanyMetric([
            'current_ratio' => 1
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'current_ratio' => Scores::A
        ]);
    }

    /** @test */
    public function it_should_score_to_B()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'current_ratio' => 1.10
        ], $company);
        $this->createCompanyMetric([
            'current_ratio' => 1
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'current_ratio' => Scores::B
        ]);
    }

    /** @test */
    public function it_should_score_to_C()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'current_ratio' => 0.75
        ], $company);
        $this->createCompanyMetric([
            'current_ratio' => 1
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'current_ratio' => Scores::C
        ]);
    }

    /** @test */
    public function it_should_score_to_D()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'current_ratio' => 1
        ], $company);
        $this->createCompanyMetric([
            'current_ratio' => 2
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'current_ratio' => Scores::D
        ]);
    }

    // -------- Edge cases --------

    /** @test */
    public function it_should_score_to_D_if_own_negative()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();
        $company3 = Company::factory(['ticker' => 'TST3'])->create();

        $this->createCompanyMetric([
            'eps_growth' => -2
        ], $company);
        $this->createCompanyMetric([
            'eps_growth' => 1
        ], $company2);
        $this->createCompanyMetric([
            'eps_growth' => 5
        ], $company3);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'eps_growth' => Scores::D
        ]);
    }

    /** @test */
    public function it_should_score_to_A_if_others_negative()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'eps_growth' => 1
        ], $company);
        $this->createCompanyMetric([
            'eps_growth' => -2
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'eps_growth' => Scores::A
        ]);
    }

    /** @test */
    public function it_should_score_to_B_if_both_zero()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'eps_growth' => 0
        ], $company);
        $this->createCompanyMetric([
            'eps_growth' => 0
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'eps_growth' => Scores::B
        ]);
    }

    /** @test */
    public function it_should_score_to_A_if_others_zero()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();
        $company3 = Company::factory(['ticker' => 'TST3'])->create();

        $this->createCompanyMetric([
            'eps_growth' => 1
        ], $company);
        $this->createCompanyMetric([
            'eps_growth' => 0
        ], $company2);
        $this->createCompanyMetric([
            'eps_growth' => 0
        ], $company3);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'eps_growth' => Scores::A
        ]);
    }
}
