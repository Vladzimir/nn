<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer\Cost;

interface CostInterface
{
    /**
     * @param float[] $target
     * @param float[] $output
     * @return float
     */
    public function __invoke(array $target, array $output): float;
}