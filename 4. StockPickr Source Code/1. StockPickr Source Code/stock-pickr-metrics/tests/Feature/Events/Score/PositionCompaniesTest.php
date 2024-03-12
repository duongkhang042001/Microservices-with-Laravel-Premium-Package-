<?php

namespace Tests\Feature\Events\Score;

use App\Enums\Scores;
use App\Events\ScoreCompanies;
use App\Models\Company;
use App\Models\CompanyScore;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PositionCompaniesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_position_existing_companies()
    {
        $company1 = Company::factory(['ticker' => 'TST1'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        $this->createCompanyMetricWithFixValue(
            'TST1',
            [
                'debt_to_capital' => 0.2,
                'current_ratio' => 2,
            ],
            $company1
        );

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

        $this->createCompanyMetricWithFixValue(
            'TST2',
            [
                'debt_to_capital' => 0.8,
                'current_ratio' => 0.5,
            ],
            $company2
        );

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST1',
            'position' => 1,
        ]);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST2',
            'position' => 2,
        ]);
    }

    /** @test */
    public function it_should_position_new_companies()
    {
        $company1 = Company::factory(['ticker' => 'TST1'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();
        $company3 = Company::factory(['ticker' => 'TST3'])->create();

        $this->createCompanyMetricWithFixValue(
            'TST1',
            [
                'debt_to_capital' => 0.1,
                'current_ratio' => 10,
            ],
            $company1
        );

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

            $this->createCompanyMetricWithFixValue(
                'TST2',
                [
                    'debt_to_capital' => 0.5,
                    'current_ratio' => 1,
                ],
                $company2
            );

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

            $this->createCompanyMetricWithFixValue(
                'TST3',
                [
                    'debt_to_capital' => 2,
                    'current_ratio' => 0.1,
                ],
                $company3
            );

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST1',
            'position' => 1,
        ]);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST2',
            'position' => 2,
        ]);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST3',
            'position' => 3,
        ]);
    }

    /** @test */
    public function it_should_calculate_position_percentile()
    {
        $company1 = Company::factory(['ticker' => 'TST1'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();
        $company3 = Company::factory(['ticker' => 'TST3'])->create();

        $this->createCompanyMetricWithFixValue(
            'TST1',
            [
                'debt_to_capital' => 0.1,
                'current_ratio' => 10,
            ],
            $company1
        );

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

            $this->createCompanyMetricWithFixValue(
                'TST2',
                [
                    'debt_to_capital' => 0.5,
                    'current_ratio' => 1,
                ],
                $company2
            );

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

            $this->createCompanyMetricWithFixValue(
                'TST3',
                [
                    'debt_to_capital' => 2,
                    'current_ratio' => 0.1,
                ],
                $company3
            );

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST1',
            'position_percentile' => 0.33333333,
        ]);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST2',
            'position_percentile' => 0.66666667,
        ]);
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST3',
            'position_percentile' => 1,
        ]);
    }
}
