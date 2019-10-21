<?php
declare(strict_types=1);

namespace Prometheus;

use InvalidArgumentException;
use Prometheus\Storage\Adapter;

class Summary extends Collector
{
    const TYPE = 'summary';

    /**
     * @var array|null
     */
    private $quantiles;

    /**
     * @param Adapter $adapter
     * @param string $namespace
     * @param string $name
     * @param string $help
     * @param array $labels
     * @param array $quantiles
     * @throws InvalidArgumentException
     */
    public function __construct(Adapter $adapter, $namespace, $name, $help, $labels = [], $quantiles = null)
    {
        parent::__construct($adapter, $namespace, $name, $help, $labels);

        if (null === $quantiles) {
            $quantiles = self::getDefaultQuantiles();
        }

        if (0 === count($quantiles)) {
            throw new InvalidArgumentException('Summary must have at least one quantile.');
        }

        for ($i = 0; $i < count($quantiles) - 1; $i++) {
            if ($quantiles[$i] >= $quantiles[$i + 1]) {
                throw new InvalidArgumentException(
                    'Summary quantiles must be in increasing order: ' .
                    $quantiles[$i] . ' >= ' . $quantiles[$i + 1]
                );
            }
        }
        foreach ($labels as $label) {
            if ($label === 'quantile') {
                throw new InvalidArgumentException('Summary cannot have a label named "quantile".');
            }
        }
        $this->quantiles = $quantiles;
    }

    /**
     * List of default quantiles
     * @return array
     */
    public static function getDefaultQuantiles(): array
    {
        return [
            0.01, 0.05, 0.5, 0.9, 0.99,
        ];
    }

    /**
     * @param double $value e.g. 123
     * @param array $labels e.g. ['status', 'opcode']
     */
    public function observe($value, $labels = []): void
    {
        $this->assertLabelsAreDefinedCorrectly($labels);
        $this->storageAdapter->updateSummary(
            [
                'value' => $value,
                'name' => $this->getName(),
                'help' => $this->getHelp(),
                'type' => $this->getType(),
                'labelNames' => $this->getLabelNames(),
                'labelValues' => $labels,
                'quantiles' => $this->quantiles,
            ]
        );
    }

    /**
     * @param float $percentile
     * @param array $values
     * @return float
     */
    public static function getQuantile($percentile, $values): float
    {
        sort($values);
        $index = (int)($percentile * count($values));
        return (floor($index) === $index)
            ? ($values[$index - 1] + $values[$index]) / 2
            : $result = $values[(int)floor($index)];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}