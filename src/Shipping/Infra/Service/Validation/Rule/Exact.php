<?php

declare( strict_types=1 );

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use Tdw\Shipping\Domain\Service\Validation\Rule;

class Exact implements Rule
{
    private $key;
    private $value;
    private $characters;

    public function __construct(string $key, $value, int $characters)
    {
        $this->key = $key;
        $this->value = $value;
        $this->characters = $characters;
    }

    public function fail(): bool
    {
        return $this->characters !== strlen( (string) $this->value );
    }

    public function message(): string
    {
        return sprintf( "%s deve conter %d caracteres." , $this->key, $this->characters );
    }
}