<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Activation;

use NN\Traits\ClassNameTrait;

class Hlim implements ActivationInterface
{
    use ClassNameTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(float $x, bool $derivative = false): float
    {
        return $derivative || $x > 0.0 ? 1.0 : 0.0;
    }
}