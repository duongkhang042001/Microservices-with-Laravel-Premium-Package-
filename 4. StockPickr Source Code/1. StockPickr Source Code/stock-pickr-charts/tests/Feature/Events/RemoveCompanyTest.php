<?php

namespace Tests\Feature\Events;

use App\Events\RemoveCompany;
use App\Models\Chart;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_remove_a_company()
    {
        Company::factory(['ticker' => 'TST'])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('companies', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_charts()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        Chart::factory(['ticker' => 'TST', 'company_id' => $company->id, 'chart' => 'revenue'])->create();
        Chart::factory(['ticker' => 'TST', 'company_id' => $company->id, 'chart' => 'cashFlow'])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('charts', 0);
    }
}
