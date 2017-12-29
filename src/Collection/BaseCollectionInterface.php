<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Collection;

use NN\IdInterface;

interface BaseCollectionInterface extends \Iterator, \Countable, IdInterface
{
    /**
     * @return BaseCollectionInterface
     */
    public function clear(): self;
}