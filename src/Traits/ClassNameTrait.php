<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Traits;

trait ClassNameTrait
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        $parts = explode('\\', get_class($this));
        return array_pop($parts);
    }
}