<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer;

class Schedule implements ScheduleInterface
{
    /**
     * @var int
     */
    protected $every;

    /**
     * @var callable
     */
    protected $do;

    /**
     * Schedule constructor.
     *
     * @param int $every
     * @param callable $do
     */
    public function __construct(int $every, callable $do)
    {
        $this->every = $every;
        $this->do = $do;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvery(): int
    {
        return $this->every;
    }

    /**
     * {@inheritdoc}
     */
    public function getDo(): callable
    {
        return $this->do;
    }
}