<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Trace;

use NN\Neuron\Connection\ConnectionInterface;

class Trace implements TraceInterface
{
    /**
     * @var float[]
     */
    protected $eligibility = [];

    /**
     * @var float[][]
     */
    protected $extended = [];

    /**
     * @var ConnectionInterface[][]
     */
    protected $influences;

    /**
     * @return float[]
     */
    public function getEligibility(): array
    {
        return $this->eligibility;
    }

    /**
     * @return int[]
     */
    public function getEligibilityKeys(): array
    {
        return array_keys($this->getEligibility());
    }

    /**
     * @param array $eligibility
     * @return TraceInterface
     */
    public function setEligibility(array $eligibility): TraceInterface
    {
        $this->eligibility = $eligibility;
        return $this;
    }

    /**
     * @param int $id
     * @param float $eligibility
     * @return TraceInterface
     */
    public function addEligibility(int $id, float $eligibility): TraceInterface
    {
        $this->eligibility[$id] = $eligibility;
        return $this;
    }

    /**
     * @param int $id
     * @return float
     */
    public function eligibility(int $id): float
    {
        return $this->eligibility[$id] ?? 0.0;
    }

    /**
     * @return float[][]
     */
    public function getExtended(): array
    {
        return $this->extended;
    }

    /**
     * @return int[]
     */
    public function getExtendedKeys(): array
    {
        return array_keys($this->getExtended());
    }

    /**
     * @param array $extended
     * @return TraceInterface
     */
    public function setExtended(array $extended): TraceInterface
    {
        $this->extended = $extended;
        return $this;
    }

    /**
     * @param int $i input id
     * @param int $j connection id
     * @param float $extended
     * @return TraceInterface
     */
    public function addExtended(int $i, int $j, float $extended): TraceInterface
    {
        $this->extended[$i][$j] = $extended;
        return $this;
    }

    /**
     * @param int $i
     * @param int $j
     * @return float
     */
    public function extended(int $i, int $j): float
    {
        return $this->extended[$i][$j] ?? 0.0;
    }

    /**
     * @return ConnectionInterface[][]
     */
    public function getInfluences(): array
    {
        return $this->influences;
    }

    /**
     * @param array $influences
     * @return TraceInterface
     */
    public function setInfluences(array $influences): TraceInterface
    {
        $this->influences = $influences;
        return $this;
    }

    /**
     * @param int $i input id
     * @param ConnectionInterface $influence
     * @return TraceInterface
     */
    public function addInfluence(int $i, ConnectionInterface $influence): TraceInterface
    {
        $this->influences[$i][] = $influence;
        return $this;
    }

    /**
     * @param int $i
     * @param int $j
     * @return ConnectionInterface|null
     */
    public function influence(int $i, int $j): ?ConnectionInterface
    {
        return $this->influences[$i][$j] ?? null;
    }
}