<?php

namespace Tdw\Shipping\Domain\Exception;


class CarrierRangeNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct("Região não localizada");
    }
}