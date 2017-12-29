<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer\Cost;

interface CostFactoryInterface
{
    public const MSE = Mse::class;

    public const BINARY = Binary::class;

    public const CROSS_ENTROPY = CrossEntropy::class;

    /**
     * @param string $name
     * @return CostInterface
     */
    public function createCost(string $name): CostInterface;
}