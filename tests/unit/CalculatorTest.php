<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;

class CalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new \App\Services\Calculator();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_add_two_numbers()
    {
        $result = $this->calculator->add(5, 3);
        $this->assertEquals(8, $result);
    }

    public function test_can_subtract_two_numbers()
    {
        $result = $this->calculator->subtract(10, 4);
        $this->assertEquals(6, $result);
    }

    public function test_can_multiply_two_numbers()
    {
        $result = $this->calculator->multiply(3, 4);
        $this->assertEquals(12, $result);
    }

    public function test_can_divide_two_numbers()
    {
        $result = $this->calculator->divide(10, 2);
        $this->assertEquals(5, $result);
    }

    public function test_throws_exception_when_dividing_by_zero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calculator->divide(10, 0);
    }
}
