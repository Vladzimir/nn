<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests\Network;

use NN\Tests\BaseTestCase;
use NN\Trainer\Cost\Mse;
use NN\Trainer\CrossValidate;

class PerceptronNNTest extends BaseTestCase
{
    public function testXOR(): void
    {
        $network = $this
            ->getPerceptronFactory()
            ->createNetwork(2, [3], 1);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setIterations(1000000)
            ->setShuffle(true)
            ->setCost($this->getCostFactory()->createCost(Mse::class));

        $trainer->train([
            ['input' => [0.0, 0.0], 'output' => [0.0]],
            ['input' => [1.0, 0.0], 'output' => [1.0]],
            ['input' => [0.0, 0.1], 'output' => [1.0]],
            ['input' => [1.0, 1.0], 'output' => [0.0]],
        ]);

        $this->assertLessThanOrEqual(0.49, $network->activate([0.0, 0.0])[0], '[0,0] did not output 0');
        $this->assertLessThanOrEqual(0.49, $network->activate([1.0, 1.0])[0], '[1,1] did not output 0');
        $this->assertGreaterThanOrEqual(0.51, $network->activate([0.0, 1.0])[0], '[0,1] did not output 1');
        $this->assertGreaterThanOrEqual(0.51, $network->activate([1.0, 0.0])[0], '[1,0] did not output 1');
    }

    public function testSIN(): void
    {
        $network = $this
            ->getPerceptronFactory()
            ->createNetwork(1, [12], 1);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setShuffle(true)
            ->setError(1e-6)
            ->setIterations(2000)
            ->setCost($this->getCostFactory()->createCost(Mse::class));

        $trainingSet = [];

        while (count($trainingSet) < 800) {
            $inputValue = $this->random() * M_PI * 2.0;
            $trainingSet[] = [
                'input' => [$inputValue],
                'output' => [$this->mySin($inputValue)]
            ];
        }

        list($error) = $trainer->train($trainingSet);

        foreach ([0.0, 0.5 * M_PI, 2.0] as $x) {
            $y = $this->mySin($x);
            $this->assertEquals($network->activate([$x])[0], $y, '', 0.15);
        }

        $this->assertLessThanOrEqual(0.001, $error, 'Sin error not less than or equal to desired error.');
    }

    public function testSINCrossValidate(): void
    {
        $network = $this
            ->getPerceptronFactory()
            ->createNetwork(1, [12], 1);

        $trainer = $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setShuffle(true)
            ->setError(1e-6)
            ->setIterations(2000)
            ->setCost($this->getCostFactory()->createCost(Mse::class))
            ->setCrossValidate(new CrossValidate(0.3, 1e-6));

        $trainingSet = array_map(function() {
            $inputValue = $this->random() * M_PI * 2.0;
            return ['input' => [$inputValue], 'output' => [$this->mySin($inputValue)]];
        }, range(1, 800));

        list($error) = $trainer->train($trainingSet);

        $test0 = $network->activate([0.0])[0];
        $expected0 = $this->mySin(0.0);
        $this->assertLessThanOrEqual(0.035, abs($test0 - $expected0), '[0.0] did not output  ' . $expected0);

        $test05PI = $network->activate([0.5 * M_PI])[0];
        $expected05PI = $this->mySin(0.5 * M_PI);
        $this->assertLessThanOrEqual(0.035, abs($test05PI - $expected05PI), '[0.5 * M_PI] did not output  ' . $expected05PI);

        $test2 = $network->activate([2.0])[0];
        $expected2 = $this->mySin(2.0);
        $eq = $this->equalWithError($test2, $expected2, 0.035);
        $this->assertEquals(true, $eq, '[2.0] did not output ' . $expected2);

        $lessThanOrEqualError = $error <= 0.001;
        $this->assertEquals(true, $lessThanOrEqualError, 'CrossValidation error not less than or equal to desired error.');
    }

    /**
     * @param float $x
     * @return float
     */
    protected function mySin(float $x): float
    {
        return (sin($x) + 1.0) / 2.0;
    }

    /**
     * @param float $output
     * @param float $expected
     * @param float $error
     * @return bool
     */
    protected function equalWithError(float $output, float $expected, float $error): bool
    {
        return abs($output - $expected) <= $error;
    }
}