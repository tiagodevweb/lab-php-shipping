<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Persistence\Data;

use Tdw\Shipping\Domain\Persistence\Data\CarrierData as ICarrierData;

class CarrierData implements ICarrierData
{
    private $id;
    private $name;
    private $active;

    public function __construct(int $id, string $name, bool $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->active = $active;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function active(): bool
    {
        return $this->active;
    }
}