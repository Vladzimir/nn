<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer\Connection;

use NN\Collection\CollectionInterface;
use NN\IdInterface;
use NN\Layer\LayerInterface;

interface ConnectionInterface extends IdInterface
{
    /**
     * @return LayerInterface
     */
    public function getFrom(): LayerInterface;

    /**
     * @return LayerInterface
     */
    public function getTo(): LayerInterface;

    /**
     * @return bool
     */
    public function isSelfConnection(): bool;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return int
     */
    public function getNeuronConnectionSize(): int;

    /**
     * @return CollectionInterface
     */
    public function getNeuronConnections(): CollectionInterface;

    /**
     * @return array
     */
    public function getGatedFrom(): array;

    /**
     * @param array $gatedFrom
     * @return ConnectionInterface
     */
    public function setGatedFrom(array $gatedFrom): self;

    /**
     * @param LayerInterface $layer
     * @param string $gateType
     * @return ConnectionInterface
     */
    public function addGatedFrom(LayerInterface $layer, string $gateType): self;
}