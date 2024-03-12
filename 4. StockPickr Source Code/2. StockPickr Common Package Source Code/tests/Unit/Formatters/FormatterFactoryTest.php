<?php

namespace Tests\Unit\Formatters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use StockPickr\Common\Formatters\FinancialNumber;
use StockPickr\Common\Formatters\Formatter;
use StockPickr\Common\Formatters\FormatterFactory;
use StockPickr\Common\Formatters\Money;
use StockPickr\Common\Formatters\Number;
use StockPickr\Common\Formatters\Percent;

class FormatterFactoryTest extends TestCase
{
    private FormatterFactory $factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = new FormatterFactory();
    }

    /** @test */
    public function it_should_create_a_money_formatter()
    {
        $this->assertInstanceOf(
            Money::class, $this->factory->create(Formatter::MONEY));
    }

    /** @test */
    public function it_should_create_a_number_formatter()
    {
        $this->assertInstanceOf(
            Number::class, $this->factory->create(Formatter::NUMBER));
    }

    /** @test */
    public function it_should_create_a_percent_formatter()
    {
        $this->assertInstanceOf(
            Percent::class, $this->factory->create(Formatter::PERCENT));
    }

    /** @test */
    public function it_should_create_a_financial_number_formatter()
    {
        $this->assertInstanceOf(
            FinancialNumber::class, $this->factory->create(Formatter::FINANCIAL_NUMBER));
    }

    /** @test */
    public function it_should_throw_an_exception_if_invalid_type_given()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->create('invalidType');
    }
}