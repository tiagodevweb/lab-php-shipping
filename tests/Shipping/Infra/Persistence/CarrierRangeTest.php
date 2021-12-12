<?php

namespace Tdw\Shipping\Infra\Persistence;


use PHPUnit\Framework\TestCase;
use Tdw\Shipping\Infra\Persistence\Data\CarrierData;
use Tdw\Shipping\Infra\Persistence\Data\CarrierRangeData;

class CarrierRangeTest extends TestCase
{
    /**
     * @var \PDO
     */
    private $pdo;
    private $initialPostCode = 96085200;
    private $finalPostCode = 96085300;
    private $minWeight = 0.399;
    private $maxWeight = 3;
    private $price = 50.98;

    public function setUp()
    {
        parent::setUp();
        $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
        $this->pdo = new \PDO('mysql:host=localhost;dbname=shipping', 'root', 'root', $options);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function test_data_should_return_instanceof_carrierdata()
    {
        $this->pdo->beginTransaction();
        $carriersRange = new CarriersRange( $this->pdo );

        $carriers = new Carriers( $this->pdo );
        $carrierId = $carriers->create( 'name test', true );

        $carriersRangeId = $carriersRange->create(
            $this->initialPostCode,$this->finalPostCode,
            $this->minWeight,$this->maxWeight,
            $this->price, $carrierId
        );
        $carrierRange = new CarrierRange( $this->pdo, $carriersRangeId );
        $this->assertInstanceOf(CarrierRangeData::class, $carrierRange->data());
        $this->pdo->rollBack();
    }

    /**
     * @expectedException \Tdw\Shipping\Domain\Exception\CarrierRangeNotFound
     */
    public function test_data_should_return_exception()
    {
        $this->pdo->beginTransaction();
        $carrierRange = new CarrierRange( $this->pdo, 0 );
        $carrierRange->data();
        $this->pdo->rollBack();
    }

    public function test_update_should_return_true()
    {
        $this->pdo->beginTransaction();
        $carriersRange = new CarriersRange( $this->pdo );

        $carriers = new Carriers( $this->pdo );
        $carrierId = $carriers->create( 'name test', true );

        $carriersRangeId = $carriersRange->create(
            $this->initialPostCode,$this->finalPostCode,
            $this->minWeight,$this->maxWeight,
            $this->price, $carrierId
        );

        $carrierRange = new CarrierRange( $this->pdo, $carriersRangeId );
        $true = $carrierRange->update(96085400,96085500,0.499,4,60.98,$carrierId);

        $this->assertTrue($true);
        $this->pdo->rollBack();
    }

    /**
     * @expectedException \Tdw\Shipping\Domain\Exception\CarrierRangeExists
     */
    public function test_update_should_return_exception()
    {
        $this->pdo->beginTransaction();
        $carriersRange = new CarriersRange( $this->pdo );

        $carriers = new Carriers( $this->pdo );
        $carrierId = $carriers->create( 'name test', true );

        $carriersRangeId = $carriersRange->create(
            $this->initialPostCode,$this->finalPostCode,
            $this->minWeight,$this->maxWeight,
            $this->price, $carrierId
        );

        $initialPostCode = 96085400;
        $finalPostCode = 96085500;
        $minWeight = 0.499;
        $maxWeight = 4;
        $price = 60.98;
        $carriersRange->create(
            $initialPostCode,$finalPostCode,
            $minWeight,$maxWeight,
            $price, $carrierId
        );

        $carrierRange = new CarrierRange( $this->pdo, $carriersRangeId );
        $carrierRange->update($initialPostCode,$finalPostCode,$minWeight,$maxWeight,$price,$carrierId);
        $this->pdo->rollBack();
    }

}