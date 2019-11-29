<?php

namespace Prometheus\Tests\Unit\Redis;

use Prometheus\Storage\Redis;
use Prometheus\Tests\Unit\AbstractHistogramTest;

/**
 * See https://prometheus.io/docs/instrumenting/exposition_formats/
 * @requires extension redis
 */
class HistogramWithPrefixTest extends AbstractHistogramTest
{
    public function configureAdapter()
    {
        $connection = new \Redis();
        $connection->connect(REDIS_HOST);

        $connection->setOption(\Redis::OPT_PREFIX, 'prefix:');

        $this->adapter = Redis::fromExistingConnection($connection);
        $this->adapter->flushRedis();
    }
}
