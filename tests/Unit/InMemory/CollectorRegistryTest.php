<?php

namespace Prometheus\Tests\Unit\InMemory;

use Prometheus\Storage\InMemory;
use Prometheus\Tests\Unit\AbstractCollectorRegistryTest;

class CollectorRegistryTest extends AbstractCollectorRegistryTest
{
    public function configureAdapter()
    {
        $this->adapter = new InMemory();
        $this->adapter->flushMemory();
    }
}
