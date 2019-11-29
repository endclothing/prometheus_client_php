<?php

namespace Prometheus\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prometheus\Counter;
use Prometheus\MetricFamilySamples;
use Prometheus\Sample;
use Prometheus\Storage\Redis;
use Prometheus\Storage\Adapter;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\APC;

/**
 * See https://prometheus.io/docs/instrumenting/exposition_formats/
 */
class CounterTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldIncreaseWithLabelsWithRedis()
    {
        $adapter = new Redis(['host' => REDIS_HOST]);
        $adapter->flushRedis();

        $this->itShouldIncreaseWithLabels($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseWithLabelsWithRedisWithPrefix()
    {
        $connection = new \Redis();
        $connection->connect(REDIS_HOST);

        $connection->setOption(\Redis::OPT_PREFIX, 'prefix:');

        $adapter = Redis::fromExistingConnection($connection);
        $adapter->flushRedis();

        $this->itShouldIncreaseWithLabels($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseWithLabelsWithInMemory()
    {
        $adapter = new InMemory();
        $adapter->flushMemory();

        $this->itShouldIncreaseWithLabels($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseWithLabelsWithAPC()
    {
        $adapter = new APC();
        $adapter->flushAPC();

        $this->itShouldIncreaseWithLabels($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseWithoutLabelWhenNoLabelsAreDefinedWithRedis()
    {
        $adapter = new Redis(['host' => REDIS_HOST]);
        $adapter->flushRedis();

        $this->itShouldIncreaseWithoutLabelWhenNoLabelsAreDefined($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseWithoutLabelWhenNoLabelsAreDefinedWithRedisWithPrefix()
    {
        $connection = new \Redis();
        $connection->connect(REDIS_HOST);

        $connection->setOption(\Redis::OPT_PREFIX, 'prefix:');

        $adapter = Redis::fromExistingConnection($connection);
        $adapter->flushRedis();

        $this->itShouldIncreaseWithoutLabelWhenNoLabelsAreDefined($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseWithoutLabelWhenNoLabelsAreDefinedWithInMemory()
    {
        $adapter = new InMemory();
        $adapter->flushMemory();

        $this->itShouldIncreaseWithoutLabelWhenNoLabelsAreDefined($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseWithoutLabelWhenNoLabelsAreDefinedWithAPC()
    {
        $adapter = new APC();
        $adapter->flushAPC();

        $this->itShouldIncreaseWithoutLabelWhenNoLabelsAreDefined($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseTheCounterByAnArbitraryIntegerWithRedis()
    {
        $adapter = new Redis(['host' => REDIS_HOST]);
        $adapter->flushRedis();

        $this->itShouldIncreaseTheCounterByAnArbitraryInteger($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseTheCounterByAnArbitraryIntegerWithRedisWithPrefix()
    {
        $connection = new \Redis();
        $connection->connect(REDIS_HOST);

        $connection->setOption(\Redis::OPT_PREFIX, 'prefix:');

        $adapter = Redis::fromExistingConnection($connection);
        $adapter->flushRedis();

        $this->itShouldIncreaseTheCounterByAnArbitraryInteger($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseTheCounterByAnArbitraryIntegerWithInMemory()
    {
        $adapter = new InMemory();
        $adapter->flushMemory();

        $this->itShouldIncreaseTheCounterByAnArbitraryInteger($adapter);
    }

    /**
     * @test
     */
    public function itShouldIncreaseTheCounterByAnArbitraryIntegerWithAPC()
    {
        $adapter = new APC();
        $adapter->flushAPC();

        $this->itShouldIncreaseTheCounterByAnArbitraryInteger($adapter);
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidMetricsNamesWithRedis()
    {
        $adapter = new Redis(['host' => REDIS_HOST]);
        $adapter->flushRedis();

        $this->itShouldRejectInvalidMetricsNames($adapter);
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidMetricsNamesWithRedisWithPrefix()
    {
        $connection = new \Redis();
        $connection->connect(REDIS_HOST);

        $connection->setOption(\Redis::OPT_PREFIX, 'prefix:');

        $adapter = Redis::fromExistingConnection($connection);
        $adapter->flushRedis();

        $this->itShouldRejectInvalidMetricsNames($adapter);
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidMetricsNamesWithInMemory()
    {
        $adapter = new InMemory();
        $adapter->flushMemory();

        $this->itShouldRejectInvalidMetricsNames($adapter);
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidMetricsNamesWithAPC()
    {
        $adapter = new APC();
        $adapter->flushAPC();

        $this->itShouldRejectInvalidMetricsNames($adapter);
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidLabelNamesWithRedis()
    {
        $adapter = new Redis(['host' => REDIS_HOST]);
        $adapter->flushRedis();

        $this->itShouldRejectInvalidLabelNames($adapter);
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidLabelNamesWithRedisWithPrefix()
    {
        $connection = new \Redis();
        $connection->connect(REDIS_HOST);

        $connection->setOption(\Redis::OPT_PREFIX, 'prefix:');

        $adapter = Redis::fromExistingConnection($connection);
        $adapter->flushRedis();

        $this->itShouldRejectInvalidLabelNames($adapter);
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidLabelNamesWithInMemory()
    {
        $adapter = new InMemory();
        $adapter->flushMemory();

        $this->itShouldRejectInvalidLabelNames($adapter);
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidLabelNamesWithAPC()
    {
        $adapter = new APC();
        $adapter->flushAPC();

        $this->itShouldRejectInvalidLabelNames($adapter);
    }

    /**
     * @test
     * @dataProvider labelValuesDataProvider
     */
    public function isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValuesWithRedis($value)
    {
        $adapter = new Redis(['host' => REDIS_HOST]);
        $adapter->flushRedis();

        $this->isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValues($adapter, $value);
    }

    /**
     * @test
     * @dataProvider labelValuesDataProvider
     */
    public function isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValuesWithRedisWithPrefix($value)
    {
        $connection = new \Redis();
        $connection->connect(REDIS_HOST);

        $connection->setOption(\Redis::OPT_PREFIX, 'prefix:');

        $adapter = Redis::fromExistingConnection($connection);
        $adapter->flushRedis();

        $this->isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValues($adapter, $value);
    }

    /**
     * @test
     * @dataProvider labelValuesDataProvider
     */
    public function isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValuesWithInMemory($value)
    {
        $adapter = new InMemory();
        $adapter->flushMemory();

        $this->isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValues($adapter, $value);
    }

    /**
     * @test
     * @dataProvider labelValuesDataProvider
     */
    public function isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValuesWithAPC($value)
    {
        $adapter = new APC();
        $adapter->flushAPC();

        $this->isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValues($adapter, $value);
    }

    private function itShouldIncreaseWithLabels(Adapter $adapter)
    {
        $counter = new Counter($adapter, 'test', 'some_metric', 'this is for testing', ['foo', 'bar']);
        $counter->inc(['lalal', 'lululu']);
        $counter->inc(['lalal', 'lululu']);
        $counter->inc(['lalal', 'lululu']);
        $this->assertThat(
            $adapter->collect(),
            $this->equalTo(
                [
                    new MetricFamilySamples(
                        [
                            'type' => Counter::TYPE,
                            'help' => 'this is for testing',
                            'name' => 'test_some_metric',
                            'labelNames' => ['foo', 'bar'],
                            'samples' => [
                                [
                                    'labelValues' => ['lalal', 'lululu'],
                                    'value' => 3,
                                    'name' => 'test_some_metric',
                                    'labelNames' => [],
                                ],
                            ],
                        ]
                    ),
                ]
            )
        );
    }

    private function itShouldIncreaseWithoutLabelWhenNoLabelsAreDefined(Adapter $adapter)
    {
        $counter = new Counter($adapter, 'test', 'some_metric', 'this is for testing');
        $counter->inc();
        $this->assertThat(
            $adapter->collect(),
            $this->equalTo(
                [
                    new MetricFamilySamples(
                        [
                            'type' => Counter::TYPE,
                            'help' => 'this is for testing',
                            'name' => 'test_some_metric',
                            'labelNames' => [],
                            'samples' => [
                                [
                                    'labelValues' => [],
                                    'value' => 1,
                                    'name' => 'test_some_metric',
                                    'labelNames' => [],
                                ],
                            ],
                        ]
                    ),
                ]
            )
        );
    }

    private function itShouldIncreaseTheCounterByAnArbitraryInteger(Adapter $adapter)
    {
        $counter = new Counter($adapter, 'test', 'some_metric', 'this is for testing', ['foo', 'bar']);
        $counter->inc(['lalal', 'lululu']);
        $counter->incBy(123, ['lalal', 'lululu']);
        $this->assertThat(
            $adapter->collect(),
            $this->equalTo(
                [
                    new MetricFamilySamples(
                        [
                            'type' => Counter::TYPE,
                            'help' => 'this is for testing',
                            'name' => 'test_some_metric',
                            'labelNames' => ['foo', 'bar'],
                            'samples' => [
                                [
                                    'labelValues' => ['lalal', 'lululu'],
                                    'value' => 124,
                                    'name' => 'test_some_metric',
                                    'labelNames' => [],
                                ],
                            ],
                        ]
                    ),
                ]
            )
        );
    }

    private function itShouldRejectInvalidMetricsNames(Adapter $adapter)
    {
        $this->expectException(InvalidArgumentException::class);
        new Counter($adapter, 'test', 'some metric invalid metric', 'help');
    }

    private function itShouldRejectInvalidLabelNames(Adapter $adapter)
    {
        $this->expectException(InvalidArgumentException::class);
        new Counter($adapter, 'test', 'some_metric', 'help', ['invalid label']);
    }

    private function isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValues(Adapter $adapter, $value)
    {
        $label = 'foo';
        $histogram = new Counter($adapter, 'test', 'some_metric', 'help', [$label]);
        $histogram->inc([$value]);

        $metrics = $adapter->collect();
        $this->assertIsArray($metrics);
        $this->assertCount(1, $metrics);
        $this->assertContainsOnlyInstancesOf(MetricFamilySamples::class, $metrics);

        $metric = reset($metrics);
        $samples = $metric->getSamples();
        $this->assertContainsOnlyInstancesOf(Sample::class, $samples);

        foreach ($samples as $sample) {
            $labels = array_combine(
                array_merge($metric->getLabelNames(), $sample->getLabelNames()),
                $sample->getLabelValues()
            );
            $this->assertEquals($value, $labels[$label]);
        }
    }

    public function labelValuesDataProvider(): array
    {
        $cases = [];
        // Basic Latin
        // See https://en.wikipedia.org/wiki/List_of_Unicode_characters#Basic_Latin
        for ($i = 32; $i <= 121; $i++) {
            $cases['ASCII code ' . $i] = [chr($i)];
        }
        return $cases;
    }
}
