<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Collection;

interface CollectionFactoryInterface
{
    /**
     * @return CollectionInterface
     */
    public function createCollection(): CollectionInterface;
}