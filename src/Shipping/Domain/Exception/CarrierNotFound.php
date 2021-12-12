<?php

namespace Tdw\Shipping\Domain\Exception;


class CarrierNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct("Transportadora não localizada");
    }
}