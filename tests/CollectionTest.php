<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests;

use NN\Collection\CollectionInterface;
use NN\IdInterface;

class CollectionTest extends BaseTestCase
{
    public function testCollection(): void
    {
        $data = [1, 2, 3, 4];

        $collection = $this->createCollection($data);

        $this->assertEquals(count($data), $collection->count());

        foreach ($data as $i => $value) {
            $this->assertEquals($value, $collection->findById($value)->getId());
            $this->assertEquals($value, $collection[$i]->getId());
        }

        $collection->remove($collection[0]);
        unset($data[0]);
        //array_shift($data);

        $this->assertEquals(count($data), $collection->count());

        unset($collection[1]);
        unset($data[1]);
        //array_shift($data);

        $this->assertEquals(count($data), $collection->count());

        //$data = array_values($data);

        foreach ($collection as $i => $value) {
            $this->assertEquals($data[$i], $value->getId());
        }

        $collection->clear();

        $this->assertEquals(0, $collection->count());
    }

    /**
     * @param array $data
     * @return CollectionInterface
     */
    protected function createCollection(array $data): CollectionInterface
    {
        $collection = $this->getCollectionFactory()->createCollection();

        foreach ($data as $value) {
            $stub = $this->createMock(IdInterface::class);
            $stub->method('getId')->willReturn($value);
            $collection->add($stub);
        }

        return $collection;
    }
}