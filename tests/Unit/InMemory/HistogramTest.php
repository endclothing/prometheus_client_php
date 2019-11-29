<?php

namespace Prometheus\Tests\Unit\InMemory;

use Prometheus\Storage\InMemory;
use Prometheus\Tests\Unit\AbstractHistogramTest;

/**
 * See https://prometheus.io/docs/instrumenting/exposition_formats/
 */
class HistogramTest extends AbstractHistogramTest
{

    public function configureAdapter()
    {
        $this->adapter = new InMemory();
        $this->adapter->flushMemory();
    }
}
