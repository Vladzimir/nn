<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer;

use NN\Collection\CollectionInterface;

interface LayerInterface extends BaseLayerInterface, \Countable
{
    /**
     * @return CollectionInterface
     */
    public function getNeurons(): CollectionInterface;

    /**
     * @return CollectionInterface
     */
    public function getConnections(): CollectionInterface;

    /**
     * true or false whether the whole layer is self-connected or not
     * @return bool
     */
    public function isSelfConnected(): bool;

    /**
     * Returns layer connection type or null whether the layer is connected to another layer (parameter)
     * @param LayerInterface $layer
     * @return null|string
     */
    public function connected(LayerInterface $layer): ?string;
}