<?php
/**
 * Created by PhpStorm.
 * User: vit
 * Date: 21.08.18
 * Time: 23:48
 */

namespace Virid\Collection;


interface CollectionInterface extends \Countable, \IteratorAggregate
{
    /**
     * @return int
     */
    public function count(): int;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator;

    /**
     * @return \Generator
     */
    public function getGenerator(): \Generator;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * Remove all items
     */
    public function clear(): void;

    /**
     * @param mixed $item
     *
     * @return CollectionInterface
     */
    public function addItem($item): CollectionInterface;

    /**
     * @param string|int $key
     * @param mixed $item
     *
     * @return CollectionInterface
     */
    public function setItem($key, $item): CollectionInterface;

    /**
     * @param mixed $item
     *
     * @param bool  $preserveKeys
     *
     * @return CollectionInterface
     */
    public function removeItem ($item, $preserveKeys = false): CollectionInterface;

    /**
     * @param string|int $key
     *
     * @param bool       $preserveKey
     *
     * @return CollectionInterface
     */
    public function remove($key, $preserveKey = false): CollectionInterface;

    /**
     * @param $item
     *
     * @return bool
     */
    public function containsItem($item): bool;

    /**
     * @param $key
     *
     * @return bool
     */
    public function containsKey($key): bool;

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getItem($key);

    /**
     * @return mixed
     */
    public function getLast();

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function mergeArray(array $data): CollectionInterface;


}
