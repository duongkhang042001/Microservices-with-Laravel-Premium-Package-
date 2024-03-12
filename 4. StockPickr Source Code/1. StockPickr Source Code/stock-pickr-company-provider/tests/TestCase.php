<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use StockPickr\Common\Containers\UpsertCompanyContainer;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    protected function createUpsertCompanyContainer(array $data)
    {
        $container = UpsertCompanyContainer::from(array_merge([
            'ticker'        => 'TST',
            'name'          => 'Test Inc.',
            'shareData'     => [],
            'analyst'       => [],
            'peers'         => [],
            'financialStatements' => [
                'incomeStatements' => Arr::get($data, 'incomeStatements', []),
                'balanceSheets'    => Arr::get($data, 'balanceSheets', []),
                'cashFlows'        => Arr::get($data, 'cashFlows', [])
            ]
        ], $data));

        return $container;
    }
}
