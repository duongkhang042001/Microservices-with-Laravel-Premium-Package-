<?php

namespace Tests\Feature\Events;

use App\Events\RemoveCompany;
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
}
