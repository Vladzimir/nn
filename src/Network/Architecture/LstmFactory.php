<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Network\Architecture;

use NN\Layer\BaseLayerInterface;
use NN\Layer\LayerInterface;
use NN\Network\Network;
use NN\Network\NetworkException;
use NN\Network\NetworkFactory;
use NN\Network\NetworkInterface;

class LstmFactory extends NetworkFactory
{
    /**
     * {@inheritdoc}
     */
    public function createNetwork(int $input, array $hidden, int $output, array $options = []): NetworkInterface
    {
        if ($input <= 0 || $output <= 0 || 0 === count($hidden)) {
            throw new NetworkException('Not enough layers (minimum 3) !!!');
        }

        $options = array_merge([
            'peepholes' => BaseLayerInterface::TYPE_CONNECTION_ALL_TO_ALL,
            'hidden_to_hidden' => false,
            'output_to_hidden' => false,
            'output_to_gates' => false,
            'input_to_output' => true,
        ], $options);

        $network = new Network(
            $this->layerFactory->createLayer($this->createNeuronCollection($input)),
            $this->collectionFactory->createCollection(),
            $this->layerFactory->createLayer($this->createNeuronCollection($output))
        );

        /** @var LayerInterface|null $previous */
        $previous = null;

        foreach ($hidden as $size) {
            // generate memory blocks (memory cell and respective gates)
            $inputGate = $this->layerFactory->createLayer($this->createNeuronCollection($size, ['bias' => 1.0]));
            $forgetGate = $this->layerFactory->createLayer($this->createNeuronCollection($size, ['bias' => 1.0]));
            $memoryCell = $this->layerFactory->createLayer($this->createNeuronCollection($size));
            $outputGate = $this->layerFactory->createLayer($this->createNeuronCollection($size, ['bias' => 1.0]));

            $network->getHidden()->add($inputGate);
            $network->getHidden()->add($forgetGate);
            $network->getHidden()->add($memoryCell);
            $network->getHidden()->add($outputGate);

            // connections from input layer
            $input = $network->getInput()->project($memoryCell);
            $network->getInput()->project($inputGate);
            $network->getInput()->project($forgetGate);
            $network->getInput()->project($outputGate);

            // connections from previous memory-block layer to this one
            if (null !== $previous) {
                $cell = $previous->project($memoryCell);
                $previous->project($inputGate);
                $previous->project($forgetGate);
                $previous->project($outputGate);
            }

            // connections from memory cell
            $output = $memoryCell->project($network->getOutput());

            // self-connection
            $self = $memoryCell->project($memoryCell);

            // hidden to hidden recurrent connection
            if ($options['hidden_to_hidden']) {
                $memoryCell->project($memoryCell, BaseLayerInterface::TYPE_CONNECTION_ALL_TO_ELSE);
            }

            // out to hidden recurrent connection
            if ($options['output_to_hidden']) {
                $network->getOutput()->project($memoryCell);
            }

            // out to gates recurrent connection
            if ($options['output_to_gates']) {
                $network->getOutput()->project($inputGate);
                $network->getOutput()->project($outputGate);
                $network->getOutput()->project($forgetGate);
            }

            // peepholes
            $memoryCell->project($inputGate, $options['peepholes']);
            $memoryCell->project($forgetGate, $options['peepholes']);
            $memoryCell->project($outputGate, $options['peepholes']);

            // gates
            $inputGate->gate($input, BaseLayerInterface::TYPE_GATE_INPUT);
            $forgetGate->gate($self, BaseLayerInterface::TYPE_GATE_ONE_TO_ONE);
            $outputGate->gate($output, BaseLayerInterface::TYPE_GATE_OUTPUT);

            if (null !== $previous && isset($cell)) {
                $inputGate->gate($cell, BaseLayerInterface::TYPE_GATE_INPUT);
            }

            $previous = $memoryCell;
        }

        // input to output direct connection
        if ($options['input_to_output']) {
            $network->getInput()->project($network->getOutput());
        }

        return $network;
    }
}