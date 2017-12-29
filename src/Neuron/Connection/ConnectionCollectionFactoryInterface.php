<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Connection;

interface ConnectionCollectionFactoryInterface
{
    /**
     * @return ConnectionCollectionInterface
     */
    public function createConnectionCollection(): ConnectionCollectionInterface;
}