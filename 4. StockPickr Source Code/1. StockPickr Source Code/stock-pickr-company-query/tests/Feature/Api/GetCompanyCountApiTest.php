<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class GetCompanyCountApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        $response = $this->json('GET', '/api/v1/queries/count');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_company_count()
    {
        foreach (range(1, 10) as $i) {
            Company::factory([
                'position' => $i
            ])
            ->create();
        }

        $count = $this->json('GET', '/api/v1/queries/count')->json('data');
        $this->assertEquals(10, $count);
    }

    /** @test */
    public function it_should_exclude_companies_with_null_position()
    {
        foreach (range(1, 5) as $i) {
            Company::factory([
                'position' => $i
            ])->create();
        }

        Company::factory([
            'position' => null
        ])
        ->count(3)
        ->create();

        $count = $this->json('GET', '/api/v1/queries/count')->json('data');
        $this->assertEquals(5, $count);
    }
}
