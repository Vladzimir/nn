<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Connection;

use NN\Collection\BaseCollectionInterface;
use NN\Collection\CollectionInterface;

interface ConnectionCollectionInterface extends BaseCollectionInterface
{
    /**
     * @return CollectionInterface
     */
    public function getInputs(): CollectionInterface;

    /**
     * @return CollectionInterface
     */
    public function getProjected(): CollectionInterface;

    /**
     * @return CollectionInterface
     */
    public function getGated(): CollectionInterface;
}