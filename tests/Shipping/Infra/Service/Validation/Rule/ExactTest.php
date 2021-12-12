<?php

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use PHPUnit\Framework\TestCase;

class ExactTest extends TestCase
{
    private $field = 'FIELD';

    public function test_fail_should_return_true_with_minus()
    {
        $exact = new Exact($this->field, 'abcd', 3);
        $this->assertTrue( $exact->fail() );
    }

    public function test_fail_should_return_true_with_plus()
    {
        $exact = new Exact($this->field, 'abcd', 5);
        $this->assertTrue( $exact->fail() );
    }

    public function test_fail_should_return_false()
    {
        $exact = new Exact($this->field, 'abcd', 4);
        $this->assertFalse( $exact->fail() );
    }

    public function test_message_should_return_string()
    {
        $expected = $this->field . ' deve conter 3 caracteres.';
        $exact = new Exact($this->field, 'abcd', 3);
        $this->assertEquals( $expected, $exact->message() );
    }
}