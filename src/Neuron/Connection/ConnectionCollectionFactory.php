<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Connection;

use NN\Collection\CollectionFactoryInterface;

class ConnectionCollectionFactory implements ConnectionCollectionFactoryInterface
{
    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;

    /**
     * ConnectionCollectionFactory constructor.
     *
     * @param CollectionFactoryInterface $collectionFactory
     */
    public function __construct(CollectionFactoryInterface $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createConnectionCollection(): ConnectionCollectionInterface
    {
        return new ConnectionCollection(
            $this->collectionFactory->createCollection(),
            $this->collectionFactory->createCollection(),
            $this->collectionFactory->createCollection()
        );
    }
}