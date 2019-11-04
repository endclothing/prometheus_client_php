<?php

namespace Prometheus\Storage;

use PHPUnit\Framework\TestCase;
use Prometheus\Exception\StorageException;

/**
 * @requires extension redis
 */
class RedisTest extends TestCase
{
    /**
     * @test
     * @expectedException Prometheus\Exception\StorageException
     * @expectedExceptionMessage Can't connect to Redis server
     */
    public function itShouldThrowAnExceptionOnConnectionFailure()
    {
        $redis = new Redis(['host' => '/dev/null']);
        $redis->collect();
        $redis->flushRedis();
    }

    /**
     * @test
     */
    public function withExistingConnection()
    {
        $connection = new \Redis;

        $connection->connect(REDIS_HOST);

        $redis = Redis::fromExistingConnection($connection);
        $redis->flushRedis();
    }
}
