<?php

namespace Tdw\Shipping\Infra\Persistence;


use PHPUnit\Framework\TestCase;
use Tdw\Shipping\Domain\Persistence\Data\Collection;

class CarriersTest extends TestCase
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

    public function test_create_should_return_last_insert_id()
    {
        $this->pdo->beginTransaction();
        $carriers = new Carriers( $this->pdo );
        $lastInsertId = $carriers->create( $this->name, $this->active );
        $this->assertTrue( is_int( $lastInsertId ) );
        $this->pdo->rollBack();
    }

    /**
     * @expectedException \Tdw\Shipping\Domain\Exception\CarrierExists
     */
    public function test_create_should_return_exception()
    {
        $this->pdo->beginTransaction();
        $carriers = new Carriers( $this->pdo );
        $carriers->create( $this->name, $this->active );
        $carriers->create( $this->name, $this->active );
        $this->pdo->rollBack();
    }

    public function test_all_should_return_collection()
    {
        $this->pdo->beginTransaction();
        $carriers = new Carriers( $this->pdo );
        $carriers->create( $this->name, $this->active );
        $this->assertInstanceOf( Collection::class, $carriers->collection() );
        $this->pdo->rollBack();
    }

}