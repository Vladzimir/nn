<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Network\Architecture;

use NN\Layer\LayerInterface;
use NN\Network\Network;
use NN\Network\NetworkException;
use NN\Network\NetworkFactory;
use NN\Network\NetworkInterface;

class PerceptronFactory extends NetworkFactory
{
    /**
     * {@inheritdoc}
     */
    public function createNetwork(int $input, array $hidden, int $output, array $options = []): NetworkInterface
    {
        if ($input <= 0 || $output <= 0 || 0 === count($hidden)) {
            throw new NetworkException('Not enough layers (minimum 3) !!!');
        }

        $network = new Network(
            $this->layerFactory->createLayer($this->createNeuronCollection($input)),
            $this->collectionFactory->createCollection(),
            $this->layerFactory->createLayer($this->createNeuronCollection($output))
        );

        foreach ($hidden as $size) {
            $layer = $this->layerFactory->createLayer($this->createNeuronCollection($size));
            $network->getHidden()->add($layer);
        }

        $previous = $network->getInput();

        /** @var LayerInterface $layer */
        foreach ($network->getHidden() as $layer) {
            $previous->project($layer);
            $previous = $layer;
        }

        $previous->project($network->getOutput());

        return $network;
    }
}