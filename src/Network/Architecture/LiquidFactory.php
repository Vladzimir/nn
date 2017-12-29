<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Network\Architecture;

use NN\Network\Network;
use NN\Network\NetworkException;
use NN\Network\NetworkFactory;
use NN\Network\NetworkInterface;
use NN\Neuron\NeuronInterface;

class LiquidFactory extends NetworkFactory
{
    /**
     * {@inheritdoc}
     */
    public function createNetwork(int $input, array $hidden, int $output, array $options = []): NetworkInterface
    {
        if ($input <= 0 || $output <= 0 || 0 === count($hidden)) {
            throw new NetworkException('Not enough layers (required 3) !!!');
        }

        if (false === isset($options['connections'])
            || false === is_int($options['connections'])
            || $options['connections'] <= 0) {
            throw new NetworkException('Options connections number is required.');
        }

        if (false === isset($options['gates'])
            || false === is_int($options['gates'])
            || $options['gates'] <= 0) {
            throw new NetworkException('Options gates number is required.');
        }

        $network = new Network(
            $this->layerFactory->createLayer($this->createNeuronCollection($input)),
            $this->collectionFactory->createCollection(),
            $this->layerFactory->createLayer($this->createNeuronCollection($output))
        );

        $hiddenLayer = $this->layerFactory->createLayer($this->createNeuronCollection($hidden[0]));

        $network->getHidden()->add($hiddenLayer);

        // make connections and gates randomly among the neurons
        $connections = [];

        for($i = 0; $i < $options['connections']; ++$i) {
            // connect two random neurons
            /** @var NeuronInterface $from */
            $from = $hiddenLayer->getNeurons()->get(rand(0, $hiddenLayer->count() - 1));

            /** @var NeuronInterface $to */
            $to = $hiddenLayer->getNeurons()->get(rand(0, $hiddenLayer->count() - 1));

            $connections[] = $from->project($to);
        }

        for ($i = 0; $i < $options['gates']; ++$i) {
            // pick a random gater neuron
            /** @var NeuronInterface $gater */
            $gater = $hiddenLayer->getNeurons()->get(rand(0, $hiddenLayer->count() - 1));

            // pick a random connection to gate
            $connection = rand(0, count($connections) - 1);

            // let the gater gate the connection
            $gater->gate($connections[$connection]);
        }

        // connect the layers
        $network->getInput()->project($hiddenLayer);
        $hiddenLayer->project($network->getOutput());

        return $network;
    }
}