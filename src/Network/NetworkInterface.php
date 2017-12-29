<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Network;

use NN\Collection\CollectionInterface;
use NN\Layer\BaseLayerInterface;
use NN\Layer\LayerInterface;

interface NetworkInterface extends BaseLayerInterface
{
    /**
     * @return LayerInterface
     */
    public function getInput(): LayerInterface;

    /**
     * @return LayerInterface
     */
    public function getOutput(): LayerInterface;

    /**
     * @return CollectionInterface
     */
    public function getHidden(): CollectionInterface;
}