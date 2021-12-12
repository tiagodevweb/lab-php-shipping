<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Persistence\Data;


interface CarrierData
{
    public function id(): int;
    public function name(): string;
    public function active(): bool;
}