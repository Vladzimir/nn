<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Collection;

class CollectionFactory implements CollectionFactoryInterface
{
    /**
     * @return CollectionInterface
     */
    public function createCollection(): CollectionInterface
    {
        return new Collection();
    }
}