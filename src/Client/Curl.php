<?php

namespace WbOpenApi\Client;

use InvalidArgumentException;
use WbOpenApi\Exceptions\RequestException;

class Curl implements OpenApiClientInterface
{
    /**
     * Base URL
     */
    private $baseUrl = 'https://suppliers-api.wildberries.ru';
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param string $apiKey
     * @param string|null $baseUrl
     */
    public function __construct(string $apiKey, string $baseUrl = null)
    {
        $this->apiKey = $apiKey;

        if (!is_null($baseUrl)) {
            $this->baseUrl = $baseUrl;
        }
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $timeout
     * @return array|null
     * @throws RequestException
     */
    public function get(string $uri, array $data = [], array $headers = [], int $timeout = 30): ?array
    {
        return $this->request('get', $uri, $data, $headers);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $timeout
     * @return array|null
     * @throws RequestException
     */
    public function request(string $method, string $uri, array $data = [], array $headers = [], int $timeout = 30): ?array
    {
        if (!in_array($method, ['get', 'post'])) {
            throw new InvalidArgumentException('Request method must be get or post');
        }

        $ch = curl_init();

        if (count($data)) {
            if ($method === 'get') {
                $uri .= '?' . http_build_query($data);
            } elseif ($method === 'post') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        if ($method === 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
        }

        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/' . $uri);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, [
            'Accept: application/json',
            'Authorization: ' . $this->apiKey,
            'Content-Type: application/json'
        ]));

        $response = curl_exec($ch);
        $responseInfo = curl_getinfo($ch);

        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($responseInfo['http_code'] != 200) {
            throw new RequestException('Request error: "' . $response . '"');
        }

        if ($errno) {
            throw new RequestException('Request error ' . $error, $errno);
        }

        $decodedResponse = json_decode($response, true);

        if (isset($decodedResponse['errors'])) {
            throw new RequestException('Request error: ' . implode('; ', $decodedResponse['errors']));
        }

        return $decodedResponse;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $timeout
     * @return array|null
     * @throws RequestException
     */
    public function post(string $uri, array $data = [], array $headers = [], int $timeout = 30): ?array
    {
        return $this->request('post', $uri, $data, $headers);
    }
}
