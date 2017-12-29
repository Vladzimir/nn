<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Layer\Connection;

use NN\Collection\CollectionInterface;
use NN\Layer\LayerInterface;
use NN\Neuron\NeuronInterface;
use NN\Traits\IdTrait;

class Connection implements ConnectionInterface
{
    /**
     * @var LayerInterface
     */
    protected $from;

    /**
     * @var LayerInterface
     */
    protected $to;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var CollectionInterface
     */
    protected $neuronConnections;

    /**
     * @var array
     */
    protected $gatedFrom = [];

    use IdTrait;

    /**
     * Connection constructor.
     * @param LayerInterface $from
     * @param LayerInterface $to
     * @param CollectionInterface $neuronConnections
     * @param string|null $type
     * @param float|null $weight
     */
    public function __construct(
        LayerInterface $from,
        LayerInterface $to,
        CollectionInterface $neuronConnections,
        ?string $type = null,
        ?float $weight = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->neuronConnections = $neuronConnections;

        if (null === $type) {
            $type = $this->isSelfConnection()
                ? LayerInterface::TYPE_CONNECTION_ONE_TO_ONE
                : LayerInterface::TYPE_CONNECTION_ALL_TO_ALL;
        }

        $this->type = $type;

        switch ($type) {
            case LayerInterface::TYPE_CONNECTION_ONE_TO_ONE:
                $this->initOneToOne($weight);
                break;
            case LayerInterface::TYPE_CONNECTION_ALL_TO_ALL:
            case LayerInterface::TYPE_CONNECTION_ALL_TO_ELSE:
                $this->initAllToAllElse($weight);
                break;
        }

        $this->getFrom()->getConnections()->add($this);
    }

    /**
     * @return LayerInterface
     */
    public function getFrom(): LayerInterface
    {
        return $this->from;
    }

    /**
     * @return LayerInterface
     */
    public function getTo(): LayerInterface
    {
        return $this->to;
    }

    /**
     * @return bool
     */
    public function isSelfConnection(): bool
    {
        return $this->getFrom() === $this->getTo();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getNeuronConnectionSize(): int
    {
        return $this->getNeuronConnections()->count();
    }

    /**
     * @return CollectionInterface
     */
    public function getNeuronConnections(): CollectionInterface
    {
        return $this->neuronConnections;
    }

    /**
     * @return array
     */
    public function getGatedFrom(): array
    {
        return $this->gatedFrom;
    }

    /**
     * @param array $gatedFrom
     * @return ConnectionInterface
     */
    public function setGatedFrom(array $gatedFrom): ConnectionInterface
    {
        $this->gatedFrom = $gatedFrom;
        return $this;
    }

    /**
     * @param LayerInterface $layer
     * @param string $gateType
     * @return ConnectionInterface
     */
    public function addGatedFrom(LayerInterface $layer, string $gateType): ConnectionInterface
    {
        $this->gatedFrom[] = ['layer' => $layer, 'type' => $gateType];
        return $this;
    }

    /**
     * @param float|null $weight
     * @return ConnectionInterface
     */
    protected function initAllToAllElse(?float $weight = null): ConnectionInterface
    {
        /** @var NeuronInterface $from */
        foreach ($this->getFrom()->getNeurons() as $from) {
            /** @var NeuronInterface $to */
            foreach ($this->getTo()->getNeurons() as $to) {
                if (LayerInterface::TYPE_CONNECTION_ALL_TO_ELSE === $this->getType() && $from === $to) {
                    continue;
                }

                $connection = $from->project($to, $weight);

                $this->getNeuronConnections()->add($connection);
            }
        }

        return $this;
    }

    /**
     * @param float|null $weight
     * @return ConnectionInterface
     */
    protected function initOneToOne(?float $weight = null): ConnectionInterface
    {
        /** @var NeuronInterface $from */
        foreach ($this->getFrom()->getNeurons() as $i => $from) {
            /** @var NeuronInterface $to */
            if ($to = $this->getTo()->getNeurons()->get($i)) {
                $connection = $from->project($to, $weight);
                $this->getNeuronConnections()->add($connection);
            }
        }

        return $this;
    }
}