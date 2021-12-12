<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Persistence;

use Tdw\Shipping\Domain\Exception\CarrierRangeExists;
use Tdw\Shipping\Domain\Exception\CarrierRangeNotFound;
use Tdw\Shipping\Domain\Persistence\CarrierRange as ICarrierRange;
use Tdw\Shipping\Domain\Persistence\Data\CarrierRangeData as ICarrierRangeData;
use Tdw\Shipping\Infra\Persistence\Data\CarrierRangeData;

class CarrierRange implements ICarrierRange
{
    private $pdo;
    private $id;

    public function __construct(\PDO $pdo, int $id)
    {
        $this->pdo = $pdo;
        $this->id = $id;
    }

    public function update(
        int $initialPostCode, int $finalPostCode,
        float $minWeight, float $maxWeight = null,
        float $price, int $carrierId
    ): bool
    {
        if ( $this->exists( $initialPostCode, $finalPostCode, $minWeight, $maxWeight, $price, $carrierId ) ) {
            throw new CarrierRangeExists();
        }
        $stmt = $this->pdo->prepare(
            "UPDATE carrier_range 
             SET initialPostCode = :initialPostCode, finalPostCode = :finalPostCode, 
                 minWeight = :minWeight, maxWeight = :maxWeight, price = :price, carrierId = :carrierId 
             WHERE id = :id"
        );
        $stmt->bindValue(':initialPostCode',$initialPostCode,\PDO::PARAM_INT);
        $stmt->bindValue(':finalPostCode',$finalPostCode,\PDO::PARAM_INT);
        $stmt->bindValue(':minWeight',$minWeight,\PDO::PARAM_STR);
        $stmt->bindValue(':maxWeight',$maxWeight, $maxWeight ? \PDO::PARAM_STR : \PDO::PARAM_BOOL);
        $stmt->bindValue(':price',$price,\PDO::PARAM_STR);
        $stmt->bindValue(':carrierId',$carrierId,\PDO::PARAM_INT);
        $stmt->bindValue(':id',$this->id,\PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function data(): ICarrierRangeData
    {
        if ( ! $carrierRange = $this->fetchObject() ) {
            throw new CarrierRangeNotFound();
        }
        return new CarrierRangeData((int)$carrierRange->id,
            (int)$carrierRange->initialPostCode, (int)$carrierRange->finalPostCode,
            (float)$carrierRange->minWeight, $carrierRange->maxWeight,
            (float)$carrierRange->price, (int)$carrierRange->carrierId,
            $carrierRange->name
        );
    }

    private function fetchObject()
    {
        $stmt = $this->pdo->prepare("SELECT cr.*, c.name  
                                     FROM carrier_range cr 
                                     JOIN carrier c ON c.id = cr.carrierId  
                                     WHERE cr.id = ?");
        $stmt->bindValue(1,$this->id,\PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    private function exists(
        int $initialPostCode, int $finalPostCode,
        float $minWeight, float $maxWeight = null,
        float $price, int $carrierId
    ): bool
    {
        $data = $this->data();
        if ( $data->initialPostCode() == $initialPostCode and
             $data->finalPostCode() == $finalPostCode and
             $data->minWeight() == $minWeight and
             $data->maxWeight() == $maxWeight and
             $data->price() == $price and
             $data->carrierId() == $carrierId ) {
            return false;
        }
        $sqlMaxWeightFloat = "SELECT id 
                                    FROM carrier_range 
                                        WHERE initialPostCode = :initialPostCode
                                             AND finalPostCode = :finalPostCode
                                             AND CAST(minWeight AS CHAR) = :minWeight
                                             AND CAST(maxWeight AS CHAR) = :maxWeight
                                             AND CAST(price AS CHAR) = :price 
                                             AND carrierId = :carrierId";
        $sqlMaxWeightNull = "SELECT id 
                                   FROM carrier_range 
                                       WHERE initialPostCode = :initialPostCode
                                            AND finalPostCode = :finalPostCode
                                            AND CAST(minWeight AS CHAR) = :minWeight
                                            AND maxWeight IS NULL
                                            AND CAST(price AS CHAR) = :price 
                                            AND carrierId = :carrierId";
        $stmt = $this->pdo->prepare($maxWeight ? $sqlMaxWeightFloat : $sqlMaxWeightNull);
        $stmt->bindValue(':initialPostCode',$initialPostCode,\PDO::PARAM_INT);
        $stmt->bindValue(':finalPostCode',$finalPostCode,\PDO::PARAM_INT);
        $stmt->bindValue(':minWeight',$minWeight,\PDO::PARAM_STR);
        if ( $maxWeight ) {
            $stmt->bindValue(':maxWeight',$maxWeight,\PDO::PARAM_STR);
        }
        $stmt->bindValue(':price',$price,\PDO::PARAM_STR);
        $stmt->bindValue(':carrierId',$carrierId,\PDO::PARAM_INT);
        $stmt->execute();
        if ( $stmt->rowCount() ) {
            return true;
        }
        return false;
    }
}