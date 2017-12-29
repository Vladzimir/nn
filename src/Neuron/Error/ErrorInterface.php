<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Error;

interface ErrorInterface
{
    /**
     * @return ErrorInterface
     */
    public function clear(): ErrorInterface;

    /**
     * @return float
     */
    public function getResponsibility(): float;

    /**
     * @param float $responsibility
     * @return ErrorInterface
     */
    public function setResponsibility(float $responsibility): self;

    /**
     * @return float
     */
    public function getProjected(): float;

    /**
     * @param float $projected
     * @return ErrorInterface
     */
    public function setProjected(float $projected): self;

    /**
     * @return float
     */
    public function getGated(): float;

    /**
     * @param float $gated
     * @return ErrorInterface
     */
    public function setGated(float $gated): self;
}