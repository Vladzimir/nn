<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests;

use NN\Activation\ActivationFactory;
use NN\Activation\ActivationFactoryInterface;
use NN\Collection\CollectionFactory;
use NN\Collection\CollectionFactoryInterface;
use NN\Layer\LayerFactory;
use NN\Layer\LayerFactoryInterface;
use NN\Network\Architecture\LstmFactory;
use NN\Network\Architecture\PerceptronFactory;
use NN\Network\Network;
use NN\Network\NetworkFactoryInterface;
use NN\Network\NetworkInterface;
use NN\Neuron\Connection\ConnectionCollectionFactory;
use NN\Neuron\Connection\ConnectionCollectionFactoryInterface;
use NN\Neuron\Connection\ConnectionFactory;
use NN\Neuron\Connection\ConnectionFactoryInterface;
use NN\Neuron\Error\ErrorFactory;
use NN\Neuron\Error\ErrorFactoryInterface;
use NN\Neuron\NeuronFactory;
use NN\Neuron\NeuronFactoryInterface;
use NN\Neuron\Trace\TraceFactory;
use NN\Neuron\Trace\TraceFactoryInterface;
use NN\Trainer\Cost\CostFactory;
use NN\Trainer\Cost\CostFactoryInterface;
use NN\Trainer\TrainerFactory;
use NN\Trainer\TrainerFactoryInterface;
use NN\Traits\RandomTrait;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    use RandomTrait;

    /**
     * @param int $input
     * @param int $output
     * @return NetworkInterface
     */
    protected function getNetwork(int $input = 2, int $output = 1): NetworkInterface
    {
        $inputCollection = $this->getCollectionFactory()->createCollection();
        $outputCollection = $this->getCollectionFactory()->createCollection();
        $hiddenCollection = $this->getCollectionFactory()->createCollection();

        for ($i = 0; $i < $input; ++$i) {
            $inputCollection->add($this->getNeuronFactory()->createNeuron());
        }

        for ($i = 0; $i < $output; ++$i) {
            $outputCollection->add($this->getNeuronFactory()->createNeuron());
        }

        $inputLayer = $this->getLayerFactory()->createLayer($inputCollection);
        $outputLayer = $this->getLayerFactory()->createLayer($outputCollection);

        return new Network($inputLayer, $hiddenCollection, $outputLayer);
    }

    /**
     * @return CollectionFactoryInterface
     */
    protected function getCollectionFactory(): CollectionFactoryInterface
    {
        return new CollectionFactory();
    }

    /**
     * @return ActivationFactoryInterface
     */
    protected function getActivationFactory(): ActivationFactoryInterface
    {
        return new ActivationFactory();
    }

    /**
     * @return ErrorFactoryInterface
     */
    protected function getErrorFactory(): ErrorFactoryInterface
    {
        return new ErrorFactory();
    }

    /**
     * @return TraceFactoryInterface
     */
    protected function getTraceFactory(): TraceFactoryInterface
    {
        return new TraceFactory();
    }

    /**
     * @return ConnectionFactoryInterface
     */
    protected function getNeuronConnectionFactory(): ConnectionFactoryInterface
    {
        return new ConnectionFactory();
    }

    /**
     * @return NeuronFactoryInterface
     */
    protected function getNeuronFactory(): NeuronFactoryInterface
    {
        return new NeuronFactory(
            $this->getActivationFactory(),
            $this->getErrorFactory(),
            $this->getTraceFactory(),
            $this->getCollectionFactory(),
            $this->getNeuronConnectionCollectionFactory(),
            $this->getNeuronConnectionFactory()
        );
    }

    /**
     * @return ConnectionCollectionFactoryInterface
     */
    protected function getNeuronConnectionCollectionFactory(): ConnectionCollectionFactoryInterface
    {
        return new ConnectionCollectionFactory($this->getCollectionFactory());
    }

    /**
     * @return \NN\Layer\Connection\ConnectionFactoryInterface
     */
    protected function getLayerConnectionFactory(): \NN\Layer\Connection\ConnectionFactoryInterface
    {
        return new \NN\Layer\Connection\ConnectionFactory($this->getCollectionFactory());
    }

    /**
     * @return LayerFactoryInterface
     */
    protected function getLayerFactory(): LayerFactoryInterface
    {
        return new LayerFactory($this->getCollectionFactory(), $this->getLayerConnectionFactory());
    }

    /**
     * @return CostFactoryInterface
     */
    protected function getCostFactory(): CostFactoryInterface
    {
        return new CostFactory();
    }

    /**
     * @return TrainerFactoryInterface
     */
    protected function getTrainerFactory(): TrainerFactoryInterface
    {
        return new TrainerFactory();
    }

    /**
     * @return NetworkFactoryInterface
     */
    protected function getPerceptronFactory(): NetworkFactoryInterface
    {
        return new PerceptronFactory(
            $this->getCollectionFactory(),
            $this->getNeuronFactory(),
            $this->getLayerFactory()
        );
    }

    /**
     * @return NetworkFactoryInterface
     */
    protected function getLstmFactory(): NetworkFactoryInterface
    {
        return new LstmFactory(
            $this->getCollectionFactory(),
            $this->getNeuronFactory(),
            $this->getLayerFactory()
        );
    }
}