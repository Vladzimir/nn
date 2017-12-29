<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests;

use NN\Activation\ActivationFactoryInterface;

class ActivationTest extends BaseTestCase
{
    public function testIdentity(): void
    {
        $activation = $this->getActivationFactory()->createActivation(ActivationFactoryInterface::IDENTITY);

        $this->assertEquals(1, $activation(1));
        $this->assertEquals(1.5, $activation(1.5));
        $this->assertEquals(6, $activation(6));

        $this->assertEquals(1, $activation(1, true));
        $this->assertEquals(1, $activation(1.5, true));
        $this->assertEquals(1, $activation(6, true));
    }

    public function testSigmoid(): void
    {
        $activation = $this->getActivationFactory()->createActivation(ActivationFactoryInterface::SIGMOID);

        $this->assertEquals(0.73105858, round($activation(1), 8));
        $this->assertEquals(0.8175745, round($activation(1.5), 7));
        $this->assertEquals(0.99752738, round($activation(6), 8));

        $this->assertEquals(0.19661193, round($activation(1, true), 8));
        $this->assertEquals(0.149146, round($activation(1.5, true), 6));
        $this->assertEquals(0.00246651, round($activation(6, true), 8));
    }

    public function testTanh(): void
    {
        $activation = $this->getActivationFactory()->createActivation(ActivationFactoryInterface::TANH);

        $this->assertEquals(0.76159416, round($activation(1), 8));
        $this->assertEquals(0.9051483, round($activation(1.5), 7));
        $this->assertEquals(0.99998771, round($activation(6), 8));

        $this->assertEquals(0.41997434, round($activation(1, true), 8));
        $this->assertEquals(0.180707, round($activation(1.5, true), 6));
        $this->assertEquals(0.00002458, round($activation(6, true), 8));
    }

    public function testHlim(): void
    {
        $activation = $this->getActivationFactory()->createActivation(ActivationFactoryInterface::HLIM);

        $this->assertEquals(1, $activation(1));
        $this->assertEquals(1, $activation(1.5));
        $this->assertEquals(1, $activation(6));
        $this->assertEquals(0, $activation(-1));
        $this->assertEquals(0, $activation(-1.5));
        $this->assertEquals(0, $activation(-6));

        $this->assertEquals(1, $activation(1, true));
        $this->assertEquals(1, $activation(1.5, true));
        $this->assertEquals(1, $activation(6, true));
        $this->assertEquals(1, $activation(-1, true));
        $this->assertEquals(1, $activation(-1.5, true));
        $this->assertEquals(1, $activation(-6, true));
    }

    public function testRelu(): void
    {
        $activation = $this->getActivationFactory()->createActivation(ActivationFactoryInterface::RELU);

        $this->assertEquals(1, $activation(1));
        $this->assertEquals(1.5, $activation(1.5));
        $this->assertEquals(6, $activation(6));
        $this->assertEquals(0, $activation(-1));
        $this->assertEquals(0, $activation(-1.5));
        $this->assertEquals(0, $activation(0));

        $this->assertEquals(1, $activation(1, true));
        $this->assertEquals(1, $activation(1.5, true));
        $this->assertEquals(1, $activation(6, true));
        $this->assertEquals(0, $activation(-1, true));
        $this->assertEquals(0, $activation(-1.5, true));
        $this->assertEquals(0, $activation(0, true));
    }
}