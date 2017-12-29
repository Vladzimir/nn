<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer;

use NN\Collection\CollectionInterface;

interface LayerFactoryInterface
{
    /**
     * @param CollectionInterface $neurons
     * @return LayerInterface
     */
    public function createLayer(CollectionInterface $neurons): LayerInterface;
}