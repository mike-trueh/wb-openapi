<?php

namespace WbOpenApi\Client;

interface OpenApiClientInterface
{
    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey);

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $timeout
     * @return array|null
     */
    public function get(string $uri, array $data, array $headers, int $timeout): ?array;

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $timeout
     * @return array|null
     */
    public function post(string $uri, array $data, array $headers, int $timeout): ?array;

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $timeout
     * @return array|null
     */
    public function request(string $method, string $uri, array $data, array $headers, int $timeout): ?array;
}