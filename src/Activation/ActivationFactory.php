<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Activation;

class ActivationFactory implements ActivationFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createActivation(string $name = self::SIGMOID): ActivationInterface
    {
        return new $name;
    }
}