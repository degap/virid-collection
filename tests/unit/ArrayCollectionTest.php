<?php

use Virid\Collection\ArrayCollection;
use Virid\Collection\CollectionInterface;

class ArrayCollectionTest extends Codeception\Test\Unit
{
    /**
     * @var CollectionInterface
     */
    private $collection;

    protected function setUp()
    {
        parent::setUp();
        $this->collection = new ArrayCollection();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->collection = null;
    }

    public function testAddBoolean(): void
    {
        $this->assertAddItem(true);
    }

    public function testAddInteger(): void
    {
        $this->assertAddItem(1);
    }

    public function testAddFloat(): void
    {
        $this->assertAddItem(1.1);
    }

    public function testAddString(): void
    {
        $this->assertAddItem('string');
    }

    public function testAddArray(): void
    {
        $this->assertAddItem([1, 2, '2']);
    }

    public function testAddObject(): void
    {
        $object = new \stdClass();
        $object->field = 'value';
        $this->assertAddItem($object);
    }

    public function testAddCallable(): void
    {
        $func = function () {
            return true;
        };
        $this->assertAddItem($func);
    }

    /**
     * @throws Exception
     */
    public function testCount(): void
    {
        $fixture = array_fill(0, random_int(1, 100), uniqid('', true));
        $this->collection->mergeArray($fixture);
        $this->assertEquals(count($fixture), $this->collection->count(), 'Count of collection items must be equal to count of original array');
    }

    public function testIsEmpty(): void
    {
        $isEmpty = $this->collection->isEmpty();
        $this->assertTrue($isEmpty, 'Initial collection must be empty');
    }

    public function testGetIterator(): void
    {
        $this->assertIterator('getIterator');
    }

    public function testGetGenerator(): void
    {
        $this->assertIterator('getGenerator');
    }

    public function testToArray(): void
    {
        $items = [11, 'string', new \stdClass()];
        $this->collection->mergeArray($items);
        $this->assertEquals($items, $this->collection->toArray(), 'Array from collection must be equal to original');
    }

    public function testClear(): void
    {
        $items = [11, 'string', new \stdClass()];
        $this->collection->mergeArray($items);
        $this->assertNotEquals(0, $this->collection->count(), 'Items must exist in collection');
        $this->collection->clear();
        $this->assertEquals(0, $this->collection->count(), 'Collection must be empty');
    }

    public function testSetItemNewKey(): void
    {
        $items = [-1 => 1, 2,3];
        $this->collection->mergeArray($items);
        $this->collection->setItem(-1, 'string');
        $expected = [-1 => 'string', 1, 2, 3];
        $this->assertEquals($expected, $this->collection->toArray(), 'Item must set with new key');
    }

    public function testSetItemExistKey(): void
    {
        $items = [1, 2,3];
        $this->collection->mergeArray($items);
        $this->collection->setItem(1, 'string');
        $expected = [1, 'string', 3];
        $this->assertEquals($expected, $this->collection->toArray(), 'Item in second position must change');
    }

    public function testSetItemWithUnsupportedKey(): void
    {
        $items = [1,2,3];
        $this->collection->mergeArray($items);
        $this->collection->setItem(new \stdClass(), 'string');
        $this->assertEquals($items, $this->collection->toArray(), 'Collection must not change');
    }

    public function testRemoveItemWithoutPreserveKeys(): void
    {
        $this->assertRemove('removeItem');
    }

    public function testRemoveItemWithPreserveKeys(): void
    {
        $this->assertRemove('removeItem', true);
    }

    public function testRemoveWithoutPreserveKeys(): void
    {
        $this->assertRemove('remove');
    }

    public function testRemoveWithPreserveKeys(): void
    {
        $this->assertRemove('remove', true);
    }

    public function testRemoveNotExistItem(): void
    {
        $this->assertRemoveNotExist('removeItem');
    }

    public function testRemoveNotExistItemUnsupportedKey(): void
    {
        $this->assertRemoveNotExist('removeItem', new \stdClass());
    }

    public function testRemoveNotExistKey(): void
    {
        $this->assertRemoveNotExist('remove');
    }

    public function testRemoveNotExistUnsupportedKey(): void
    {
        $this->assertRemoveNotExist('remove', new \stdClass());
    }

    public function testContainsItem(): void
    {
        $this->assertCollectionContains('containsItem');
    }

    public function testContainsNotExistedItem(): void
    {
        $this->assertCollectionContains('containsItem', false, 'not_existed');
    }

    public function testContainsItemNotSupportedKey(): void
    {
        $this->assertCollectionContains('containsItem', false, new \stdClass());
    }

    public function testContainsKey(): void
    {
        $this->assertCollectionContains('containsKey');
    }

    public function testContainsNotExistedKey(): void
    {
        $this->assertCollectionContains('containsKey', false, 100);
    }

    public function testContainsKeyNotSupportedKey(): void
    {
        $this->assertCollectionContains('containsKey', false, new \stdClass());
    }

    public function testGetItem(): void
    {
        $items = [1,2,3];
        $this->collection->mergeArray($items);
        $key = 1;
        $this->assertEquals($items[$key], $this->collection->getItem($key), 'Item from collection mast be expected');
    }

    public function testGetItemUnsupportedKey(): void
    {
        $items = [1,2,3];
        $this->collection->mergeArray($items);
        $this->assertNull($this->collection->getItem(new \stdClass()), 'Search result must be null');
    }

    public function testGetLast(): void
    {
        $items = [1,2,3];
        $this->collection->mergeArray($items);
        $this->assertEquals(3, $this->collection->getLast(), 'Last item must be expected');
        $new = 'new';
        $this->collection->addItem($new);
        $this->assertEquals($new, $this->collection->getLast(), 'Last item must be ' . $new);
    }

    public function testMergeArray(): void
    {
        $array1 = ['s' => 1, 2, 77 => 3];
        $expected1 = ['s' => 1, 2, 3];
        $array2 = [4, 5, 's' => 20, 77 => 6];
        $expected2 = ['s' => 20, 2, 3, 4, 5, 6];

        $this->collection->mergeArray($array1);
        $this->assertEquals($expected1, $this->collection->toArray(), 'Merged array must be expected');
        $this->collection->mergeArray($array2);
        $this->assertEquals($expected2, $this->collection->toArray(), 'Merged array must be expected');
    }

    private function assertCollectionContains($method, $exists = true, $search = 1): void
    {
        $items = [1,2,3];
        $this->collection->mergeArray($items);
        $result = $this->collection->$method($search);
        if ($exists) {
            $this->assertTrue($result, 'Search result must true');
        } else {
            $this->assertFalse($result, 'Search result must false');
        }
    }

    private function assertRemoveNotExist($method, $toRemove = 'unexisted'): void
    {
        $items = [1,2,3];
        $this->collection->mergeArray($items);
        $this->collection->$method($toRemove);
        $this->assertEquals($items, $this->collection->toArray(), 'Collection must not change');
    }

    private function assertRemove($method, $preserveKeys = false): void
    {
        $items = [1, 'string', 3];
        $this->collection->mergeArray($items);
        $removeByKey = ($method === 'remove');
        $remove = $removeByKey ? 1 : 'string';
        $this->collection->$method($remove, $preserveKeys);
        $expected = $preserveKeys ? [0 => 1, 2 => 3] : [1, 3];
        $message = $removeByKey ? "Item with key $remove must remove" : "Item $remove must remove";
        $this->assertEquals($expected, $this->collection->toArray(), $message);
    }

    private function assertAddItem($item): void
    {
        $this->collection->addItem($item);
        $gotItem = $this->collection->getLast();
        $message = sprintf('Item from collection must be equal to original');
        $this->assertEquals($item, $gotItem, $message);
    }

    private function assertIterator(string $getter): void
    {
        $item1 = 11;
        $item2 = 22;
        $this->collection->addItem($item1);
        $this->collection->addItem($item2);

        /** @var Iterator $iterator */
        $iterator = $this->collection->$getter();

        $iterator->rewind();
        $this->assertEquals($item1, $iterator->current(), 'First item of iterator must be equal to first added item');
        $iterator->next();
        $this->assertEquals($item2, $iterator->current(), 'Second item of iterator must be equal to second added item');
        $iterator->next();
        $this->assertFalse($iterator->valid(), 'iterator must contains only two items');
    }

}