<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Activation;

use NN\Traits\ClassNameTrait;

class Sigmoid implements ActivationInterface
{
    use ClassNameTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(float $x, bool $derivative = false): float
    {
        $fx = 1.0 / (1.0 + exp(-$x));
        return $derivative ? $fx * (1.0 - $fx) : $fx;
    }
}