<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests\Network;

use NN\Tests\BaseTestCase;
use NN\Trainer\Cost\Mse;
use NN\Trainer\Schedule;

class RateCheckTest extends BaseTestCase
{
    public function testCallback(): void
    {
        $network = $this
            ->getPerceptronFactory()
            ->createNetwork(2, [3], 1);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setIterations(2000)
            ->setShuffle(true)
            ->setRate(function($iterations, $error) {
                return $iterations < 1000 ? 0.01 : 0.005;
            })
            ->setError(0.000001)
            ->setCost($this->getCostFactory()->createCost(Mse::class))
            ->setSchedule(new Schedule(1, function(float $error, int $iterations, float $rate) {
                switch ($iterations) {
                    case 1:
                    case 500:
                    case 999:
                        $this->assertEquals(0.01, $rate);
                        break;

                    case 1000:
                    case 1500:
                    case 2000:
                    $this->assertEquals(0.005, $rate);
                        break;
                }
            }));

        $trainer->train($this->getTrainingSet());
    }

    public function testArray(): void
    {
        $network = $this
            ->getPerceptronFactory()
            ->createNetwork(2, [3], 1);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setIterations(2000)
            ->setShuffle(true)
            ->setRate([0.01, 0.005])
            ->setError(0.000001)
            ->setCost($this->getCostFactory()->createCost(Mse::class))
            ->setSchedule(new Schedule(1, function(float $error, int $iterations, float $rate) {
                switch ($iterations) {
                    case 1:
                    case 500:
                    case 999:
                        $this->assertEquals(0.01, $rate);
                        break;

                    case 1000:
                    case 1500:
                    case 2000:
                        $this->assertEquals(0.005, $rate);
                        break;
                }
            }));

        $trainer->train($this->getTrainingSet());
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