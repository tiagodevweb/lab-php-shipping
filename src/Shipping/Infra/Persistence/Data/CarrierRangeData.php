<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Persistence\Data;

use Tdw\Shipping\Domain\Persistence\Data\CarrierRangeData as ICarrierRangeData;

class CarrierRangeData implements ICarrierRangeData
{
    private $id;
    private $initialPostCode;
    private $finalPostCode;
    private $minWeight;
    private $maxWeight;
    private $price;
    private $carrierId;
    private $carrierName;

    public function __construct(
        int $id, int $initialPostCode, int $finalPostCode,
        float $minWeight, $maxWeight, float $price, int $carrierId,
        string $carrierName
    )
    {
        $this->id = $id;
        $this->initialPostCode = $initialPostCode;
        $this->finalPostCode = $finalPostCode;
        $this->minWeight = $minWeight;
        $this->maxWeight = $maxWeight;
        $this->price = $price;
        $this->carrierId = $carrierId;
        $this->carrierName = $carrierName;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function initialPostCode(): int
    {
        return $this->initialPostCode;
    }

    public function finalPostCode(): int
    {
        return $this->finalPostCode;
    }

    public function minWeight(): float
    {
        return $this->minWeight;
    }

    public function maxWeight()
    {
        return $this->maxWeight;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function carrierId(): int
    {
        return $this->carrierId;
    }

    public function carrierName(): string
    {
        return $this->carrierName;
    }
}