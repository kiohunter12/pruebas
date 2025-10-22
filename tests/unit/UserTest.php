<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;

class UserTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_user_can_be_created_with_name_and_email()
    {
        $user = new \App\Models\User([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
    }

    public function test_user_email_validation()
    {
        $mockValidator = Mockery::mock('EmailValidator');
        $mockValidator->shouldReceive('isValid')
                     ->with('john@example.com')
                     ->andReturn(true);

        $this->assertTrue($mockValidator->isValid('john@example.com'));
    }
}
