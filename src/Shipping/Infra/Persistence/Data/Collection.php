<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Persistence\Data;

use Tdw\Shipping\Domain\Persistence\Data\Collection as ICollection;

class Collection implements ICollection
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function count()
    {
        return count( $this->items );
    }
}