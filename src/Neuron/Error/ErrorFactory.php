<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Neuron\Error;

class ErrorFactory implements ErrorFactoryInterface
{
    /**
     * @return ErrorInterface
     */
    public function createError(): ErrorInterface
    {
        return new Error();
    }
}