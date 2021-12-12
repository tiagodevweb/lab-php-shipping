<?php

namespace Tdw\Shipping\Domain\Exception;


class CarrierRangeExists extends \Exception
{
    public function __construct()
    {
        parent::__construct("Região já cadastrada no sistema.");
    }
}