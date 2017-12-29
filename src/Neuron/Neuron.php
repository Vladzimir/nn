<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron;

use NN\Activation\ActivationInterface;
use NN\Collection\CollectionInterface;
use NN\Neuron\Connection\ConnectionCollectionInterface;
use NN\Neuron\Connection\ConnectionFactoryInterface;
use NN\Neuron\Connection\ConnectionInterface;
use NN\Neuron\Error\ErrorInterface;
use NN\Neuron\Trace\TraceInterface;
use NN\Traits\IdTrait;
use NN\Traits\RandomTrait;

class Neuron implements NeuronInterface
{
    /**
     * @var ActivationInterface
     */
    protected $activationFunction;

    /**
     * @var ConnectionFactoryInterface
     */
    protected $connectionFactory;

    /**
     * @var float
     */
    protected $activation = 0.0;

    /**
     * @var float
     */
    protected $derivative = 0.0;

    /**
     * @var float
     */
    protected $bias = 0.0;

    /**
     * @var float
     */
    protected $state = 0.0;

    /**
     * @var float
     */
    protected $oldState = 0.0;

    /**
     * @var ConnectionInterface
     */
    protected $selfConnection;

    /**
     * @var ConnectionCollectionInterface
     */
    protected $connections;

    /**
     * @var CollectionInterface
     */
    protected $neighbors;

    /**
     * @var ErrorInterface
     */
    protected $error;

    /**
     * @var TraceInterface
     */
    protected $trace;

    use IdTrait;
    use RandomTrait;

    /**
     * Neuron constructor.
     * @param ActivationInterface $activationFunction
     * @param ErrorInterface $error
     * @param TraceInterface $trace
     * @param ConnectionFactoryInterface $connectionFactory
     * @param ConnectionCollectionInterface $connections
     * @param CollectionInterface $neighbors
     */
    public function __construct(
        ActivationInterface $activationFunction,
        ErrorInterface $error,
        TraceInterface $trace,
        ConnectionFactoryInterface $connectionFactory,
        ConnectionCollectionInterface $connections,
        CollectionInterface $neighbors)
    {
        $this->activationFunction = $activationFunction;
        $this->error = $error;
        $this->trace = $trace;
        $this->connectionFactory = $connectionFactory;
        $this->connections = $connections;
        $this->neighbors = $neighbors;

        $this->selfConnection = $connectionFactory->createNeuronConnection($this, $this, 0.0); // weight = 0 -> not connected

        $this->setBias($this->getInitRandom());
    }

    /**
     * @return ActivationInterface
     */
    public function getActivationFunction(): ActivationInterface
    {
        return $this->activationFunction;
    }

    /**
     * @param float $activation
     * @return NeuronInterface
     */
    public function setActivation(float $activation = 0.0): NeuronInterface
    {
        $this->activation = $activation;
        return $this;
    }

    /**
     * @return float
     */
    public function getActivation(): float
    {
        return $this->activation;
    }

    /**
     * @param float $derivative
     * @return NeuronInterface
     */
    public function setDerivative(float $derivative = 0.0): NeuronInterface
    {
        $this->derivative = $derivative;
        return $this;
    }

    /**
     * @return float
     */
    public function getDerivative(): float
    {
        return $this->derivative;
    }

    /**
     * @param float $bias
     * @return NeuronInterface
     */
    public function setBias(float $bias = 0.0): NeuronInterface
    {
        $this->bias = $bias;
        return $this;
    }

    /**
     * @return float
     */
    public function getBias(): float
    {
        return $this->bias;
    }

    /**
     * @param float $state
     * @return NeuronInterface
     */
    public function setState(float $state = 0.0): NeuronInterface
    {
        $this->setOldState($this->getState());
        $this->state = $state;
        return $this;
    }

    /**
     * @return float
     */
    public function getState(): float
    {
        return $this->state;
    }

    /**
     * @param float $oldState
     * @return NeuronInterface
     */
    public function setOldState(float $oldState = 0.0): NeuronInterface
    {
        $this->oldState = $oldState;
        return $this;
    }

    /**
     * @return float
     */
    public function getOldState(): float
    {
        return $this->oldState;
    }

    /**
     * @return ConnectionInterface
     */
    public function getSelfConnection(): ConnectionInterface
    {
        return $this->selfConnection;
    }

    /**
     * @return ConnectionCollectionInterface
     */
    public function getConnections(): ConnectionCollectionInterface
    {
        return $this->connections;
    }

    /**
     * @return CollectionInterface
     */
    public function getNeighbors(): CollectionInterface
    {
        return $this->neighbors;
    }

    /**
     * @return ErrorInterface
     */
    public function getError(): ErrorInterface
    {
        return $this->error;
    }

    /**
     * @return TraceInterface
     */
    public function getTrace(): TraceInterface
    {
        return $this->trace;
    }

    /**
     * @param float|null $input
     * @return float
     */
    public function activate(?float $input = null): float
    {
        if (null !== $input) {
            return $this
                ->setActivation($input)
                ->setDerivative()
                ->setBias()
                ->getActivation();
        }

        $state = $this->getSelfConnection()->getWeightDotGain() * $this->getState() + $this->getBias();

        /** @var ConnectionInterface $connection */
        foreach ($this->getConnections()->getInputs() as $connection) {
            $state += $connection->getFrom()->getActivation() * $connection->getWeightDotGain();
        }

        $this
            ->setState($state)
            ->setActivation($this->getActivationFunction()($state))
            ->setDerivative($this->getActivationFunction()($state, true));

        $influences = [];

        foreach ($this->getTrace()->getExtendedKeys() as $id) {
            /** @var NeuronInterface $neuron */
            $neuron = $this->getNeighbors()->findById($id);

            // if gated neuron's selfconnection is gated by this unit, the influence keeps track of the neuron's old state
            $influence = $neuron->getSelfConnection()->getGater() === $this ? $neuron->getOldState() : 0.0;

            // index runs over all the incoming connections to the gated neuron that are gated by this unit
            /** @var ConnectionInterface $connection */
            foreach ($this->getTrace()->getInfluences()[$neuron->getId()] as $connection) {
                $influence += $connection->getWeight() * $connection->getFrom()->getActivation();
            }

            $influences[$neuron->getId()] = $influence;
        }

        /** @var ConnectionInterface $input */
        foreach ($this->getConnections()->getInputs() as $input) {
            $eligibility = $this->getSelfConnection()->getWeightDotGain()
                * $this->getTrace()->eligibility($input->getId())
                + $input->getGain()
                * $input->getFrom()->getActivation();

            $this->getTrace()->addEligibility($input->getId(), $eligibility);

            foreach ($this->getTrace()->getExtendedKeys() as $id) {
                /** @var NeuronInterface $neuron */
                $neuron = $this->getNeighbors()->findById($id);

                $x = $neuron->getSelfConnection()->getWeightDotGain()
                    * $this->getTrace()->extended($id, $input->getId())
                    + $this->getDerivative()
                    * $this->getTrace()->eligibility($input->getId())
                    * $influences[$neuron->getId()];

                $this->getTrace()->addExtended($id, $input->getId(), $x);
            }
        }

        // update gated connection's gains
        /** @var ConnectionInterface $connection */
        foreach ($this->getConnections()->getGated() as $connection) {
            $connection->setGain($this->getActivation());
        }

        return $this->getActivation();
    }

    /**
     * back-propagate the error
     *
     * @param float|null $rate
     * @param float|null $target
     * @return NeuronInterface
     */
    public function propagate(?float $rate = null, ?float $target = null): NeuronInterface
    {
        // error accumulator
        $error = 0.0;

        // whether or not this neuron is in the output layer
        $isOutput = null !== $target;

        // output neurons get their error from the enviroment
        if ($isOutput) {
            $this
                ->getError()
                ->setResponsibility(
                    $this
                        ->getError()
                        ->setProjected($target - $this->getActivation())
                        ->getProjected()
                );
        } else { // the rest of the neuron compute their error responsibilities by backpropagation
            // error responsibilities from all the connections projected from this neuron
            /** @var ConnectionInterface $connection */
            foreach ($this->getConnections()->getProjected() as $connection) {
                $error += $connection->getTo()->getError()->getResponsibility() * $connection->getWeightDotGain();
            }

            $this->getError()->setProjected($this->getDerivative() * $error);

            $error = 0.0;

            // error responsibilities from all the connections gated by this neuron
            foreach ($this->getTrace()->getExtendedKeys() as $id) {
                /** @var NeuronInterface $neuron */
                $neuron = $this->getNeighbors()->findById($id);

                // if gated neuron's selfconnection is gated by this neuron
                $influence = $neuron->getSelfConnection()->getGater() === $this ? $neuron->getOldState() : 0.0;

                // index runs over all the connections to the gated neuron that are gated by this neuron
                /** @var ConnectionInterface $connection */
                foreach ($this->getTrace()->getInfluences()[$neuron->getId()] as $connection) { // captures the effect that the input connection of this neuron have, on a neuron which its input/s is/are gated by this neuron
                    $influence += $connection->getWeight() * $connection->getFrom()->getActivation();
                }

                $error += $neuron->getError()->getResponsibility() * $influence;
            }

            // gated error responsibility
            $this
                ->getError()
                ->setGated($this->getDerivative() * $error)
                ->setResponsibility($this->getError()->getProjected() + $this->getError()->getGated());
        }

        // learning rate
        if (null === $rate) {
            $rate = 1.0;
        }

        // adjust all the neuron's incoming connections
        /** @var ConnectionInterface $connection */
        foreach ($this->getConnections()->getInputs() as $connection) {
            $gradient = $this->getError()->getProjected() * $this->getTrace()->eligibility($connection->getId());

            foreach ($this->getTrace()->getExtendedKeys() as $id) {
                /** @var NeuronInterface $neuron */
                $neuron = $this->getNeighbors()->findById($id);

                $gradient += $neuron->getError()->getResponsibility()
                    * $this->getTrace()->extended($neuron->getId(), $connection->getId());
            }

            $connection->setWeight($connection->getWeight() + $rate * $gradient); // adjust weights - aka learn
        }

        // adjust bias
        $this->setBias($this->getBias() + $rate * $this->getError()->getResponsibility());

        return $this;
    }

    /**
     * @param NeuronInterface $neuron
     * @param float|null $weight
     * @return ConnectionInterface
     */
    public function project(NeuronInterface $neuron, ?float $weight = null): ConnectionInterface
    {
        if ($this === $neuron) {
            $this->getSelfConnection()->setWeight(1.0);
            return $this->getSelfConnection();
        }

        if ($connected = $this->connected($neuron)) {
            if (ConnectionInterface::TYPE_PROJECTED === $connected[0]) {
                /** @var ConnectionInterface $connection */
                $connection = $connected[1];

                if (null !== $weight) {
                    $connection->setWeight($weight);
                }

                return $connection;
            }
        }

        $connection = $this->connectionFactory->createNeuronConnection($this, $neuron, $weight);

        $this->getConnections()->getProjected()->add($connection);
        $this->getNeighbors()->add($neuron);

        $neuron->getConnections()->getInputs()->add($connection);
        $neuron->getTrace()->addEligibility($connection->getId(), 0.0);

        foreach ($neuron->getTrace()->getExtendedKeys() as $i) {
            $neuron->getTrace()->addExtended($i, $connection->getId(), 0.0);
        }

        return $connection;
    }

    /**
     * @param ConnectionInterface $connection
     * @return NeuronInterface
     */
    public function gate(ConnectionInterface $connection): NeuronInterface
    {
        $this->getConnections()->getGated()->add($connection);

        $neuron = $connection->getTo();

        if (false === array_key_exists($neuron->getId(), $this->getTrace()->getExtended())) {
            $this->getNeighbors()->add($neuron);

            /** @var ConnectionInterface $input */
            foreach ($this->getConnections()->getInputs() as $input) {
                $this->getTrace()->addExtended($neuron->getId(), $input->getId(), 0.0);
            }
        }

        $this->getTrace()->addInfluence($neuron->getId(), $connection);

        $connection->setGater($this);

        return $this;
    }

    /**
     * returns true or false whether the neuron is self-connected or not
     * @return bool
     */
    public function isSelfConnected(): bool
    {
        return $this->getSelfConnection()->getWeight() !== 0.0;
    }

    /**
     * Returns [type, ConnectionInterface] or null whether the neuron is connected to another neuron (parameter)
     *
     * @param NeuronInterface $neuron
     * @return array|null
     */
    public function connected(NeuronInterface $neuron): ?array
    {
        if ($this === $neuron) {
            return $this->isSelfConnected()
                ? [ConnectionInterface::TYPE_SELF_CONNECTED, $this->getSelfConnection()]
                : null;
        }

        if ($connection = $this->connectedByCollection($neuron, $this->getConnections()->getInputs())) {
            return [ConnectionInterface::TYPE_INPUTS, $connection];
        }

        if ($connection = $this->connectedByCollection($neuron, $this->getConnections()->getProjected())) {
            return [ConnectionInterface::TYPE_PROJECTED, $connection];
        }

        if ($connection = $this->connectedByCollection($neuron, $this->getConnections()->getGated())) {
            return [ConnectionInterface::TYPE_GATED, $connection];
        }

        return null;
    }

    /**
     * clears all the traces (the neuron forgets it's context, but the connections remain intact)
     *
     * @return NeuronInterface
     */
    public function clear(): NeuronInterface
    {
        foreach ($this->getTrace()->getEligibilityKeys() as $id) {
            $this->getTrace()->addEligibility($id, 0.0);
        }

        foreach ($this->getTrace()->getExtendedKeys() as $i) {
            foreach ($this->getTrace()->getExtended()[$i] as $j => $v) {
                $this->getTrace()->addExtended($i, $j, 0.0);
            }
        }

        $this->getError()->clear();

        return $this;
    }

    /**
     * @return NeuronInterface
     */
    public function reset(): NeuronInterface
    {
        /** @var ConnectionInterface $connection */
        foreach ($this->getConnections() as $connection) {
            $connection->initRandomWeight();
        }

        return $this
            ->clear()
            ->setBias($this->getInitRandom())
            ->setState()
            ->setOldState()
            ->setActivation();
    }

    /**
     * @param NeuronInterface $neuron
     * @param CollectionInterface $collection
     * @return ConnectionInterface|null
     */
    protected function connectedByCollection(NeuronInterface $neuron, CollectionInterface $collection): ?ConnectionInterface
    {
        /** @var ConnectionInterface $connection */
        foreach ($collection as $connection) {
            if ($connection->getFrom() === $neuron || $connection->getTo() === $neuron) {
                return $connection;
            }
        }

        return null;
    }
}