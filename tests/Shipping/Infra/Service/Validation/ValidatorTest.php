<?php

namespace Tdw\Shipping\Infra\Service\Validation;


use PHPUnit\Framework\TestCase;
use Tdw\Shipping\Infra\Service\Validation\Rule\Alpha;
use Tdw\Shipping\Infra\Service\Validation\Rule\Digit;

class ValidatorTest extends TestCase
{
    private $field = 'FIELD';

    public function test_fails_should_return_true()
    {
        $alpha = new Alpha($this->field, 123);
        $digit = new Digit($this->field, 'abc');
        $validator = new Validator();
        $validator->add($alpha)
                    ->add($digit);
        $this->assertTrue( $validator->fails() );
    }

    public function test_fails_should_return_false()
    {
        $alpha = new Alpha($this->field, 'abc');
        $digit = new Digit($this->field, 123);
        $validator = new Validator();
        $validator->add($alpha)
                  ->add($digit);
        $this->assertFalse( $validator->fails() );
    }

    public function test_fails_should_return_messages()
    {
        $expected = $this->field . ' deve conter apenas letras.' . PHP_EOL;
        $expected .= $this->field . ' deve conter apenas numeros.';
        $alpha = new Alpha($this->field, 123);
        $digit = new Digit($this->field, 'abc');
        $validator = new Validator();
        $validator->add($alpha)
                  ->add($digit);
        $validator->fails();
        $this->assertEquals( $expected, $validator->messages() );
    }
}