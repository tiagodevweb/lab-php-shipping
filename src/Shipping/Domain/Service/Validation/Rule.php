<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Service\Validation;

interface Rule
{
    public function fail(): bool;
    public function message(): string;
}