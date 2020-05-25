<?php

namespace Test\Prometheus;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Ring\Client\MockHandler;
use PHPUnit\Framework\TestCase;
use Prometheus\CollectorRegistry;
use Prometheus\MetricFamilySamples;
use Prometheus\PushGateway;

class PushGatewayTest extends TestCase
{
    /**
     * @test
     *
     * @doesNotPerformAssertions
     */
    public function validResponseShouldNotThrowException()
    {
        $mockedCollectorRegistry = $this->createMock(CollectorRegistry::class);
        $mockedCollectorRegistry->method('getMetricFamilySamples')->with()->willReturn([
            $this->createMock(MetricFamilySamples::class)
        ]);

        $mockHandler = new MockHandler(['status' => 200]);
        $client = new Client(['handler' => $mockHandler]);

        $pushGateway = new PushGateway('http://foo.bar', $client);
        $pushGateway->push($mockedCollectorRegistry, 'foo');
    }

    /**
     * @test
     *
     * @doesNotPerformAnyAssertions
     */
    public function invalidResponseShouldThrowRuntimeException()
    {
        $this->expectException(\RuntimeException::class);

        $mockedCollectorRegistry = $this->createMock(CollectorRegistry::class);
        $mockedCollectorRegistry->method('getMetricFamilySamples')->with()->willReturn([
            $this->createMock(MetricFamilySamples::class)
        ]);


        $mockHandler = new MockHandler([
            new Response(201),
            new Response(300),
        ]);

        $client = new Client(['handler' => $mockHandler]);

        $pushGateway = new PushGateway('http://foo.bar', $client);
        $pushGateway->push($mockedCollectorRegistry, 'foo');
    }

    /**
     * @test
     */
    public function clientGetsDefinedIfNotSpecified()
    {
        $this->expectException(\RuntimeException::class);

        $mockedCollectorRegistry = $this->createMock(CollectorRegistry::class);
        $mockedCollectorRegistry->method('getMetricFamilySamples')->with()->willReturn([
            $this->createMock(MetricFamilySamples::class)
        ]);

        $pushGateway = new PushGateway('http://foo.bar');
        $pushGateway->push($mockedCollectorRegistry, 'foo');
    }
}
