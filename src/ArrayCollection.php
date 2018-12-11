<?php

namespace Virid\Collection;

use function count;
use function is_string;
use function is_int;
use function array_search;
use function in_array;

class ArrayCollection implements CollectionInterface
{
    /**
     * @var array
     */
    private $storage = [];

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->storage);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->storage);
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->storage);
    }

    /**
     * @return \Generator
     */
    public function getGenerator(): \Generator
    {
        yield from $this->storage;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->storage;
    }

    /**
     * Remove all items
     */
    public function clear(): void
    {
        $this->storage = [];
    }

    /**
     * @param mixed $item
     *
     * @return CollectionInterface
     */
    public function addItem($item): CollectionInterface
    {
        $this->storage[] = $item;
        return $this;
    }

    /**
     * @param string|int $key
     * @param mixed      $item
     *
     * @return CollectionInterface
     */
    public function setItem($key, $item): CollectionInterface
    {
        if ($this->isSupportedKey($key)) {
            $this->storage[$key] = $item;
        }
        return $this;
    }

    /**
     * @param mixed $item
     *
     * @param bool  $preserveKey
     *
     * @return CollectionInterface
     */
    public function removeItem($item, $preserveKey = false): CollectionInterface
    {
        $itemKey = array_search($item, $this->storage, true);
        return $this->remove($itemKey, $preserveKey);
    }

    /**
     * @param string|int $key
     *
     * @param bool       $preserveKey
     *
     * @return CollectionInterface
     */
    public function remove($key, $preserveKey = false): CollectionInterface
    {
        if ($this->isSupportedKey($key)) {
            unset($this->storage[$key]);
            if (!$preserveKey) {
                $this->storage = array_values($this->storage);
            }
        }
        return $this;
    }

    /**
     * @param $item
     *
     * @return bool
     */
    public function containsItem($item): bool
    {
        return in_array($item, $this->storage, true);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function containsKey($key): bool
    {
        return  $this->isSupportedKey($key) ? isset($this->storage[$key]) : false;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getItem($key)
    {
        return $this->isSupportedKey($key) ? $this->storage[$key] : null;
    }

    public function getLast()
    {
        end($this->storage);
        return current($this->storage);
    }

    /**
     * @param array $data
     *
     * @return CollectionInterface
     */
    public function mergeArray(array $data): CollectionInterface
    {
        $this->storage = array_merge($this->storage, $data);
        return $this;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    private function isSupportedKey($key): bool
    {
        return is_string($key) || is_int($key);
    }
}
