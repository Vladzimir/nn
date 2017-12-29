<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer;

use NN\IdInterface;
use NN\Layer\Connection\ConnectionInterface;

interface BaseLayerInterface extends IdInterface
{
    public const TYPE_CONNECTION_ALL_TO_ALL = 'ALL TO ALL';

    public const TYPE_CONNECTION_ONE_TO_ONE = 'ONE TO ONE';

    public const TYPE_CONNECTION_ALL_TO_ELSE = 'ALL TO ELSE';

    public const TYPE_GATE_INPUT = 'INPUT';

    public const TYPE_GATE_OUTPUT = 'OUTPUT';

    public const TYPE_GATE_ONE_TO_ONE = 'ONE TO ONE';

    /**
     * activates all the neurons in the layer
     *
     * @param float[]|null $input
     * @return float[]
     * @throws \Throwable
     */
    public function activate(?array $input = null): array;

    /**
     * propagates the error on all the neurons of the layer
     *
     * @param float|null $rate
     * @param float[]|null $target
     * @return BaseLayerInterface
     * @throws \Throwable
     */
    public function propagate(?float $rate = null, ?array $target = null): self;

    /**
     * projects a connection from this layer to another one
     *
     * @param BaseLayerInterface $layer
     * @param null|string $type
     * @param float|null $weight
     * @return ConnectionInterface|null
     * @throws \Throwable
     */
    public function project(BaseLayerInterface $layer, ?string $type = null, ?float $weight = null): ?ConnectionInterface;

    /**
     * gates a connection between two layers
     *
     * @param ConnectionInterface $connection
     * @param string $gateType
     * @return BaseLayerInterface
     * @throws \Throwable
     */
    public function gate(ConnectionInterface $connection, string $gateType): self;

    /**
     * clears all the neurons in the layer
     * @return BaseLayerInterface
     */
    public function clear(): self;

    /**
     * resets all the neurons in the layer
     * @return BaseLayerInterface
     */
    public function reset(): self;
}