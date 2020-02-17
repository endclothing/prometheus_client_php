<?php

namespace Prometheus\Tests\BlackBox;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Prometheus\CollectorRegistry;
use Prometheus\PushGateway;
use Prometheus\Storage\APC;

class BlackBoxPushGatewayTest extends TestCase
{
    /**
     * @test
     */
    public function pushGatewayShouldWork()
    {
        $adapter = new APC();
        $registry = new CollectorRegistry($adapter);

        $counter = $registry->registerCounter('test', 'some_counter', 'it increases', ['type']);
        $counter->incBy(6, ['blue']);

        $pushGateway = new PushGateway(PUSH_GATEWAY_ADDR);
        $pushGateway->push($registry, 'my_job', ['instance' => 'foo']);

        $httpClient = new Client();
        $metrics = $httpClient->get(sprintf("http://%s/metrics", PUSH_GATEWAY_ADDR))->getBody()->getContents();
        $this->assertStringContainsString(
            '# HELP test_some_counter it increases
# TYPE test_some_counter counter
test_some_counter{instance="foo",job="my_job",type="blue"} 6',
            $metrics
        );

        $pushGateway->delete('my_job', ['instance' => 'foo']);

        $httpClient = new Client();
        $metrics = $httpClient->get(sprintf("http://%s/metrics", PUSH_GATEWAY_ADDR))->getBody()->getContents();
        $this->assertStringNotContainsString(
            '# HELP test_some_counter it increases
# TYPE test_some_counter counter
test_some_counter{instance="foo",job="my_job",type="blue"} 6',
            $metrics
        );
    }
}
