<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests\Network;

use NN\Tests\BaseTestCase;
use NN\Trainer\Cost\CrossEntropy;
use NN\Trainer\Cost\Mse;

class LstmTest extends BaseTestCase
{
//    public function testDiscreteSequenceRecall(): void
//    {
//        $network = $this
//            ->getLstmFactory()
//            ->createNetwork(5, [3], 2);
//
//        $trainer = $this
//            ->getTrainerFactory()
//            ->createTrainer($network)
//            ->setIterations(250000)
//            ->setRate(0.17)
//            ->setCost($this->getCostFactory()->createCost(CrossEntropy::class));
//
//        $targets = [2, 4];
//        $distractors = [3, 5];
//        $prompts = [0, 1];
//        $length = 9;
//        $trial = $correct = $i = $j = $success = 0;
//        $error = 1.0;
//    }

    public function testTimingTask(): void
    {
        $network = $this
            ->getLstmFactory()
            ->createNetwork(2, [7], 1);

        list($trainingSet, $testSet) = $this->getSamples(4000, 500);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setIterations(200)
            ->setError(0.005)
            ->setRate([0.03, 0.02])
            ->setCost($this->getCostFactory()->createCost(Mse::class));

        list(, $iterations) = $trainer->train($trainingSet);

        $test = $trainer->test($testSet);

        $this->assertTrue($iterations <= 200, 'should complete the training in less than 200 iterations');
        $this->assertTrue($test < 0.05, 'should pass the test with an error smaller than 0.05');
    }

    /**
     * @param int $trainingSize
     * @param int $testSize
     * @return array
     */
    public function getSamples(int $trainingSize, int $testSize): array
    {
        $size = $trainingSize + $testSize;

        $set = [];

        for($i = 0; $i < $size; ++$i) {
            $set[] = ['input' => [0.0, 0.0], 'output' => [0.0]];
        }

        $t = 0;

        while ($t < ($size - 20)) {
            $n = round($this->random() * 20.0);
            $set[$t]['input'][0] = 1.0;

            for($j = $t; $j <= ($t + $n); ++$j) {
                $set[$j]['input'][1] = (float) $n / 20.0;
                $set[$j]['output'][0] = 0.5;
            }

            $t += (int) $n;
            $n = round($this->random() * 20.0);

            for ($k = $t + 1; $k <= ($t + $n) && $k < $size; ++$k) {
                $set[$k]['input'][1] = $set[$t]['input'][1];
            }

            $t += (int) $n;
        }

        $trainingSet = [];
        $testSet = [];

        for ($l = 0; $l < $size; $l++) {
            if ($l < $trainingSize) {
                $trainingSet[] = $set[$l];
            } else {
                $testSet[] = $set[$l];
            }
        }

        return [$trainingSet, $testSet];
    }
}