<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Persistence;

use Tdw\Shipping\Domain\Exception\CarrierExists as CarrierExistsException;
use Tdw\Shipping\Domain\Exception\CarrierNotFound as CarrierNotFoundException;
use Tdw\Shipping\Domain\Persistence\Data\CarrierData as ICarrierData;

interface Carrier
{
    /**
     * @param string $name
     * @param bool $active
     * @return bool
     * @throws CarrierExistsException
     */
    public function update(string $name, bool $active): bool;

    /**
     * @throws CarrierNotFoundException
     * @return ICarrierData
     */
    public function data(): ICarrierData;
}