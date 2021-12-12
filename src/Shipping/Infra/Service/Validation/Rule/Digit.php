<?php

declare( strict_types=1 );

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use Tdw\Shipping\Domain\Service\Validation\Rule;

class Digit implements Rule
{
    private $key;
    private $value;

    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function fail(): bool
    {
        return ! ctype_digit( (string ) $this->value );
    }

    public function message(): string
    {
        return sprintf( "%s deve conter apenas numeros." , $this->key );
    }
}