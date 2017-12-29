<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer\Connection;

use NN\Collection\CollectionFactoryInterface;
use NN\Layer\LayerInterface;

class ConnectionFactory implements ConnectionFactoryInterface
{
    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;

    /**
     * ConnectionFactory constructor.
     *
     * @param CollectionFactoryInterface $collectionFactory
     */
    public function __construct(CollectionFactoryInterface $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param LayerInterface $from
     * @param LayerInterface $to
     * @param null|string $type
     * @param float|null $weight
     * @return ConnectionInterface
     */
    public function createLayerConnection(
        LayerInterface $from,
        LayerInterface $to,
        ?string $type = null,
        ?float $weight = null): ConnectionInterface
    {
        return new Connection($from, $to, $this->collectionFactory->createCollection(), $type, $weight);
    }
}