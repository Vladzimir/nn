<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer\Cost;

class Binary implements CostInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(array $target, array $output): float
    {
        $misses = 0.0;

        for($i = 0, $count = count($output); $i < $count; ++$i) {
            $misses += (float) ($target[$i] * 2.0 != $output[$i] * 2.0);
        }

        return $misses;
    }
}