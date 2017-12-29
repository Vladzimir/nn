<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Network\Architecture;

use NN\Layer\BaseLayerInterface;
use NN\Network\Network;
use NN\Network\NetworkException;
use NN\Network\NetworkFactory;
use NN\Network\NetworkInterface;

class HopfieldFactory extends NetworkFactory
{
    /**
     * {@inheritdoc}
     */
    public function createNetwork(int $input, array $hidden, int $output, array $options = []): NetworkInterface
    {
        if ($input <= 0 || $output <= 0) {
            throw new NetworkException('Not enough layers (require 2) !!!');
        }

        $network = new Network(
            $this->layerFactory->createLayer($this->createNeuronCollection($input)),
            $this->collectionFactory->createCollection(),
            $this->layerFactory->createLayer($this->createNeuronCollection($output))
        );

        $network->getInput()->project($network->getOutput(), BaseLayerInterface::TYPE_CONNECTION_ALL_TO_ALL);

        return $network;
    }
}