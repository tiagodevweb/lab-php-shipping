<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Persistence;

use Tdw\Shipping\Domain\Exception\CarrierRangeExists;
use Tdw\Shipping\Domain\Exception\CarrierRangeNotFound;
use Tdw\Shipping\Domain\Persistence\CarriersRange as ICarriersRange;
use Tdw\Shipping\Domain\Persistence\Data\Collection as ICollection;
use Tdw\Shipping\Infra\Persistence\Data\Collection;

class CarriersRange implements ICarriersRange
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(
        int $initialPostCode, int $finalPostCode,
        float $minWeight, float $maxWeight = null,
        float $price, int $carrierId
    )
    {
        if ( $this->exists( $initialPostCode, $finalPostCode, $minWeight, $maxWeight, $carrierId ) ) {
            throw new CarrierRangeExists();
        }
        $stmt = $this->pdo->prepare(
            "INSERT 
             INTO carrier_range (initialPostCode, finalPostCode, minWeight, maxWeight, price, carrierId) 
             VALUES(:initialPostCode, :finalPostCode, :minWeight, :maxWeight, :price, :carrierId)"
        );
        $stmt->bindValue(':initialPostCode',$initialPostCode,\PDO::PARAM_INT);
        $stmt->bindValue(':finalPostCode',$finalPostCode,\PDO::PARAM_INT);
        $stmt->bindValue(':minWeight',$minWeight,\PDO::PARAM_STR);
        $stmt->bindValue(':maxWeight',$maxWeight, $maxWeight ? \PDO::PARAM_STR : \PDO::PARAM_BOOL);
        $stmt->bindValue(':price',$price,\PDO::PARAM_STR);
        $stmt->bindValue(':carrierId',$carrierId,\PDO::PARAM_INT);
        if ( $stmt->execute() ) {
            return (int)$this->pdo->lastInsertId();
        }
        return false;
    }

    public function remove(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM carrier_range WHERE id = ?");
        $stmt->bindValue(1,$id,\PDO::PARAM_INT);
        $stmt->execute();
        if ( $stmt->rowCount() == 0 ) {
            throw new CarrierRangeNotFound();
        }
        $stmt = $this->pdo->prepare("DELETE FROM carrier_range WHERE id = ?");
        $stmt->bindValue(1,$id,\PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function collection(): ICollection
    {
        $stmt = $this->pdo->query(
            "SELECT c.name as carrierName, cr.* FROM carrier_range cr 
             JOIN carrier c ON c.id = cr.carrierId"
        );
        return new Collection( $stmt->fetchAll() );
    }

    private function exists(
        int $initialPostCode, int $finalPostCode, float $minWeight, float $maxWeight = null, int $carrierId
    ): bool
    {
        $sqlMaxWeightFloat = "SELECT id 
                                    FROM carrier_range 
                                        WHERE initialPostCode = :initialPostCode
                                             AND finalPostCode = :finalPostCode
                                             AND CAST(minWeight AS CHAR) = :minWeight
                                             AND CAST(maxWeight AS CHAR) = :maxWeight
                                             AND carrierId = :carrierId";
        $sqlMaxWeightNull = "SELECT id 
                                   FROM carrier_range 
                                       WHERE initialPostCode = :initialPostCode
                                            AND finalPostCode = :finalPostCode
                                            AND CAST(minWeight AS CHAR) = :minWeight
                                            AND maxWeight IS NULL
                                            AND carrierId = :carrierId";
        $stmt = $this->pdo->prepare($maxWeight ? $sqlMaxWeightFloat : $sqlMaxWeightNull);
        $stmt->bindValue(':initialPostCode',$initialPostCode,\PDO::PARAM_INT);
        $stmt->bindValue(':finalPostCode',$finalPostCode,\PDO::PARAM_INT);
        $stmt->bindValue(':minWeight',$minWeight,\PDO::PARAM_STR);
        if ( $maxWeight ) {
            $stmt->bindValue(':maxWeight',$maxWeight,\PDO::PARAM_STR);
        }
        $stmt->bindValue(':carrierId',$carrierId,\PDO::PARAM_INT);
        $stmt->execute();
        if ( $stmt->rowCount() ) {
            return true;
        }
        return false;
    }
}