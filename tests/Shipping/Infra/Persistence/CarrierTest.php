<?php

namespace Tdw\Shipping\Infra\Persistence;


use PHPUnit\Framework\TestCase;
use Tdw\Shipping\Infra\Persistence\Data\CarrierData;

class CarrierTest extends TestCase
{
    /**
     * @var \PDO
     */
    private $pdo;
    private $name = 'name test';
    private $active = true;

    public function setUp()
    {
        parent::setUp();
        $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
        $this->pdo = new \PDO('mysql:host=localhost;dbname=shipping', 'root', 'root', $options);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function test_update_should_return_true()
    {
        $this->pdo->beginTransaction();
        $carriers = new Carriers( $this->pdo );
        $lastInsertId = $carriers->create( $this->name, $this->active );
        $carrier = new Carrier( $this->pdo, $lastInsertId );
        $true = $carrier->update('new name test', false);
        $this->assertTrue( $true );
        $this->pdo->rollBack();
    }

    /**
     * @expectedException \Tdw\Shipping\Domain\Exception\CarrierExists
     */
    public function test_update_should_return_exception()
    {
        $this->pdo->beginTransaction();
        $carriers = new Carriers( $this->pdo );
        $lastInsertId = $carriers->create( $this->name, $this->active );
        $carrier = new Carrier( $this->pdo, $lastInsertId );
        $name = 'new name';
        $carriers->create( $name, $this->active );
        $carrier->update($name, false);
        $this->pdo->rollBack();
    }

    public function test_data_should_return_instanceof_CarrierData()
    {
        $this->pdo->beginTransaction();
        $carriers = new Carriers( $this->pdo );
        $lastInsertId = $carriers->create( $this->name, $this->active );
        $carrier = new Carrier( $this->pdo, $lastInsertId );
        $carrierData = $carrier->data();
        $this->assertInstanceOf(CarrierData::class, $carrierData);
        $this->pdo->rollBack();
    }

    /**
     * @expectedException \Tdw\Shipping\Domain\Exception\CarrierNotFound
     */
    public function test_data_should_return_exception()
    {
        $this->pdo->beginTransaction();
        $carrier = new Carrier( $this->pdo, 0 );
        $carrier->data();
        $this->pdo->rollBack();
    }

}