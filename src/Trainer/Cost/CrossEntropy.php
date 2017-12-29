<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer\Cost;

class CrossEntropy implements CostInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(array $target, array $output): float
    {
        $crossEntropy = 0.0;

        for($i = 0, $count = count($output); $i < $count; ++$i) {
            // +1e-15 is a tiny push away to avoid Math.log(0)
            $crossEntropy -= ($target[$i] * log($output[$i] + 1e-15)) + ((1.0 - $target[$i]) * log((1.0 + 1e-15) - $output[$i]));
        }

        return $crossEntropy;
    }
}