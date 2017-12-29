<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Connection;

use NN\Neuron\NeuronInterface;
use NN\Traits\IdTrait;
use NN\Traits\RandomTrait;

class Connection implements ConnectionInterface
{
    /**
     * @var NeuronInterface
     */
    protected $from;

    /**
     * @var NeuronInterface
     */
    protected $to;

    /**
     * @var NeuronInterface|null
     */
    protected $gater = null;

    /**
     * @var float
     */
    protected $gain = 1.0;

    /**
     * @var float
     */
    protected $weight = 0.0;

    use IdTrait;
    use RandomTrait;

    public function __construct(NeuronInterface $from, NeuronInterface $to)
    {
        $this->from = $from;
        $this->to = $to;

        $this->initRandomWeight();
    }

    /**
     * @return NeuronInterface
     */
    public function getFrom(): NeuronInterface
    {
        return $this->from;
    }

    /**
     * @return NeuronInterface
     */
    public function getTo(): NeuronInterface
    {
        return $this->to;
    }

    /**
     * @return NeuronInterface|null
     */
    public function getGater(): ?NeuronInterface
    {
        return $this->gater;
    }

    /**
     * @param NeuronInterface|null $gater
     * @return ConnectionInterface
     */
    public function setGater(?NeuronInterface $gater = null): ConnectionInterface
    {
        $this->gater = $gater;
        return $this;
    }

    /**
     * @return float
     */
    public function getGain(): float
    {
        return $this->gain;
    }

    /**
     * @param float $gain
     * @return ConnectionInterface
     */
    public function setGain(float $gain): ConnectionInterface
    {
        $this->gain = $gain;
        return $this;
    }

    /**
     * @param float $weight
     * @return ConnectionInterface
     */
    public function setWeight(float $weight): ConnectionInterface
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return ConnectionInterface
     */
    public function initRandomWeight(): ConnectionInterface
    {
        return $this->setWeight($this->getInitRandom());
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return float
     */
    public function getWeightDotGain(): float
    {
        return $this->getWeight() * $this->getGain();
    }
}