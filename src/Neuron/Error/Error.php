<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Error;

class Error implements ErrorInterface
{
    /**
     * @var float
     */
    protected $responsibility = 0.0;

    /**
     * @var float
     */
    protected $projected = 0.0;

    /**
     * @var float
     */
    protected $gated = 0.0;

    /**
     * @return ErrorInterface
     */
    public function clear(): ErrorInterface
    {
        $this->setResponsibility(0.0);
        $this->setProjected(0.0);
        $this->setGated(0.0);
        return $this;
    }

    /**
     * @return float
     */
    public function getResponsibility(): float
    {
        return $this->responsibility;
    }

    /**
     * @param float $responsibility
     * @return ErrorInterface
     */
    public function setResponsibility(float $responsibility): ErrorInterface
    {
        $this->responsibility = $responsibility;
        return $this;
    }

    /**
     * @return float
     */
    public function getProjected(): float
    {
        return $this->projected;
    }

    /**
     * @param float $projected
     * @return ErrorInterface
     */
    public function setProjected(float $projected): ErrorInterface
    {
        $this->projected = $projected;
        return $this;
    }

    /**
     * @return float
     */
    public function getGated(): float
    {
        return $this->gated;
    }

    /**
     * @param float $gated
     * @return ErrorInterface
     */
    public function setGated(float $gated): ErrorInterface
    {
        $this->gated = $gated;
        return $this;
    }
}