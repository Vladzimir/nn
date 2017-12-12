<?php
/**
 * (c) Igor Agapie <igoragapie@gmail.com>
 */

declare(strict_types=1);

namespace NN\Tests;

use NN\Test;
use PHPUnit\Framework\TestCase;

class TestForTest extends TestCase
{
    public function testIsThereAnySyntaxError(): void
    {
        $object = new Test();
        $this->assertTrue(is_object($object));
        unset($var);
    }

    public function testMethod1(): void
    {
        $object = new Test();
        $this->assertTrue($object->method1() === 'Hello World');
        unset($var);
    }
}