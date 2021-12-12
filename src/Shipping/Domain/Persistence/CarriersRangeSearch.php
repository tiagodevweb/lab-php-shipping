<?php

namespace Tdw\Shipping\Domain\Persistence;


use Tdw\Shipping\Domain\Persistence\Data\Collection;

interface CarriersRangeSearch
{
    public function execute(int $postCode, float $weight): Collection;
}