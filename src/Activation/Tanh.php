<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Activation;

use NN\Traits\ClassNameTrait;

class Tanh implements ActivationInterface
{
    use ClassNameTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(float $x, bool $derivative = false): float
    {
        $fx = tanh($x);
        return $derivative ? 1.0 - ($fx ** 2.0) : $fx;
    }
}