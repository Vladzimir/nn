<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Trainer;

use NN\Network\NetworkInterface;

class TrainerFactory implements TrainerFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createTrainer(NetworkInterface $network): TrainerInterface
    {
        return new Trainer($network);
    }
}