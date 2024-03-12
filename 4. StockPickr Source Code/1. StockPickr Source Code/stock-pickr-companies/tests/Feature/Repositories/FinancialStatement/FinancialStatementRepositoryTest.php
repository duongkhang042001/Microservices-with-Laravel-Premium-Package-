<?php


namespace Tests\Feature\Repositories\FinancialStatement;


use App\Models\Company\Company;
use App\Models\FinancialStatement\IncomeStatement;
use App\Repositories\FinancialStatement\IncomeStatementRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialStatementRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var IncomeStatementRepository */
    private $statements;

    protected function setUp(): void
    {
        parent::setUp();
        $this->statements = resolve(IncomeStatementRepository::class);
    }

    /** @test */
    public function it_can_return_all_years_where_a_company_has_financial_statement()
    {
        $company = Company::factory()->create();

        IncomeStatement::factory()
            ->count(3)
            ->state(new Sequence(
                ['year' => 2020, 'ticker' => $company->ticker, 'company_id' => $company->id],
                ['year' => 2019, 'ticker' => $company->ticker, 'company_id' => $company->id],
                ['year' => 2018, 'ticker' => $company->ticker, 'company_id' => $company->id],
            ))
            ->create();

        $years = $this->statements->findYearsWhereHasData($company);
        $this->assertEquals([2020, 2019, 2018], $years);
    }
}
