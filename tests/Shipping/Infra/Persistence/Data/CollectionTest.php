<?php

namespace Tdw\Shipping\Infra\Persistence\Data;


use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    private $items = [
        ['0key1' => '0value1', '0key2' => '0value2'],
        ['1key1' => '1value1', '1key2' => '1value2'],
        ['3key1' => '3value1', '3key2' => '3value2']
    ];

    /**
     * @var Collection
     */
    private $collection;

    public function setUp()
    {
        parent::setUp();
        $this->collection = new Collection( $this->items );
    }

    public function test_all_should_return_items()
    {
        $this->assertEquals($this->items, $this->collection->all());
    }

    public function test_count_should_return_3()
    {
        $this->assertCount(3, $this->collection);
    }
}