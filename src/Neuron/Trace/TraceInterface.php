<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Trace;

use NN\Neuron\Connection\ConnectionInterface;

interface TraceInterface
{
    /**
     * @return float[]
     */
    public function getEligibility(): array;

    /**
     * @return int[]
     */
    public function getEligibilityKeys(): array;

    /**
     * @param array $eligibility
     * @return TraceInterface
     */
    public function setEligibility(array $eligibility): self;

    /**
     * @param int $id
     * @param float $eligibility
     * @return TraceInterface
     */
    public function addEligibility(int $id, float $eligibility): self;

    /**
     * @param int $id
     * @return float
     */
    public function eligibility(int $id): float;

    /**
     * @return float[][]
     */
    public function getExtended(): array;

    /**
     * @return int[]
     */
    public function getExtendedKeys(): array;

    /**
     * @param array $extended
     * @return TraceInterface
     */
    public function setExtended(array $extended): self;

    /**
     * @param int $i input id
     * @param int $j connection id
     * @param float $extended
     * @return TraceInterface
     */
    public function addExtended(int $i, int $j, float $extended): self;

    /**
     * @param int $i
     * @param int $j
     * @return float
     */
    public function extended(int $i, int $j): float;

    /**
     * @return ConnectionInterface[][]
     */
    public function getInfluences(): array;

    /**
     * @param array $influences
     * @return TraceInterface
     */
    public function setInfluences(array $influences): self;

    /**
     * @param int $i input id
     * @param ConnectionInterface $influence
     * @return TraceInterface
     */
    public function addInfluence(int $i, ConnectionInterface $influence): self;

    /**
     * @param int $i
     * @param int $j
     * @return ConnectionInterface|null
     */
    public function influence(int $i, int $j): ?ConnectionInterface;
}