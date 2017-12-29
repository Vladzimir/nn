<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer;

use NN\Network\NetworkInterface;

interface TrainerFactoryInterface
{
    /**
     * @param NetworkInterface $network
     * @return TrainerInterface
     */
    public function createTrainer(NetworkInterface $network): TrainerInterface;
}