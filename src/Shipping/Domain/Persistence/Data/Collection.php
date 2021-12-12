<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Persistence\Data;


interface Collection extends \Countable
{
    public function all(): array;
}