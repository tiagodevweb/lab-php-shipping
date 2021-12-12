<?php

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use PHPUnit\Framework\TestCase;

class BetweenTest extends TestCase
{
    private $field = 'FIELD';

    public function test_fail_should_return_true_with_min()
    {
        $between = new Between($this->field, 'ab', 3, 10);
        $this->assertTrue( $between->fail() );
    }

    public function test_fail_should_return_true_with_max()
    {
        $between = new Between($this->field, 'abcdefghijk', 3, 10);
        $this->assertTrue( $between->fail() );
    }

    public function test_fail_should_return_false_with_min()
    {
        $between = new Between($this->field, 'abc', 3, 10);
        $this->assertFalse( $between->fail() );
    }

    public function test_fail_should_return_false_with_max()
    {
        $between = new Between($this->field, 'abcdefghij', 3, 10);
        $this->assertFalse( $between->fail() );
    }

    public function test_message_should_return_string()
    {
        $expected = $this->field . ' deve conter entre 3 e 10 caracteres.';
        $between = new Between($this->field, 'ab', 3, 10);
        $this->assertEquals( $expected, $between->message() );
    }
}