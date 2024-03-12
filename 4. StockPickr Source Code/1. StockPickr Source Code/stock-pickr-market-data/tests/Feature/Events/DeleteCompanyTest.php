<?php

namespace Tests\Feature\Events;

use App\Events\DeleteCompany;
use App\Models\Analyst;
use App\Models\ShareData;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use StockPickr\Common\Containers\CompanyUpsertFailedContainer;

class DeleteCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_delete_analyst_if_company_create_failed()
    {
        Analyst::factory()->state(['ticker' => 'TST'])->create();

        event(new DeleteCompany('TST'));

        $this->assertDatabaseCount('analysts', 0);
    }

    /** @test */
    public function it_should_delete_share_data_if_company_create_failed()
    {
        ShareData::factory()->state(['ticker' => 'TST'])->create();

        event(new DeleteCompany('TST'));

        $this->assertDatabaseCount('share_data', 0);
    }
}
