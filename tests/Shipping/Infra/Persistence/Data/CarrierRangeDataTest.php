<?php

namespace Tdw\Shipping\Infra\Persistence\Data;


use PHPUnit\Framework\TestCase;
use Tdw\Shipping\Domain\Persistence\Data\CarrierRangeData as ICarrierRangeData;

class CarrierRangeDataTest extends TestCase
{
    /**
     * @var CarrierRangeData
     */
    private $carrierRange;
    private $id = 1;
    private $initialPostCode = 96085200;
    private $finalPostCode = 96085300;
    private $minWeight = 0.300;
    private $maxWeight = 3;
    private $price = 50.98;
    private $carrierId = 1;
    private $carrierName = 'name test';

    public function setUp()
    {
        parent::setUp();
        $this->carrierRange = new CarrierRangeData($this->id,
            $this->initialPostCode, $this->finalPostCode, $this->minWeight,
            $this->maxWeight, $this->price, $this->carrierId, $this->carrierName
        );
    }

    public function test_instanceof()
    {
        $this->assertInstanceOf( ICarrierRangeData::class, $this->carrierRange );
    }

    public function test_should_return_id()
    {
        $this->assertEquals( $this->id, $this->carrierRange->id() );
    }

    public function test_should_return_initialPostCode()
    {
        $this->assertEquals( $this->initialPostCode, $this->carrierRange->initialPostCode() );
    }

    public function test_should_return_finalPostCode()
    {
        $this->assertEquals( $this->finalPostCode, $this->carrierRange->finalPostCode() );
    }

    public function test_should_return_minWeight()
    {
        $this->assertEquals( $this->minWeight, $this->carrierRange->minWeight() );
    }

    public function test_should_return_maxWeight()
    {
        $this->assertEquals( $this->maxWeight, $this->carrierRange->maxWeight() );
    }

    public function test_should_return_price()
    {
        $this->assertEquals( $this->price, $this->carrierRange->price() );
    }

    public function test_should_return_carrierId()
    {
        $this->assertEquals( $this->carrierId, $this->carrierRange->carrierId() );
    }

    public function test_should_return_carrierName()
    {
        $this->assertEquals( $this->carrierName, $this->carrierRange->carrierName() );
    }
}