<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer\Cost;

class CostFactory implements CostFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCost(string $name): CostInterface
    {
        return new $name;
    }
}