<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Persistence;

use Tdw\Shipping\Domain\Exception\CarrierExists as CarrierExistsException;
use Tdw\Shipping\Domain\Exception\CarrierNotFound;
use Tdw\Shipping\Domain\Persistence\Carrier as ICarrier;
use Tdw\Shipping\Domain\Persistence\Data\CarrierData as ICarrierData;
use Tdw\Shipping\Infra\Persistence\Data\CarrierData;

class Carrier implements ICarrier
{
    private $pdo;
    private $id;

    public function __construct(\PDO $pdo, int $id)
    {
        $this->pdo = $pdo;
        $this->id = $id;
    }

    public function update(string $name, bool $active): bool
    {
        if ( $this->exists( $name ) ) {
            throw new CarrierExistsException();
        }
        $stmt = $this->pdo->prepare("UPDATE carrier SET name = ?, active = ? WHERE id = ?");
        $stmt->bindValue(1,$name,\PDO::PARAM_STR);
        $stmt->bindValue(2,$active,\PDO::PARAM_BOOL);
        $stmt->bindValue(3,$this->id,\PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function data(): ICarrierData
    {
        if ( ! $carrier = $this->fetchObject() ) {
            throw new CarrierNotFound();
        }
        return new CarrierData((int)$carrier->id, $carrier->name, (bool)$carrier->active);
    }

    private function fetchObject()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM carrier WHERE id = ?");
        $stmt->bindValue(1,$this->id,\PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    private function exists(string $name): bool
    {
        if ( ( $nameDB = $this->data()->name() ) === $name ) {
            return false;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM carrier WHERE name = ?");
        $stmt->bindValue(1, $name, \PDO::PARAM_STR);
        $stmt->execute();
        if ( $stmt->rowCount() ) {
            return true;
        }
        return false;
    }

}