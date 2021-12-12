<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Persistence;

use Tdw\Shipping\Domain\Exception\CarrierExists as CarrierExistsException;
use Tdw\Shipping\Domain\Persistence\Carriers as ICarriers;
use Tdw\Shipping\Domain\Persistence\Data\Collection as ICollection;
use Tdw\Shipping\Infra\Persistence\Data\Collection;

class Carriers implements ICarriers
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $name, bool $active): int
    {
        if ( $this->exists( $name ) ) {
            throw new CarrierExistsException();
        }
        $stmt = $this->pdo->prepare("INSERT INTO carrier (name, active) VALUES(?, ?)");
        $stmt->bindValue(1,$name,\PDO::PARAM_STR);
        $stmt->bindValue(2,$active,\PDO::PARAM_BOOL);
        if ( $stmt->execute() ) {
            return (int)$this->pdo->lastInsertId();
        }
        return 0;
    }

    public function collection(): ICollection
    {
        $stmt = $this->pdo->query(
            "SELECT *, CASE WHEN active = 1 THEN 'Sim' WHEN active = 0 THEN 'Não' END AS active 
             FROM carrier"
        );
        return new Collection( $stmt->fetchAll() );
    }

    private function exists(string $name): bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM carrier WHERE name = ?");
        $stmt->bindValue(1, $name, \PDO::PARAM_STR);
        $stmt->execute();
        if ( $stmt->rowCount() ) {
            return true;
        }
        return false;
    }
}