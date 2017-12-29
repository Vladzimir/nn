<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron;

use NN\Activation\ActivationInterface;
use NN\Collection\CollectionInterface;
use NN\IdInterface;
use NN\Neuron\Connection\ConnectionCollectionInterface;
use NN\Neuron\Connection\ConnectionInterface;
use NN\Neuron\Error\ErrorInterface;
use NN\Neuron\Trace\TraceInterface;

interface NeuronInterface extends IdInterface
{
    /**
     * @return ActivationInterface
     */
    public function getActivationFunction(): ActivationInterface;

    /**
     * @param float $activation
     * @return NeuronInterface
     */
    public function setActivation(float $activation = 0.0): self;

    /**
     * @return float
     */
    public function getActivation(): float;

    /**
     * @param float $derivative
     * @return NeuronInterface
     */
    public function setDerivative(float $derivative = 0.0): self;

    /**
     * @return float
     */
    public function getDerivative(): float;

    /**
     * @param float $bias
     * @return NeuronInterface
     */
    public function setBias(float $bias = 0.0): self;

    /**
     * @return float
     */
    public function getBias(): float;

    /**
     * @param float $state
     * @return NeuronInterface
     */
    public function setState(float $state = 0.0): self;

    /**
     * @return float
     */
    public function getState(): float;

    /**
     * @param float $oldState
     * @return NeuronInterface
     */
    public function setOldState(float $oldState = 0.0): self;

    /**
     * @return float
     */
    public function getOldState(): float;

    /**
     * @return ConnectionInterface
     */
    public function getSelfConnection(): ConnectionInterface;

    /**
     * @return ConnectionCollectionInterface
     */
    public function getConnections(): ConnectionCollectionInterface;

    /**
     * @return CollectionInterface
     */
    public function getNeighbors(): CollectionInterface;

    /**
     * @return ErrorInterface
     */
    public function getError(): ErrorInterface;

    /**
     * @return TraceInterface
     */
    public function getTrace(): TraceInterface;

    /**
     * @param float|null $input
     * @return float
     */
    public function activate(?float $input = null): float;

    /**
     * back-propagate the error
     *
     * @param float|null $rate
     * @param float|null $target
     * @return NeuronInterface
     */
    public function propagate(?float $rate = null, ?float $target = null): self;

    /**
     * @param NeuronInterface $neuron
     * @param float|null $weight
     * @return ConnectionInterface
     */
    public function project(NeuronInterface $neuron, ?float $weight = null): ConnectionInterface;

    /**
     * @param ConnectionInterface $connection
     * @return NeuronInterface
     */
    public function gate(ConnectionInterface $connection): self;

    /**
     * returns true or false whether the neuron is self-connected or not
     * @return bool
     */
    public function isSelfConnected(): bool;

    /**
     * Returns [type, ConnectionInterface] or null whether the neuron is connected to another neuron (parameter)
     *
     * @param NeuronInterface $neuron
     * @return array|null
     */
    public function connected(NeuronInterface $neuron): ?array;

    /**
     * clears all the traces (the neuron forgets it's context, but the connections remain intact)
     *
     * @return NeuronInterface
     */
    public function clear(): self;

    /**
     * @return NeuronInterface
     */
    public function reset(): self;
}