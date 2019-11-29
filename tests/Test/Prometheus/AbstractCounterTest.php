<?php

namespace Test\Prometheus;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prometheus\Counter;
use Prometheus\MetricFamilySamples;
use Prometheus\Sample;
use Prometheus\Storage\Adapter;

/**
 * See https://prometheus.io/docs/instrumenting/exposition_formats/
 */
abstract class AbstractCounterTest extends TestCase
{
    /**
     * @var Adapter
     */
    public $adapter;

    public function setUp(): void
    {
        $this->configureAdapter();
    }

    abstract public function configureAdapter();

    /**
     * @test
     */
    public function itShouldIncreaseWithLabels()
    {
        $gauge = new Counter($this->adapter, 'test', 'some_metric', 'this is for testing', ['foo', 'bar']);
        $gauge->inc(['lalal', 'lululu']);
        $gauge->inc(['lalal', 'lululu']);
        $gauge->inc(['lalal', 'lululu']);
        $this->assertThat(
            $this->adapter->collect(),
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

    /**
     * @test
     */
    public function itShouldIncreaseWithoutLabelWhenNoLabelsAreDefined()
    {
        $gauge = new Counter($this->adapter, 'test', 'some_metric', 'this is for testing');
        $gauge->inc();
        $this->assertThat(
            $this->adapter->collect(),
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

    /**
     * @test
     */
    public function itShouldIncreaseTheCounterByAnArbitraryInteger()
    {
        $gauge = new Counter($this->adapter, 'test', 'some_metric', 'this is for testing', ['foo', 'bar']);
        $gauge->inc(['lalal', 'lululu']);
        $gauge->incBy(123, ['lalal', 'lululu']);
        $this->assertThat(
            $this->adapter->collect(),
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

    /**
     * @test
     */
    public function itShouldRejectInvalidMetricsNames()
    {
        $this->expectException(InvalidArgumentException::class);
        new Counter($this->adapter, 'test', 'some metric invalid metric', 'help');
    }

    /**
     * @test
     */
    public function itShouldRejectInvalidLabelNames()
    {
        $this->expectException(InvalidArgumentException::class);
        new Counter($this->adapter, 'test', 'some_metric', 'help', ['invalid label']);
    }

    /**
     * @test
     * @dataProvider labelValuesDataProvider
     *
     * @param mixed $value The label value
     */
    public function isShouldAcceptAnySequenceOfBasicLatinCharactersForLabelValues($value)
    {
        $label = 'foo';
        $histogram = new Counter($this->adapter, 'test', 'some_metric', 'help', [$label]);
        $histogram->inc([$value]);

        $metrics = $this->adapter->collect();
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

    /**
     * @return array
     * @see isShouldAcceptArbitraryLabelValues
     */
    public function labelValuesDataProvider()
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
