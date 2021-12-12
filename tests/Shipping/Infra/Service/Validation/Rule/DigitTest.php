<?php

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use PHPUnit\Framework\TestCase;

class DigitTest extends TestCase
{
    private $field = 'FIELD';

    public function test_fail_should_return_true_with_digit()
    {
        $digit = new Digit($this->field, 'abcd');
        $this->assertTrue( $digit->fail() );
    }

    public function test_fail_should_return_true_with_empty()
    {
        $digit = new Digit($this->field, '');
        $this->assertTrue( $digit->fail() );
    }

    public function test_fail_should_return_true_with_null()
    {
        $digit = new Digit($this->field, null);
        $this->assertTrue( $digit->fail() );
    }

    public function test_fail_should_return_true_with_float()
    {
        $digit = new Digit($this->field, 1.5);
        $this->assertTrue( $digit->fail() );
    }

    public function test_fail_should_return_false_with_int()
    {
        $digit = new Digit($this->field, 123);
        $this->assertFalse( $digit->fail() );
    }

    public function test_fail_should_return_false_with_string_number()
    {
        $digit = new Digit($this->field, '123');
        $this->assertFalse( $digit->fail() );
    }

    public function test_message_should_return_string()
    {
        $expected = $this->field . ' deve conter apenas numeros.';
        $digit = new Digit($this->field, 'abcd');
        $this->assertEquals( $expected, $digit->message() );
    }
}