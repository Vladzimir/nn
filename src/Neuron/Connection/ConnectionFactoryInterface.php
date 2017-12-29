<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Connection;

use NN\Neuron\NeuronInterface;

interface ConnectionFactoryInterface
{
    /**
     * @param NeuronInterface $from
     * @param NeuronInterface $to
     * @param float|null $weight
     * @return ConnectionInterface
     */
    public function createNeuronConnection(NeuronInterface $from, NeuronInterface $to, ?float $weight = null): ConnectionInterface;
}