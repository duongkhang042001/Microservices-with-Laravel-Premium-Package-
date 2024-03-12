<?php

namespace Tests\Unit;

use App\Containers\MetricContainer;
use App\Enums\Rules;
use App\Repositories\MetricRepository;
use App\Rules\DownwardRule;
use App\Rules\RuleFactory;
use App\Rules\UpwardRule;
use InvalidArgumentException;
use Tests\TestCase;

class RuleFactoryTest extends TestCase
{
    private RuleFactory $factory;
    private MetricRepository $metrics;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = resolve(RuleFactory::class);
        $this->metrics = resolve(MetricRepository::class);
    }

    /** @test */
    public function it_should_create_a_upward_rule()
    {
        $rule = $this->factory->create($this->metrics->getAll()->firstWhere('slug', 'current_ratio'));
        $this->assertInstanceOf(UpwardRule::class, $rule);
    }

    /** @test */
    public function it_should_create_a_downward_rule()
    {
        $rule = $this->factory->create($this->metrics->getAll()->firstWhere('slug', 'debt_to_capital'));
        $this->assertInstanceOf(DownwardRule::class, $rule);
    }

    /** @test */
    public function it_should_throw_an_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->create(MetricContainer::from([
            'name' => 'Test',
            'slug' => 'test',
            'slugCamel' => 'test',
            'scoreRule' => 'INVALID',
            'formatter' => 'percent',
            'shouldHighlightNegativeValue' => false
        ]));
    }
}
