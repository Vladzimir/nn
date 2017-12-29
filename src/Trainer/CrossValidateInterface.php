<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer;

interface CrossValidateInterface
{
    /**
     * @param float $testSize
     * @return CrossValidateInterface
     */
    public function setTestSize(float $testSize): self;

    /**
     * @return float
     */
    public function getTestSize(): float;

    /**
     * @param float $testError
     * @return CrossValidateInterface
     */
    public function setTestError(float $testError): self;

    /**
     * @return float
     */
    public function getTestError(): float;
}