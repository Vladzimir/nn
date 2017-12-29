<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Collection;

use NN\IdInterface;

interface CollectionInterface extends \ArrayAccess, BaseCollectionInterface
{
    /**
     * @return IdInterface|null
     */
    public function end(): ?IdInterface;

    /**
     * @return IdInterface|null
     */
    public function prev(): ?IdInterface;

    /**
     * @param mixed $key
     * @return IdInterface|null
     */
    public function get($key): ?IdInterface;

    /**
     * @param IdInterface $item
     * @return CollectionInterface
     */
    public function add(IdInterface $item): self;

    /**
     * @param IdInterface|int $item
     * @return CollectionInterface
     */
    public function remove($item): self;

    /**
     * @param IdInterface|int $item
     * @return bool
     */
    public function contains($item): bool;

    /**
     * @param IdInterface|int $id
     * @return IdInterface|null
     */
    public function findById($id): ?IdInterface;

    /**
     * @param callable $condition
     * @return IdInterface|null
     */
    public function find(callable $condition): ?IdInterface;

    /**
     * @param callable $condition
     * @return mixed|null return null if no index found
     */
    public function findKey(callable $condition);

    /**
     * @param array $items
     * @return CollectionInterface
     */
    public function reset(array $items = []): self;
}