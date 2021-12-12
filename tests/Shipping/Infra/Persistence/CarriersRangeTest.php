<?php

namespace Tdw\Shipping\Infra\Persistence;


use PHPUnit\Framework\TestCase;
use Tdw\Shipping\Domain\Persistence\Data\Collection;

class CarriersRangeTest extends TestCase
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

    public function test_create_should_return_last_insert_id()
    {
        $this->pdo->beginTransaction();
        $carriersRange = new CarriersRange( $this->pdo );

        $carriers = new Carriers( $this->pdo );
        $carrierId = $carriers->create( 'name test', true );

        $lastInsertId = $carriersRange->create(
            $this->initialPostCode,$this->finalPostCode,
            $this->minWeight,$this->maxWeight,
            $this->price, $carrierId
        );
        $this->assertTrue( is_int( $lastInsertId ) );
        $this->pdo->rollBack();
    }

    /**
     * @expectedException \Tdw\Shipping\Domain\Exception\CarrierRangeExists
     */
    public function test_create_should_return_exception()
    {
        $this->pdo->beginTransaction();
        $carriersRange = new CarriersRange( $this->pdo );

        $carriers = new Carriers( $this->pdo );
        $carrierId = $carriers->create( 'name test', true );

        $carriersRange->create(
            $this->initialPostCode,$this->finalPostCode,
            $this->minWeight,null,
            $this->price, $carrierId
        );
        $carriersRange->create(
            $this->initialPostCode,$this->finalPostCode,
            $this->minWeight,null,
            $this->price, $carrierId
        );
        $this->pdo->rollBack();
    }

    public function test_remove_should_return_true()
    {
        $this->pdo->beginTransaction();
        $carriersRange = new CarriersRange( $this->pdo );

        $carriers = new Carriers( $this->pdo );
        $carrierId = $carriers->create( 'name test', true );

        $lastInsertId = $carriersRange->create(
            $this->initialPostCode,$this->finalPostCode,
            $this->minWeight,$this->maxWeight,
            $this->price, $carrierId
        );
        $true = $carriersRange->remove( $lastInsertId );
        $this->assertTrue( $true );
        $this->pdo->rollBack();
    }

    /**
     * @expectedException \Tdw\Shipping\Domain\Exception\CarrierRangeNotFound
     */
    public function test_remove_should_return_exception()
    {
        $this->pdo->beginTransaction();
        $carriersRange = new CarriersRange( $this->pdo );
        $carriersRange->remove( 0 );
        $this->pdo->rollBack();
    }

    public function test_all_should_return_collection()
    {
        $this->pdo->beginTransaction();
        $carriersRange = new CarriersRange( $this->pdo );
        $carriers = new Carriers( $this->pdo );
        $carrierId = $carriers->create( 'name test', true );

        $carriersRange->create(
            $this->initialPostCode,$this->finalPostCode,
            $this->minWeight,$this->maxWeight,
            $this->price, $carrierId
        );

        $this->assertInstanceOf( Collection::class, $carriersRange->collection() );
        $this->pdo->rollBack();
    }

}