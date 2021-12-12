<?php

declare( strict_types=1 );

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use Tdw\Shipping\Domain\Service\Validation\Rule;

class Between implements Rule
{
    private $key;
    private $value;
    private $min;
    private $max;

    public function __construct(string $key, $value, int $min, int $max)
    {
        $this->key = $key;
        $this->value = $value;
        $this->min = $min;
        $this->max = $max;
    }

    public function fail(): bool
    {
        return strlen( (string) $this->value ) < $this->min or strlen( (string) $this->value ) > $this->max;
    }

    public function message(): string
    {
        return sprintf( "%s deve conter entre %d e %d caracteres." , $this->key, $this->min, $this->max );
    }
}