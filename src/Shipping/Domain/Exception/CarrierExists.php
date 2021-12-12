<?php

namespace Tdw\Shipping\Domain\Exception;

class CarrierExists extends \Exception
{
    public function __construct()
    {
        parent::__construct("Transportadora já cadastrada no sistema.");
    }

}