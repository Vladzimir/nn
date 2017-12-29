<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Traits;

trait RandomTrait
{
    /**
     * @return float
     */
    public function random(): float
    {
        return (float) rand() / (float) getrandmax();
    }

    /**
     * @return float
     */
    public function getInitRandom(): float
    {
        return $this->random() * 0.2 - 0.1;
    }

    /**
     * @param array $o
     * @return array
     */
    public function shuffle(array $o): array
    {
        $o = array_values($o);
        for($i = count($o); $i > 0; $j = (int) floor($this->random() * (float) $i), $x = $o[--$i], $o[$i] = $o[$j], $o[$j] = $x);
        return $o;
    }
}