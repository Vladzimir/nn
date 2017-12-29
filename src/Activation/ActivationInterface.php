<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Activation;

interface ActivationInterface
{
    /**
     * @param float $x
     * @param bool $derivative
     * @return float
     */
    public function __invoke(float $x, bool $derivative = false): float;

    /**
     * @return string
     */
    public function __toString(): string;
}