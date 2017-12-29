<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Collection;

use NN\IdInterface;
use NN\Traits\IdTrait;

class Collection implements CollectionInterface
{
    /**
     * @var IdInterface[]
     */
    protected $items;

    use IdTrait;

    /**
     * Collection constructor.
     *
     * @param IdInterface[] $items
     */
    public function __construct(array $items = [])
    {
        $this->reset($items);
    }

    /**
     * Collection destructor
     */
    public function __destruct()
    {
        unset($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key): ?IdInterface
    {
        return $this->items[$key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function add(IdInterface $item): CollectionInterface
    {
        array_push($this->items, $item);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function findById($id): ?IdInterface
    {
        $id = $id instanceof IdInterface ? $id->getId() : $id;

        return $this->find(function(IdInterface $item) use($id) {
            return $item->getId() === $id;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function find(callable $condition): ?IdInterface
    {
        if (null !== $key = $this->findKey($condition)) {
            return $this->offsetGet($key);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function findKey(callable $condition)
    {
        foreach ($this->items as $key => $item) {
            if ($condition($item)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($item): bool
    {
        return null !== $this->findById($item);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($item): CollectionInterface
    {
        $id = $item instanceof IdInterface ? $item->getId() : $item;

        foreach ($this->items as $key => $item) {
            if ($item->getId() === $id) {
                $this->offsetUnset($key);
                break;
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): BaseCollectionInterface
    {
        return $this->reset();
    }

    /**
     * {@inheritdoc}
     */
    public function reset(array $items = []): CollectionInterface
    {
        $this->items = $items;
        $this->rewind();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function current(): IdInterface
    {
        return current($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        next($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function prev(): ?IdInterface
    {
        return prev($this->items) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function end(): ?IdInterface
    {
        return end($this->items) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->offsetExists($this->key());
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): IdInterface
    {
        return $this->items[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->items);
    }
}