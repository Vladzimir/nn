<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer;

interface ScheduleInterface
{
    /**
     * @return int
     */
    public function getEvery(): int;

    /**
     * @return callable
     */
    public function getDo(): callable;
}