<?php

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use PHPUnit\Framework\TestCase;

class RequiredTest extends TestCase
{
    private $field = 'FIELD';

    public function test_fail_should_return_true_with_empty()
    {
        $required = new Required($this->field, '');
        $this->assertTrue( $required->fail() );
    }

    public function test_fail_should_return_true_with_null()
    {
        $required = new Required($this->field, null);
        $this->assertTrue( $required->fail() );
    }

    public function test_fail_should_return_false()
    {
        $required = new Required($this->field, 'abcd');
        $this->assertFalse( $required->fail() );
    }

    public function test_message_should_return_string()
    {
        $expected = $this->field . ' é requerido.';
        $required = new Required($this->field, '');
        $this->assertEquals( $expected, $required->message() );
    }
}