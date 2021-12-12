<?php

namespace Tdw\Shipping\Infra\Persistence;

use Tdw\Shipping\Domain\Persistence\CarriersRangeSearch as ICarriersRangeSearch;
use Tdw\Shipping\Domain\Persistence\Data\Collection as ICollection;
use Tdw\Shipping\Infra\Persistence\Data\Collection;

class CarriersRangeSearch implements ICarriersRangeSearch
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function execute(int $postCode, float $weight): ICollection
    {
        $stmt = $this->pdo->prepare(
            "SELECT c.name, cr.price FROM carrier_range cr 
            JOIN carrier c ON c.id = cr.carrierId 
            WHERE cr.initialPostCode <= ?  
            AND cr.finalPostCode >= ? 
            AND cr.minWeight <= ? 
            AND (cr.maxWeight >= ? OR cr.maxWeight IS NULL)  
            ORDER BY cr.price ASC"
        );
        $stmt->bindValue(1, $postCode, \PDO::PARAM_INT);
        $stmt->bindValue(2, $postCode, \PDO::PARAM_INT);
        $stmt->bindValue(3, $weight, \PDO::PARAM_STR);
        $stmt->bindValue(4, $weight, \PDO::PARAM_STR);
        $stmt->execute();
        return new Collection( $stmt->fetchAll() );
    }
}