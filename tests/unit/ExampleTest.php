<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;

class ExampleTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_basic_addition()
    {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }

    public function test_string_contains()
    {
        $string = "Hello World";
        $this->assertTrue(str_contains($string, "World"));
    }

    public function test_array_has_key()
    {
        $array = ['name' => 'John', 'age' => 30];
        $this->assertArrayHasKey('name', $array);
    }

    public function test_mock_example()
    {
        $mock = Mockery::mock('MyService');
        $mock->shouldReceive('doSomething')
             ->once()
             ->andReturn(true);

        $result = $mock->doSomething();
        $this->assertTrue($result);
    }
}
