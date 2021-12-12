<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Persistence\Data;


interface CarrierRangeData
{
    public function id(): int;
    public function initialPostCode(): int;
    public function finalPostCode(): int;
    public function minWeight(): float;

    /**
     * @return float|null
     */
    public function maxWeight();
    public function price(): float;
    public function carrierId(): int;
    public function carrierName(): string;
}