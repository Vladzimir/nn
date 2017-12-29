<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Activation;

interface ActivationFactoryInterface
{
    public const SIGMOID = Sigmoid::class;

    public const TANH = Tanh::class;

    public const IDENTITY = Identity::class;

    public const HLIM = Hlim::class;

    public const RELU = Relu::class;

    /**
     * @param string $name
     * @return ActivationInterface
     */
    public function createActivation(string $name = self::SIGMOID): ActivationInterface;
}