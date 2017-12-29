<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN;

interface IdInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function __toString(): string;
}