<?php

use PHPUnit\Framework\TestCase;
use Mockery;

class ExampleTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testSuma()
    {
        $calculator = new Calculator();
        $this->assertEquals(4, $calculator->add(2, 2));
    }

    public function testResta()
    {
        $calculator = new Calculator();
        $this->assertEquals(5, $calculator->subtract(10, 5));
    }

    public function testMultiplicacion()
    {
        $calculator = new Calculator();
        $this->assertEquals(6, $calculator->multiply(2, 3));
    }

    public function testDivision()
    {
        $calculator = new Calculator();
        $this->assertEquals(2, $calculator->divide(4, 2));
    }

    public function testDivisionPorCero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $calculator = new Calculator();
        $calculator->divide(4, 0);
    }

    public function testValidarEmail()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValidEmail('test@example.com'));
        $this->assertFalse($validator->isValidEmail('invalid-email'));
    }

    public function testMockUsuarioRepository()
    {
        $mockRepository = Mockery::mock('UserRepository');
        $mockRepository->shouldReceive('findById')
            ->with(1)
            ->andReturn(['id' => 1, 'name' => 'John Doe']);

        $userService = new UserService($mockRepository);
        $user = $userService->getUserById(1);

        $this->assertEquals('John Doe', $user['name']);
    }

    public function testValidarPassword()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValidPassword('Strong123!'));
        $this->assertFalse($validator->isValidPassword('weak'));
    }

    public function testFormatearFecha()
    {
        $formatter = new DateFormatter();
        $date = '2023-12-25';
        $this->assertEquals('25/12/2023', $formatter->format($date));
    }

    public function testCalcularTotal()
    {
        $cart = new ShoppingCart();
        $cart->addItem(['price' => 10.00]);
        $cart->addItem(['price' => 20.00]);
        
        $this->assertEquals(30.00, $cart->getTotal());
    }
}
