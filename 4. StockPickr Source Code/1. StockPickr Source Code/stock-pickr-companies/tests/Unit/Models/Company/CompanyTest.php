<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Company;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    /** @test */
    public function it_can_return_the_full_name()
    {
        $company = new Company;
        $company->ticker = 'TEST';
        $company->name = 'Company Inc.';

        $this->assertEquals('TEST - Company Inc.', $company->full_name);
    }

    /** @test */
    public function it_can_return_employees_formatted()
    {
        $company = new Company;
        $company->employees = 15772;

        $this->assertEquals('15,772', $company->employees_formatted);
    }

    /** @test */
    public function it_has_ticker_as_route_key()
    {
        $company = new Company;
        $this->assertEquals('ticker', $company->getRouteKeyName());
    }

    /** @test */
    public function it_has_income_statements()
    {
        $company = new Company;

        $this->assertInstanceOf(Collection::class, $company->income_statements);
    }

    /** @test */
    public function it_has_balance_sheets()
    {
        $company = new Company;

        $this->assertInstanceOf(Collection::class, $company->balance_sheets);
    }

    /** @test */
    public function it_has_cash_flows()
    {
        $company = new Company;

        $this->assertInstanceOf(Collection::class, $company->cash_flows);
    }
}
