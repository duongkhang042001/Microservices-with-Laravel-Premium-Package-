<?php

namespace Tests;

use App\Models\Company\Company;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use StockPickr\Common\Containers\UpsertCompanyContainer;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var Generator */
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    protected function tearDown(): void
    {
        Redis::flushall();
        parent::tearDown();
    }

    public function createDtoItems($values): Collection
    {
        if (!count($values)) {
            $values[] = 0;
        }

        return collect($values)
            ->map(function ($x, $i) {
                return (object)['value' => $x, 'year' => (2010 + $i)];
            });
    }

    protected function createCompanies(array $companies)
    {
        foreach ($companies as $company) {
            Company::factory()
                ->state($company)
                ->create();
        }
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    protected function createUpserCompanyContainer(array $data)
    {
        $mergedData = array_merge([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => 'Tech',
            'financialStatements' => [
                'incomeStatements'  => [],
                'balanceSheets'     => [],
                'cashFlows'         => []
            ]
        ], $data);

        return UpsertCompanyContainer::from($mergedData);
    }
}
