<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer\Connection;

use NN\Layer\LayerInterface;

interface ConnectionFactoryInterface
{
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
        ?float $weight = null): ConnectionInterface;
}