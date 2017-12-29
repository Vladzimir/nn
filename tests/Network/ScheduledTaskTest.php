<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests\Network;

use NN\Tests\BaseTestCase;
use NN\Trainer\Cost\Mse;
use NN\Trainer\Schedule;

class ScheduledTaskTest extends BaseTestCase
{
    public function testStop(): void
    {
        $network = $this
            ->getPerceptronFactory()
            ->createNetwork(2, [3], 1);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setIterations(3000)
            ->setShuffle(true)
            ->setRate(0.000001)
            ->setError(0.000001)
            ->setCost($this->getCostFactory()->createCost(Mse::class))
            ->setSchedule(new Schedule(1000, function(float $error, int $iterations) {
                return 20000 === $iterations;
            }));

        list(, $iterations) = $trainer->train($this->getTrainingSet());

        $this->assertEquals(3000, $iterations);
    }

    public function testAbort(): void
    {
        $network = $this
            ->getPerceptronFactory()
            ->createNetwork(2, [3], 1);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setIterations(3000)
            ->setShuffle(true)
            ->setRate(0.000001)
            ->setError(0.000001)
            ->setCost($this->getCostFactory()->createCost(Mse::class))
            ->setSchedule(new Schedule(1000, function(float $error, int $iterations) {
                return 2000 === $iterations;
            }));

        list(, $iterations) = $trainer->train($this->getTrainingSet());

        $this->assertEquals(2000, $iterations);
    }

    public function testNoValue(): void
    {
        $network = $this
            ->getPerceptronFactory()
            ->createNetwork(2, [3], 1);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setIterations(3000)
            ->setShuffle(true)
            ->setRate(0.000001)
            ->setError(0.000001)
            ->setCost($this->getCostFactory()->createCost(Mse::class))
            ->setSchedule(new Schedule(1000, function() {}));

        list(, $iterations) = $trainer->train($this->getTrainingSet());

        $this->assertEquals(3000, $iterations);
    }

    /**
     * @return array
     */
    protected function getTrainingSet(): array
    {
        return [
            ['input' => [0.0, 0.0], 'output' => [0.0]],
            ['input' => [1.0, 0.0], 'output' => [1.0]],
            ['input' => [0.0, 0.1], 'output' => [1.0]],
            ['input' => [1.0, 1.0], 'output' => [0.0]],
        ];
    }
}