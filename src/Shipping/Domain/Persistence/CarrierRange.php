<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Persistence;


use Tdw\Shipping\Domain\Exception\CarrierRangeExists as CarrierRangeNotFoundException;
use Tdw\Shipping\Domain\Persistence\Data\CarrierRangeData as ICarrierRangeData;

interface CarrierRange
{
    public function update(
        int $initialPostCode, int $finalPostCode,
        float $minWeight, float $maxWeight = null,
        float $price, int $carrierId
    ): bool;

    /**
     * @throws CarrierRangeNotFoundException
     * @return ICarrierRangeData
     */
    public function data(): ICarrierRangeData;
}