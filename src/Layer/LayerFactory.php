<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer;

use NN\Collection\CollectionFactoryInterface;
use NN\Collection\CollectionInterface;
use NN\Layer\Connection\ConnectionFactoryInterface;

class LayerFactory implements LayerFactoryInterface
{
    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;

    /**
     * @var ConnectionFactoryInterface
     */
    protected $connectionFactory;

    /**
     * LayerFactory constructor.
     *
     * @param CollectionFactoryInterface $collectionFactory
     * @param ConnectionFactoryInterface $connectionFactory
     */
    public function __construct(CollectionFactoryInterface $collectionFactory, ConnectionFactoryInterface $connectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * @param CollectionInterface $neurons
     * @return LayerInterface
     */
    public function createLayer(CollectionInterface $neurons): LayerInterface
    {
        return new Layer($neurons, $this->collectionFactory->createCollection(), $this->connectionFactory);
    }
}