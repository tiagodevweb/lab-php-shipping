<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Persistence;


use Tdw\Shipping\Domain\Persistence\Data\Collection;
use Tdw\Shipping\Domain\Exception\CarrierExists as CarrierExistsException;

interface Carriers
{
    /**
     * @param string $name
     * @param bool $active
     * @return int
     * @throws CarrierExistsException
     */
    public function create(string $name, bool $active): int;
    public function collection(): Collection;
}