<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer;

class CrossValidate implements CrossValidateInterface
{
    /**
     * @var float
     */
    protected $testSize;

    /**
     * @var float
     */
    protected $testError;

    /**
     * CrossValidate constructor.
     * @param float $testSize
     * @param float $testError
     */
    public function __construct(float $testSize = 0.0, float $testError = 0.0)
    {
        $this->testSize = $testSize;
        $this->testError = $testError;
    }

    /**
     * {@inheritdoc}
     */
    public function setTestSize(float $testSize): CrossValidateInterface
    {
        $this->testSize = $testSize;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTestSize(): float
    {
        return $this->testSize;
    }

    /**
     * {@inheritdoc}
     */
    public function setTestError(float $testError): CrossValidateInterface
    {
        $this->testError = $testError;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTestError(): float
    {
        return $this->testError;
    }
}