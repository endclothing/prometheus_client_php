<?php

namespace Prometheus\Tests\Unit\Redis;

use Prometheus\Storage\Redis;
use Prometheus\Tests\Unit\AbstractCollectorRegistryTest;

/**
 * @requires extension redis
 */
class CollectorRegistryTest extends AbstractCollectorRegistryTest
{
    public function configureAdapter()
    {
        $this->adapter = new Redis(['host' => REDIS_HOST]);
        $this->adapter->flushRedis();
    }
}
