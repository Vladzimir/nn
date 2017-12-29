<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer\Cost;

class Mse implements CostInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(array $target, array $output): float
    {
        $mse = 0.0;

        for($i = 0, $count = count($output); $i < $count; ++$i) {
            $mse += ($target[$i] - $output[$i]) ** 2;
        }

        return $mse / (float) $count;
    }
}