<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron;

use NN\Activation\ActivationFactoryInterface;
use NN\Collection\CollectionFactoryInterface;
use NN\Neuron\Connection\ConnectionCollectionFactoryInterface;
use NN\Neuron\Connection\ConnectionFactoryInterface;
use NN\Neuron\Error\ErrorFactoryInterface;
use NN\Neuron\Trace\TraceFactoryInterface;

class NeuronFactory implements NeuronFactoryInterface
{
    /**
     * @var ActivationFactoryInterface
     */
    protected $activationFactory;

    /**
     * @var ErrorFactoryInterface
     */
    protected $errorFactory;

    /**
     * @var TraceFactoryInterface
     */
    protected $traceFactory;

    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;

    /**
     * @var ConnectionCollectionFactoryInterface
     */
    protected $connectionCollectionFactory;

    /**
     * @var ConnectionFactoryInterface
     */
    protected $connectionFactory;

    public function __construct(
        ActivationFactoryInterface $activationFactory,
        ErrorFactoryInterface $errorFactory,
        TraceFactoryInterface $traceFactory,
        CollectionFactoryInterface $collectionFactory,
        ConnectionCollectionFactoryInterface $connectionCollectionFactory,
        ConnectionFactoryInterface $connectionFactory)
    {
        $this->activationFactory = $activationFactory;
        $this->errorFactory = $errorFactory;
        $this->traceFactory = $traceFactory;
        $this->collectionFactory = $collectionFactory;
        $this->connectionCollectionFactory = $connectionCollectionFactory;
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * @param string $activationFunctionName
     * @return NeuronInterface
     */
    public function createNeuron(string $activationFunctionName = ActivationFactoryInterface::SIGMOID): NeuronInterface
    {
        return new Neuron(
            $this->activationFactory->createActivation($activationFunctionName),
            $this->errorFactory->createError(),
            $this->traceFactory->createTrace(),
            $this->connectionFactory,
            $this->connectionCollectionFactory->createConnectionCollection(),
            $this->collectionFactory->createCollection()
        );
    }
}