<?php

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use PHPUnit\Framework\TestCase;

class AlphaTest extends TestCase
{
    private $field = 'FIELD';

    public function test_fail_should_return_true_with_digit()
    {
        $alpha = new Alpha($this->field, 123);
        $this->assertTrue( $alpha->fail() );
    }

    public function test_fail_should_return_true_with_empty()
    {
        $alpha = new Alpha($this->field, '');
        $this->assertTrue( $alpha->fail() );
    }

    public function test_fail_should_return_true_with_null()
    {
        $alpha = new Alpha($this->field, null);
        $this->assertTrue( $alpha->fail() );
    }

    public function test_fail_should_return_false()
    {
        $alpha = new Alpha($this->field, 'abcd');
        $this->assertFalse( $alpha->fail() );
    }

    public function test_message_should_return_string()
    {
        $expected = $this->field . ' deve conter apenas letras.';
        $alpha = new Alpha($this->field, 123);
        $this->assertEquals( $expected, $alpha->message() );
    }
}