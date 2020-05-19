<?php

namespace Prometheus;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;
use RuntimeException;

use function strtolower;

class PushGateway
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * PushGateway constructor.
     *
     * @param string $address host:port of the push gateway
     * @param ClientInterface $client
     */
    public function __construct($address, ClientInterface $client = null)
    {
        $this->address = $address;
        $this->client = $client ?? new Client();
    }

    /**
     * Pushes all metrics in a Collector, replacing all those with the same job.
     * Uses HTTP PUT.
     *
     * @param CollectorRegistry $collectorRegistry
     * @param string $job
     * @param array $groupingKey
     * @throws RequestException
     */
    public function push(CollectorRegistry $collectorRegistry, string $job, array $groupingKey = [])
    {
        $this->doRequest($collectorRegistry, $job, $groupingKey, 'put');
    }

    /**
     * Pushes all metrics in a Collector, replacing only previously pushed metrics of the same name and job.
     * Uses HTTP POST.
     *
     * @param CollectorRegistry $collectorRegistry
     * @param $job
     * @param $groupingKey
     * @throws RequestException
     */
    public function pushAdd(CollectorRegistry $collectorRegistry, string $job, array $groupingKey = [])
    {
        $this->doRequest($collectorRegistry, $job, $groupingKey, 'post');
    }

    /**
     * Deletes metrics from the Push Gateway.
     * Uses HTTP POST.
     *
     * @param string $job
     * @param array $groupingKey
     * @throws RequestException
     */
    public function delete(string $job, array $groupingKey = [])
    {
        $this->doRequest(null, $job, $groupingKey, 'delete');
    }

    /**
     * @param CollectorRegistry $collectorRegistry
     * @param string $job
     * @param array $groupingKey
     * @param string $method
     * @throws RequestException
     */
    private function doRequest(CollectorRegistry $collectorRegistry, string $job, array $groupingKey, $method)
    {
        $url = "http://" . $this->address . "/metrics/job/" . $job;
        if (!empty($groupingKey)) {
            foreach ($groupingKey as $label => $value) {
                $url .= "/" . $label . "/" . $value;
            }
        }

        $requestOptions = [
            'headers'         => [
                'Content-Type' => RenderTextFormat::MIME_TYPE,
            ],
            'connect_timeout' => 10,
            'timeout'         => 20,
        ];

        if ($method !== 'delete') {
            $renderer = new RenderTextFormat();
            $requestOptions['body'] = $renderer->render($collectorRegistry->getMetricFamilySamples());
        }
        $response = $this->request($method, $url, $requestOptions);
        $statusCode = $response->getStatusCode();
        if (!in_array($statusCode, [200, 202])) {
            $msg = "Unexpected status code "
                . $statusCode
                . " received from push gateway "
                . $this->address . ": " . $response->getBody();
            throw new RuntimeException($msg);
        }
    }

    /**
     * @param $method
     * @param $url
     * @param $requestOptions
     * @throws RequestException
     * @return ResponseInterface
     */
    private function request($method, $url, $requestOptions): ResponseInterface
    {
        switch (strtolower($method)) {
            case 'get':
                return $this->client->get($url, $requestOptions);
            case 'post':
                return $this->client->post($url, $requestOptions);
            case 'put':
                return $this->client->put($url, $requestOptions);
            case 'patch':
                return $this->client->patch($url, $requestOptions);
            case 'delete':
                return $this->client->delete($url, $requestOptions);
            default:
                throw new RuntimeException('Invalid HTTP method requested.');
        }
    }
}
