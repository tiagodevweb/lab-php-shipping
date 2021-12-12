<?php

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use PHPUnit\Framework\TestCase;

class RegexTest extends TestCase
{
    private $field = 'FIELD';
    private $expression = '[0-9]*.?[0-9]{1,3}';

    public function test_fail_should_return_true_with_string()
    {
        $regex = new Regex($this->field, 'abc', $this->expression);
        $this->assertTrue( $regex->fail() );
    }

    public function test_fail_should_return_true_with_decimal_4()
    {
        $regex = new Regex($this->field, 1.1234, $this->expression);
        $this->assertTrue( $regex->fail() );
    }

    public function test_fail_should_return_false_with_decimal_3()
    {
        $regex = new Regex($this->field, 1.123, $this->expression);
        $this->assertFalse( $regex->fail() );
    }

    public function test_fail_should_return_false_with_decimal_2()
    {
        $regex = new Regex($this->field, 1.12, $this->expression);
        $this->assertFalse( $regex->fail() );
    }

    public function test_fail_should_return_false_with_decimal_1()
    {
        $regex = new Regex($this->field, 1.1, $this->expression);
        $this->assertFalse( $regex->fail() );
    }

    public function test_fail_should_return_false_with_int()
    {
        $regex = new Regex($this->field, 1, $this->expression);
        $this->assertFalse( $regex->fail() );
    }

    public function test_message_should_return_string()
    {
        $expected = $this->field . ' não contém um formato válido.';
        $regex = new Regex($this->field, 'abc', $this->expression);
        $this->assertEquals( $expected, $regex->message() );
    }

    public function test_message_should_return_string_custom()
    {
        $expected = $this->field . ' não contém um formato válido. Ex.: 1.250';
        $regex = new Regex($this->field, 'abc', $this->expression, ' Ex.: 1.250');
        $this->assertEquals( $expected, $regex->message() );
    }
}