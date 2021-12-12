<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Persistence;

use Tdw\Shipping\Domain\Exception\CarrierRangeNotFound;
use Tdw\Shipping\Domain\Persistence\Data\Collection;
use Tdw\Shipping\Domain\Exception\CarrierRangeExists;

interface CarriersRange
{
    /**
     * @param int $postCodeInitial
     * @param int $postCodeFinal
     * @param float $minWeight
     * @param $maxWeight
     * @param float $price
     * @param int $carrierId
     * @return mixed
     * @throws CarrierRangeExists
     */
    public function create(
        int $postCodeInitial, int $postCodeFinal,
        float $minWeight, float $maxWeight = null,
        float $price, int $carrierId
    );

    /**
     * @param int $id
     * @return bool
     * @throws CarrierRangeNotFound
     */
    public function remove(int $id): bool;
    public function collection(): Collection;
}