<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Traits;

trait IdTrait
{
    /**
     * @var int[]
     */
    protected static $counters = [];

    /**
     * @var int
     */
    protected $id = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        if ($class = get_class($this)) {
            if (false === array_key_exists($class, self::$counters)) {
                self::$counters[$class] = 0;
            }

            if (null === $this->id) {
                $this->id = ++self::$counters[$class];
            }
        }

        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $parts = explode('\\', get_class($this));
        return sprintf('%s #%s', ucfirst(array_pop($parts)), $this->getId());
    }
}