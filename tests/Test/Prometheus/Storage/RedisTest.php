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
    public function itShouldThrowExceptionWhenInjectedRedisIsNotConnected()
    {
        $connection = new \Redis;

        $this->expectException(StorageException::class);
        $this->expectExceptionMessage('Connection to Redis server not established');

        Redis::fromExistingConnection($connection);
    }
}
