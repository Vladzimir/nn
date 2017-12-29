<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests\Network;

use NN\Network\NetworkInterface;
use NN\Tests\BaseTestCase;
use NN\Trainer\Cost\Mse;
use NN\Trainer\TrainerInterface;

class BasicNNTest extends BaseTestCase
{
    public function testAnAndGate(): void
    {
        $network = $this->getNetwork();
        $trainer = $this->getTrainer($network);

        $trainer->train([
            ['input' => [0.0, 0.0], 'output' => [0.0]],
            ['input' => [0.0, 1.0], 'output' => [0.0]],
            ['input' => [1.0, 0.0], 'output' => [0.0]],
            ['input' => [1.0, 1.0], 'output' => [1.0]],
        ]);

        $this->assertEquals(0, (int) round($network->activate([0.0, 0.0])[0]), '[0,0] did not output 0');
        $this->assertEquals(0, (int) round($network->activate([0.0, 1.0])[0]), '[0,1] did not output 0');
        $this->assertEquals(0, (int) round($network->activate([1.0, 0.0])[0]), '[0,1] did not output 0');
        $this->assertEquals(1, (int) round($network->activate([1.0, 1.0])[0]), '[1,1] did not output 1');
    }

    public function testAnOrGate(): void
    {
        $network = $this->getNetwork();
        $trainer = $this->getTrainer($network);

        $trainer->train([
            ['input' => [0.0, 0.0], 'output' => [0.0]],
            ['input' => [0.0, 1.0], 'output' => [1.0]],
            ['input' => [1.0, 0.0], 'output' => [1.0]],
            ['input' => [1.0, 1.0], 'output' => [1.0]],
        ]);

        $this->assertEquals(0, (int) round($network->activate([0.0, 0.0])[0]), '[0,0] did not output 0');
        $this->assertEquals(1, (int) round($network->activate([0.0, 1.0])[0]), '[0,1] did not output 1');
        $this->assertEquals(1, (int) round($network->activate([1.0, 0.0])[0]), '[0,1] did not output 1');
        $this->assertEquals(1, (int) round($network->activate([1.0, 1.0])[0]), '[1,1] did not output 1');
    }

    public function testANotGate(): void
    {
        $network = $this->getNetwork(1);
        $trainer = $this->getTrainer($network);

        $trainer->train([
            ['input' => [0.0], 'output' => [1.0]],
            ['input' => [1.0], 'output' => [0.0]],
        ]);

        $this->assertEquals(1, (int) round($network->activate([0.0])[0]), '0 did not output 1');
        $this->assertEquals(0, (int) round($network->activate([1.0])[0]), '1 did not output 0');
    }

    /**
     * @param NetworkInterface $network
     * @return TrainerInterface
     */
    protected function getTrainer(NetworkInterface $network): TrainerInterface
    {
        return $this
            ->getTrainerFactory()
            ->createTrainer($network)
            ->setCost($this->getCostFactory()->createCost(Mse::class))
            ->setIterations(1000)
            ->setError(0.001);
    }

    /**
     * @param int $input
     * @param int $output
     * @return NetworkInterface
     */
    protected function getNetwork(int $input = 2, int $output = 1): NetworkInterface
    {
        $network = parent::getNetwork($input, $output);

        $network->getInput()->project($network->getOutput());

        return $network;
    }
}