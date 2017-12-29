<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron;

use NN\Activation\ActivationFactoryInterface;

interface NeuronFactoryInterface
{
    /**
     * @param string $activationFunctionName
     * @return NeuronInterface
     */
    public function createNeuron(string $activationFunctionName = ActivationFactoryInterface::SIGMOID): NeuronInterface;
}