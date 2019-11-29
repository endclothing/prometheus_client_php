<?php

namespace Prometheus\Tests\Unit\InMemory;

use Prometheus\Storage\InMemory;
use Prometheus\Tests\Unit\AbstractGaugeTest;

/**
 * See https://prometheus.io/docs/instrumenting/exposition_formats/
 */
class GaugeTest extends AbstractGaugeTest
{

    public function configureAdapter()
    {
        $this->adapter = new InMemory();
        $this->adapter->flushMemory();
    }
}
