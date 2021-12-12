<?php

namespace Tdw\Shipping\Infra\Persistence\Data;

use PHPUnit\Framework\TestCase;
use Tdw\Shipping\Domain\Persistence\Data\CarrierData as ICarrierData;

class CarrierDataTest extends TestCase
{
    /**
     * @var CarrierData
     */
    private $carrier;
    private $id = 1;
    private $name = 'name test';
    private $active = true;

    public function setUp()
    {
        parent::setUp();
        $this->carrier = new CarrierData($this->id, $this->name, $this->active);
    }

    public function test_instanceof()
    {
        $this->assertInstanceOf( ICarrierData::class, $this->carrier );
    }

    public function test_should_return_id()
    {
        $this->assertEquals( $this->id, $this->carrier->id() );
    }

    public function test_should_return_name()
    {
        $this->assertEquals( $this->name, $this->carrier->name() );
    }

    public function test_should_return_active()
    {
        $this->assertEquals( $this->active, $this->carrier->active() );
    }
}