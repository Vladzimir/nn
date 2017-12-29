<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Network;

interface NetworkFactoryInterface
{
    /**
     * @param int $input
     * @param array $hidden
     * @param int $output
     * @param array $options
     * @return NetworkInterface
     * @throws \Throwable
     */
    public function createNetwork(int $input, array $hidden, int $output, array $options = []): NetworkInterface;
}