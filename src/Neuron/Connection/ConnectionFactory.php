<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Connection;

use NN\Neuron\NeuronInterface;

class ConnectionFactory implements ConnectionFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createNeuronConnection(NeuronInterface $from, NeuronInterface $to, ?float $weight = null): ConnectionInterface
    {
        $connection = new Connection($from, $to);

        if (null !== $weight) {
            $connection->setWeight($weight);
        }

        return $connection;
    }
}