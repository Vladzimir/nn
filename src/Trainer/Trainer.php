<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer;

use NN\Network\NetworkInterface;
use NN\Trainer\Cost\CostInterface;
use NN\Traits\RandomTrait;

class Trainer implements TrainerInterface
{
    /**
     * @var NetworkInterface
     */
    protected $network;

    /**
     * @var CostInterface
     */
    protected $cost = null;

    /**
     * @var float|float[]|callable
     */
    protected $rate;

    /**
     * @var int
     */
    protected $iterations;

    /**
     * @var float
     */
    protected $error;

    /**
     * @var bool
     */
    protected $shuffle;

    /**
     * @var CrossValidateInterface|null
     */
    protected $crossValidate = null;

    /**
     * @var ScheduleInterface|null
     */
    protected $schedule = null;

    use RandomTrait;

    /**
     * Trainer constructor.
     * @param NetworkInterface $network
     */
    public function __construct(NetworkInterface $network)
    {
        $this->network = $network;

        $this
            ->setRate(0.2)
            ->setIterations(100000)
            ->setError(0.005)
            ->setShuffle(false);
    }

    /**
     * {@inheritdoc}
     */
    public function getNetwork(): NetworkInterface
    {
        return $this->network;
    }

    /**
     * {@inheritdoc}
     */
    public function setCost(CostInterface $cost): TrainerInterface
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRate($rate): TrainerInterface
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setIterations(int $iterations): TrainerInterface
    {
        $this->iterations = $iterations;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setError(float $error): TrainerInterface
    {
        $this->error = $error;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setShuffle(bool $shuffle): TrainerInterface
    {
        $this->shuffle = $shuffle;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCrossValidate(?CrossValidateInterface $crossValidate = null): TrainerInterface
    {
        $this->crossValidate = $crossValidate;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSchedule(?ScheduleInterface $schedule = null): TrainerInterface
    {
        $this->schedule = $schedule;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function train(array $set): array
    {
        if (null === $this->cost) {
            throw new \InvalidArgumentException('CostInterface is required.');
        }

        $currentRate = $this->rate;
        $bucketSize = 0;
        $error = 1.0;
        $iterations = 0;
        $lastError = 0.0;
        $abort = false;
        $testSet = [];
        $trainSet = [];

        if (is_array($this->rate)) {
            $bucketSize = (int) floor((float) $this->iterations / (float) count($this->rate));
        }

        if (null !== $this->crossValidate) {
            $numTrain = (int) ceil((1.0 - $this->crossValidate->getTestSize()) * (float) count($set));
            $trainSet = array_slice($set, 0, $numTrain);
            $testSet = array_slice($set, $numTrain);
        }

        while (false === (bool) $abort && $iterations < $this->iterations && $error > $this->error) {
            if (null !== $this->crossValidate && $error <= $this->crossValidate->getTestError()) {
                break;
            }

            $error = 0.0;
            $iterations++;

            if ($bucketSize > 0) {
                $currentBucket = (int) floor((float) $iterations / (float) $bucketSize);
                $currentRate = $this->rate[$currentBucket] ?? $currentRate;
            }

            if (is_callable($this->rate)) {
                $currentRate = ($this->rate)($iterations, $lastError);
            }

            if (null === $this->crossValidate) {
                $error += $this->trainSet($set, $currentRate);
                $currentSetSize = count($set);
            } else {
                $this->trainSet($trainSet, $currentRate);
                $error += $this->test($testSet);
                $currentSetSize = 1;
            }

            // check error
            if ($currentSetSize) {
                $error /= (float)$currentSetSize;
            }

            $lastError = $error;

            if (null !== $this->schedule && $this->schedule->getEvery() > 0 && 0 === ($iterations % $this->schedule->getEvery())) {
                $abort = $this->schedule->getDo()($error, $iterations, $currentRate);
            }

            if ($this->shuffle) {
                $set = self::shuffle($set);
            }
        }

        return [$error, $iterations];
    }

    /**
     * {@inheritdoc}
     */
    public function trainSet(array &$set, float $currentRate): float
    {
        $errorSum = 0.0;

        foreach ($set as $item) {
            $output = $this->network->activate($item['input']);
            $this->network->propagate($currentRate, $item['output']);
            $errorSum += ($this->cost)($item['output'], $output);
        }

        return $errorSum;
    }

    /**
     * {@inheritdoc}
     */
    public function test(array &$set): float
    {
        $error = 0.0;

        if (0 === count($set)) {
            return $error;
        }

        foreach ($set as $item) {
            $output = $this->network->activate($item['input']);
            $error += ($this->cost)($item['output'], $output);
        }

        return $error / (float) count($set);
    }
}