<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Connection;

use NN\Collection\BaseCollectionInterface;
use NN\Collection\CollectionInterface;
use NN\Traits\IdTrait;

class ConnectionCollection extends \AppendIterator implements ConnectionCollectionInterface
{
    protected const TYPE_INPUTS = 'INPUTS';

    protected const TYPE_PROJECTED = 'PROJECTED';

    protected const TYPE_GATED = 'GATED';

    /**
     * @var array
     */
    protected $types = [];

    use IdTrait;

    /**
     * ConnectionCollection constructor.
     *
     * @param CollectionInterface $inputs
     * @param CollectionInterface $projected
     * @param CollectionInterface $gated
     */
    public function __construct(CollectionInterface $inputs, CollectionInterface $projected, CollectionInterface $gated)
    {
        parent::__construct();

        $this->types[self::TYPE_INPUTS] = $inputs->getId();
        $this->types[self::TYPE_PROJECTED] = $projected->getId();
        $this->types[self::TYPE_GATED] = $gated->getId();

        $this->append($inputs);
        $this->append($projected);
        $this->append($gated);
    }

    public function __destruct()
    {
        unset($this->types);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        $count = 0;

        /** @var CollectionInterface $iterator */
        foreach ($this->getArrayIterator() as $iterator) {
            $count += $iterator->count();
        }

        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): BaseCollectionInterface
    {
        /** @var CollectionInterface $iterator */
        foreach ($this->getArrayIterator() as $iterator) {
            $iterator->clear();
        }

        return $this;
    }

    /**
     * @return CollectionInterface
     */
    public function getInputs(): CollectionInterface
    {
        return $this->findByType(self::TYPE_INPUTS);
    }

    /**
     * @return CollectionInterface
     */
    public function getProjected(): CollectionInterface
    {
        return $this->findByType(self::TYPE_PROJECTED);
    }

    /**
     * @return CollectionInterface
     */
    public function getGated(): CollectionInterface
    {
        return $this->findByType(self::TYPE_GATED);
    }

    /**
     * @param string $type
     * @return CollectionInterface|null
     */
    protected function findByType(string $type): ?CollectionInterface
    {
        /** @var CollectionInterface $iterator */
        foreach ($this->getArrayIterator() as $iterator) {
            if ($iterator->getId() === $this->types[$type]) {
                return $iterator;
            }
        }

        return null;
    }
}