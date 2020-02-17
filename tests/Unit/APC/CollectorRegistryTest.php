<?php

namespace Prometheus\Tests\Unit\APC;

use Prometheus\Storage\APC;
use Prometheus\Tests\Unit\AbstractCollectorRegistryTest;

/**
 * @requires extension apcu
 */
class CollectorRegistryTest extends AbstractCollectorRegistryTest
{

    public function configureAdapter()
    {
        $this->adapter = new APC();
        $this->adapter->flushAPC();
    }
}
