<?php

namespace Prometheus\Tests\Unit\APC;

use Prometheus\Storage\APC;
use Prometheus\Tests\Unit\AbstractGaugeTest;

/**
 * See https://prometheus.io/docs/instrumenting/exposition_formats/
 * @requires extension apcu
 */
class GaugeTest extends AbstractGaugeTest
{
    public function configureAdapter()
    {
        $this->adapter = new APC();
        $this->adapter->flushAPC();
    }
}
