<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Trace;

class TraceFactory implements TraceFactoryInterface
{
    /**
     * @return TraceInterface
     */
    public function createTrace(): TraceInterface
    {
        return new Trace();
    }
}