<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Network;

use NN\Activation\ActivationFactoryInterface;
use NN\Collection\CollectionFactoryInterface;
use NN\Collection\CollectionInterface;
use NN\Layer\LayerFactoryInterface;
use NN\Neuron\NeuronFactoryInterface;
use NN\Traits\RandomTrait;

abstract class NetworkFactory implements NetworkFactoryInterface
{
    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;

    /**
     * @var NeuronFactoryInterface
     */
    protected $neuronFactory;

    /**
     * @var LayerFactoryInterface
     */
    protected $layerFactory;

    use RandomTrait;

    /**
     * NetworkFactory constructor.
     *
     * @param CollectionFactoryInterface $collectionFactory
     * @param NeuronFactoryInterface $neuronFactory
     * @param LayerFactoryInterface $layerFactory
     */
    public function __construct(
        CollectionFactoryInterface $collectionFactory,
        NeuronFactoryInterface $neuronFactory,
        LayerFactoryInterface $layerFactory)
    {
        $this->collectionFactory = $collectionFactory;
        $this->neuronFactory = $neuronFactory;
        $this->layerFactory = $layerFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function createNeuronCollection(int $size, array $options = []): CollectionInterface
    {
        $options = array_merge([
            'activation' => ActivationFactoryInterface::SIGMOID,
            'bias' => 0.0
        ], $options);

        $collection = $this->collectionFactory->createCollection();

        for ($i = 0; $i < $size; ++$i) {
            $collection->add(
                $this
                    ->neuronFactory
                    ->createNeuron($options['activation'])
                    ->setBias($options['bias'])
            );
        }

        return $collection;
    }
}