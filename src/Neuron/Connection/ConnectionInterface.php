<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Connection;

use NN\IdInterface;
use NN\Neuron\NeuronInterface;

interface ConnectionInterface extends IdInterface
{
    public const TYPE_SELF_CONNECTED = 'SELF CONNECTED';

    public const TYPE_INPUTS = 'INPUTS';

    public const TYPE_PROJECTED = 'PROJECTED';

    public const TYPE_GATED = 'GATED';

    /**
     * @return NeuronInterface
     */
    public function getFrom(): NeuronInterface;

    /**
     * @return NeuronInterface
     */
    public function getTo(): NeuronInterface;

    /**
     * @return NeuronInterface|null
     */
    public function getGater(): ?NeuronInterface;

    /**
     * @param NeuronInterface|null $gater
     * @return ConnectionInterface
     */
    public function setGater(?NeuronInterface $gater = null): self;

    /**
     * @return float
     */
    public function getGain(): float;

    /**
     * @param float $gain
     * @return ConnectionInterface
     */
    public function setGain(float $gain): self;

    /**
     * @return ConnectionInterface
     */
    public function initRandomWeight(): self;

    /**
     * @param float $weight
     * @return ConnectionInterface
     */
    public function setWeight(float $weight): self;

    /**
     * @return float
     */
    public function getWeight(): float;

    /**
     * @return float
     */
    public function getWeightDotGain(): float;
}