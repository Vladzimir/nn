<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Network;

use NN\Collection\CollectionInterface;
use NN\Layer\BaseLayerInterface;
use NN\Layer\Connection\ConnectionInterface;
use NN\Layer\LayerInterface;
use NN\Traits\IdTrait;

class Network implements NetworkInterface
{
    /**
     * @var LayerInterface
     */
    protected $input;

    /**
     * @var LayerInterface
     */
    protected $output;

    /**
     * @var CollectionInterface
     */
    protected $hidden;

    use IdTrait;

    /**
     * Network constructor.
     * @param LayerInterface|null $input
     * @param CollectionInterface|null $hidden
     * @param LayerInterface|null $output
     */
    public function __construct(?LayerInterface $input = null, ?CollectionInterface $hidden = null, ?LayerInterface $output = null)
    {
        $this->input = $input;
        $this->hidden = $hidden;
        $this->output = $output;
    }

    /**
     * feed-forward activation of all the layers to produce an ouput
     *
     * @param float[]|null $input
     * @return float[]
     * @throws \Throwable
     */
    public function activate(?array $input = null): array
    {
        $this->getInput()->activate($input);

        /** @var LayerInterface $layer */
        foreach ($this->getHidden() as $layer) {
            $layer->activate();
        }

        return $this->getOutput()->activate();
    }

    /**
     * back-propagate the error thru the network
     *
     * @param float|null $rate
     * @param float[]|null $target
     * @return BaseLayerInterface
     * @throws \Throwable
     */
    public function propagate(?float $rate = null, ?array $target = null): BaseLayerInterface
    {
        $this->getOutput()->propagate($rate, $target);

        for ($this->getHidden()->end(); null !== $this->getHidden()->key(); $this->getHidden()->prev()) {
            $this->getHidden()->current()->propagate($rate);
        }

        return $this;
    }

    /**
     * project a connection to another unit (either a network or a layer)
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
            return $this->getOutput()->project($layer->getInput(), $type, $weight);
        }

        if ($layer instanceof LayerInterface) {
            return $this->getOutput()->project($layer, $type, $weight);
        }

        throw new NetworkException('Invalid argument, you can only project connections to LAYERS and NETWORKS!');
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
        $this->getOutput()->gate($connection, $gateType);
        return $this;
    }

    /**
     * clears all the neurons in the layer
     * @return BaseLayerInterface
     */
    public function clear(): BaseLayerInterface
    {
        $this->getInput()->clear();

        /** @var LayerInterface $layer */
        foreach ($this->getHidden() as $layer) {
            $layer->clear();
        }

        $this->getOutput()->clear();

        return $this;
    }

    /**
     * resets all the neurons in the layer
     * @return BaseLayerInterface
     */
    public function reset(): BaseLayerInterface
    {
        $this->getInput()->reset();

        /** @var LayerInterface $layer */
        foreach ($this->getHidden() as $layer) {
            $layer->reset();
        }

        $this->getOutput()->reset();

        return $this;
    }

    /**
     * @return LayerInterface
     */
    public function getInput(): LayerInterface
    {
        return $this->input;
    }

    /**
     * @param LayerInterface $input
     * @return NetworkInterface
     */
    public function setInput(LayerInterface $input): NetworkInterface
    {
        $this->input = $input;
        return $this;
    }

    /**
     * @return LayerInterface
     */
    public function getOutput(): LayerInterface
    {
        return $this->output;
    }

    /**
     * @param LayerInterface $output
     * @return NetworkInterface
     */
    public function setOutput(LayerInterface $output): NetworkInterface
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @return CollectionInterface
     */
    public function getHidden(): CollectionInterface
    {
        return $this->hidden;
    }

    /**
     * @param CollectionInterface $hidden
     * @return NetworkInterface
     */
    public function setHidden(CollectionInterface $hidden): NetworkInterface
    {
        $this->hidden = $hidden;
        return $this;
    }
}