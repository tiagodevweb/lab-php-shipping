<?php

declare( strict_types=1 );

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use Tdw\Shipping\Domain\Service\Validation\Rule;

class Regex implements Rule
{
    private $key;
    private $value;
    private $expression;
    private $format;

    public function __construct(string $key, $value, string $expression, string $format = '')
    {
        $this->key = $key;
        $this->value = $value;
        $this->expression = $expression;
        $this->format = $format;
    }

    public function fail(): bool
    {
        return ! preg_match( "/^".$this->expression."$/" , (string)$this->value );
    }

    public function message(): string
    {
        return sprintf( "%s não contém um formato válido.%s" , $this->key, $this->format );
    }
}