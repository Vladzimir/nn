<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer;

use NN\Network\NetworkInterface;
use NN\Trainer\Cost\CostInterface;

interface TrainerInterface
{
    /**
     * @return NetworkInterface
     */
    public function getNetwork(): NetworkInterface;

    /**
     * @param CostInterface $cost
     * @return TrainerInterface
     */
    public function setCost(CostInterface $cost): self;

    /**
     * @param float|float[]|callable $rate
     * @return TrainerInterface
     */
    public function setRate($rate): self;

    /**
     * @param int $iterations
     * @return TrainerInterface
     */
    public function setIterations(int $iterations): self;

    /**
     * @param float $error
     * @return TrainerInterface
     */
    public function setError(float $error): self;

    /**
     * @param bool $shuffle
     * @return TrainerInterface
     */
    public function setShuffle(bool $shuffle): self;

    /**
     * @param CrossValidateInterface|null $crossValidate
     * @return TrainerInterface
     */
    public function setCrossValidate(?CrossValidateInterface $crossValidate = null): self;

    /**
     * @param ScheduleInterface|null $schedule
     * @return TrainerInterface
     */
    public function setSchedule(?ScheduleInterface $schedule = null): self;

    /**
     * @param array $set
     * @return array
     */
    public function train(array $set): array;

    /**
     * preforms one training epoch and returns the error (private function used in this.train)
     *
     * @param array $set
     * @param float $currentRate
     * @return float
     */
    public function trainSet(array &$set, float $currentRate): float;

    /**
     * tests a set and returns the error and elapsed time
     *
     * @param array $set
     * @return float
     */
    public function test(array &$set): float;
}