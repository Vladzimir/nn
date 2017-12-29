<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Traits;

trait ActivationTrait
{
    /**
     * @param float $x
     * @param bool $derivative
     * @return float
     */
    public function sigmoid(float $x, bool $derivative = false): float
    {
        $fx = 1.0 / (1.0 + exp(-$x));
        return $derivative ? $fx * (1.0 - $fx) : $fx;
    }

    /**
     * @param float $x
     * @param bool $derivative
     * @return float
     */
    public function tanh(float $x, bool $derivative = false): float
    {
        $fx = tanh($x);
        return $derivative ? 1.0 - ($fx ** 2.0) : $fx;
    }

    /**
     * @param float $x
     * @param bool $derivative
     * @return float
     */
    public function identity(float $x, bool $derivative = false): float
    {
        return $derivative ? 1.0 : $x;
    }

    /**
     * @param float $x
     * @param bool $derivative
     * @return float
     */
    public function hlim(float $x, bool $derivative = false): float
    {
        return $derivative ? 1.0 : $x > 0.0 ? 1.0 : 0.0;
    }

    /**
     * @param float $x
     * @param bool $derivative
     * @return float
     */
    public function relu(float $x, bool $derivative = false): float
    {
        return $x <= 0.0 ? 0.0 : $derivative ? 1.0 : $x;
    }
}