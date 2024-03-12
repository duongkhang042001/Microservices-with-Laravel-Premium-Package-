<?php

namespace Tests\Feature\Rules;

use App\Enums\Scores;
use App\Events\ScoreCompanies;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DownwardScoringTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_score_to_A()
    {
        $company1 = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'debt_to_capital' => 0.1
        ], $company1);
        $this->createCompanyMetric([
            'debt_to_capital' => 0.4
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'debt_to_capital' => Scores::A
        ]);
    }

    /** @test */
    public function it_should_score_to_B()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'debt_to_capital' => 1.10
        ], $company);
        $this->createCompanyMetric([
            'debt_to_capital' => 1
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'debt_to_capital' => Scores::B
        ]);
    }

    /** @test */
    public function it_should_score_to_C()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'debt_to_capital' => 1.15
        ], $company);
        $this->createCompanyMetric([
            'debt_to_capital' => 0.84
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'debt_to_capital' => Scores::C
        ]);
    }

    /** @test */
    public function it_should_score_to_D()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'debt_to_capital' => 2
        ], $company);
        $this->createCompanyMetric([
            'debt_to_capital' => 1
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'debt_to_capital' => Scores::D
        ]);
    }

    /** @test */
    public function it_should_score_to_A_if_own_0()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'debt_to_capital' => 0
        ], $company);
        $this->createCompanyMetric([
            'debt_to_capital' => 1
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'debt_to_capital' => Scores::A
        ]);
    }

    /** @test */
    public function it_scores_to_D_if_others_zero_but_own_not_zero()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();
        $company3 = Company::factory(['ticker' => 'TST3'])->create();

        $this->createCompanyMetric([
            'interest_to_operating_profit' => 1
        ], $company);
        $this->createCompanyMetric([
            'interest_to_operating_profit' => 0
        ], $company2);
        $this->createCompanyMetric([
            'interest_to_operating_profit' => 0
        ], $company3);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'interest_to_operating_profit' => Scores::D
        ]);
    }

    /** @test */
    public function it_scores_to_B_if_both_zero()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetric([
            'interest_to_operating_profit' => 0
        ], $company);
        $this->createCompanyMetric([
            'interest_to_operating_profit' => 0
        ], $company2);

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST',
            'interest_to_operating_profit' => Scores::B
        ]);
    }
}
