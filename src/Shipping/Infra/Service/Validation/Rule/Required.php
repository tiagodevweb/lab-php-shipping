<?php

declare( strict_types=1 );

namespace Tdw\Shipping\Infra\Service\Validation\Rule;


use Tdw\Shipping\Domain\Service\Validation\Rule;

class Required implements Rule
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
        return empty( $this->value );
    }

    public function message(): string
    {
        return sprintf( "%s é requerido." , $this->key );
    }
}