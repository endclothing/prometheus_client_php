<?php

namespace Prometheus\Tests\Unit\InMemory;

use Prometheus\Storage\InMemory;
use Prometheus\Tests\Unit\AbstractCounterTest;

/**
 * See https://prometheus.io/docs/instrumenting/exposition_formats/
 */
class CounterTest extends AbstractCounterTest
{

    public function configureAdapter()
    {
        $this->adapter = new InMemory();
        $this->adapter->flushMemory();
    }
}
