<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Activation;

use NN\Traits\ClassNameTrait;

class Relu implements ActivationInterface
{
    use ClassNameTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(float $x, bool $derivative = false): float
    {
        return $x <= 0.0 ? 0.0 : ($derivative ? 1.0 : $x);
    }
}