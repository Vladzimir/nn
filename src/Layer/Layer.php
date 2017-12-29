<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer;

use NN\Collection\CollectionInterface;
use NN\Layer\Connection\ConnectionFactoryInterface;
use NN\Layer\Connection\ConnectionInterface;
use NN\Network\NetworkInterface;
use NN\Neuron\NeuronInterface;
use NN\Neuron\Connection\ConnectionInterface as NeuronConnectionInterface;
use NN\Traits\IdTrait;

class Layer implements LayerInterface
{
    /**
     * @var CollectionInterface
     */
    protected $neurons;

    /**
     * @var CollectionInterface
     */
    protected $connections;

    /**
     * @var ConnectionFactoryInterface
     */
    protected $connectionFactory;

    use IdTrait;

    /**
     * Layer constructor.
     * @param CollectionInterface $neurons
     * @param CollectionInterface $connections
     * @param ConnectionFactoryInterface $connectionFactory
     */
    public function __construct(
        CollectionInterface $neurons,
        CollectionInterface $connections,
        ConnectionFactoryInterface $connectionFactory)
    {
        $this->neurons = $neurons;
        $this->connections = $connections;
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * activates all the neurons in the layer
     *
     * @param float[]|null $input
     * @return float[]
     * @throws \Throwable
     */
    public function activate(?array $input = null): array
    {
        $activations = [];

        if (null === $input) {
            /** @var NeuronInterface $neuron */
            foreach ($this->getNeurons() as $neuron) {
                $activations[] = $neuron->activate();
            }
        } else {
            if ($this->count() !== count($input)) {
                throw new LayerException('INPUT size and LAYER size must be the same to activate!');
            }

            /** @var NeuronInterface $neuron */
            foreach ($this->getNeurons() as $i => $neuron) {
                $activations[] = $neuron->activate($input[$i]);
            }
        }

        return $activations;
    }

    /**
     * propagates the error on all the neurons of the layer
     *
     * @param float|null $rate
     * @param float[]|null $target
     * @return BaseLayerInterface
     * @throws \Throwable
     */
    public function propagate(?float $rate = null, ?array $target = null): BaseLayerInterface
    {
        if (null === $target) {
            for ($this->getNeurons()->end(); null !== $this->getNeurons()->key(); $this->getNeurons()->prev()) {
                $this->getNeurons()->current()->propagate($rate);
            }
        } else {
            if ($this->count() !== count($target)) {
                throw new LayerException('TARGET size and LAYER size must be the same to propagate!');
            }

            for ($i = count($target) - 1, $this->getNeurons()->end();
                 $i >= 0 && null !== $this->getNeurons()->key();
                 --$i, $this->getNeurons()->prev()) {
                $this->getNeurons()->current()->propagate($rate, $target[$i]);
            }
        }

        return $this;
    }

    /**
     * projects a connection from this layer to another one
     *
     * @param BaseLayerInterface $layer
     * @param null|string $type
     * @param float|null $weight
     * @return ConnectionInterface|null
     * @throws \Throwable
     */
    public function project(BaseLayerInterface $layer, ?string $type = null, ?float $weight = null): ?ConnectionInterface
    {
        if ($layer instanceof NetworkInterface) {
            $layer = $layer->getInput();
        }

        if (false === ($layer instanceof LayerInterface)) {
            throw new LayerException('Invalid argument, you can only project connections to LAYERS and NETWORKS!');
        }

        /** @var LayerInterface $layer */

        if ($this->connected($layer)) {
            return null;
        }

        return $this->connectionFactory->createLayerConnection($this, $layer, $type, $weight);
    }

    /**
     * gates a connection between two layers
     *
     * @param ConnectionInterface $connection
     * @param string $gateType
     * @return BaseLayerInterface
     * @throws \Throwable
     */
    public function gate(ConnectionInterface $connection, string $gateType): BaseLayerInterface
    {
        switch ($gateType) {
            case self::TYPE_GATE_INPUT:
                if ($this->count() !== $connection->getTo()->count()) {
                    throw new LayerException('GATER layer and CONNECTION.TO layer must be the same size in order to gate!');
                }

                /** @var NeuronInterface $neuron */
                foreach ($connection->getTo()->getNeurons() as $i => $neuron) {
                    /** @var NeuronInterface $gater */
                    if ($gater = $this->getNeurons()->get($i)) {
                        /** @var NeuronConnectionInterface $gated */
                        foreach ($neuron->getConnections()->getInputs() as $gated) {
                            if ($connection->getNeuronConnections()->contains($gated)) {
                                $gater->gate($gated);
                            }
                        }
                    }
                }
                break;
            case self::TYPE_GATE_OUTPUT:
                if ($this->count() !== $connection->getFrom()->count()) {
                    throw new LayerException('GATER layer and CONNECTION.FROM layer must be the same size in order to gate!');
                }

                /** @var NeuronInterface $neuron */
                foreach ($connection->getFrom()->getNeurons() as $i => $neuron) {
                    /** @var NeuronInterface $gater */
                    if ($gater = $this->getNeurons()->get($i)) {
                        /** @var NeuronConnectionInterface $gated */
                        foreach ($neuron->getConnections()->getProjected() as $gated) {
                            if ($connection->getNeuronConnections()->contains($gated)) {
                                $gater->gate($gated);
                            }
                        }
                    }
                }
                break;
            case self::TYPE_GATE_ONE_TO_ONE:
                if ($this->count() !== $connection->getNeuronConnectionSize()) {
                    throw new LayerException('The number of GATER UNITS must be the same as the number of CONNECTIONS to gate!');
                }

                /** @var NeuronConnectionInterface $gated */
                foreach ($connection->getNeuronConnections() as $i => $gated) {
                    /** @var NeuronInterface $gater */
                    if ($gater = $this->getNeurons()->get($i)) {
                        $gater->gate($gated);
                    }
                }
                break;
        }

        $connection->addGatedFrom($this, $gateType);

        return $this;
    }

    /**
     * clears all the neurons in the layer
     * @return BaseLayerInterface
     */
    public function clear(): BaseLayerInterface
    {
        /** @var NeuronInterface $neuron */
        foreach ($this->getNeurons() as $neuron) {
            $neuron->clear();
        }

        return $this;
    }

    /**
     * resets all the neurons in the layer
     * @return BaseLayerInterface
     */
    public function reset(): BaseLayerInterface
    {
        /** @var NeuronInterface $neuron */
        foreach ($this->getNeurons() as $neuron) {
            $neuron->reset();
        }

        return $this;
    }

    /**
     * Return number of neurons
     *
     * @return int
     */
    public function count(): int
    {
        return $this->getNeurons()->count();
    }

    /**
     * @return CollectionInterface
     */
    public function getNeurons(): CollectionInterface
    {
        return $this->neurons;
    }

    /**
     * @return CollectionInterface
     */
    public function getConnections(): CollectionInterface
    {
        return $this->connections;
    }

    /**
     * true or false whether the whole layer is self-connected or not
     * @return bool
     */
    public function isSelfConnected(): bool
    {
        /** @var NeuronInterface $neuron */
        foreach ($this->getNeurons() as $neuron) {
            if (false === $neuron->isSelfConnected()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns layer connection type or null whether the layer is connected to another layer (parameter)
     * @param LayerInterface $layer
     * @return null|string
     */
    public function connected(LayerInterface $layer): ?string
    {
        // Check if ALL to ALL connection
        $connections = 0;

        /** @var NeuronInterface $from */
        foreach ($this->getNeurons() as $from) {
            /** @var NeuronInterface $to */
            foreach ($layer->getNeurons() as $to) {
                if (($connected = $from->connected($to))
                    && NeuronConnectionInterface::TYPE_PROJECTED === $connected[0]) {
                    $connections++;
                }
            }
        }

        if (($this->count() * $layer->count()) === $connections) {
            return self::TYPE_CONNECTION_ALL_TO_ALL;
        }

        // Check if ONE to ONE connection
        $connections = 0;

        /** @var NeuronInterface $from */
        foreach ($this->getNeurons() as $i => $from) {
            /** @var NeuronInterface $to */
            if (($to = $layer->getNeurons()->get($i))
                && ($connected = $from->connected($to))
                && NeuronConnectionInterface::TYPE_PROJECTED === $connected[0]) {
                $connections++;
            }
        }

        if ($this->count() === $connections) {
            return self::TYPE_CONNECTION_ONE_TO_ONE;
        }

        return null;
    }
}