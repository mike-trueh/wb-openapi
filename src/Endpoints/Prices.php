<?php

namespace WbOpenApi\Endpoints;

use InvalidArgumentException;
use WbOpenApi\Client\OpenApiClientInterface;

class Prices implements ApiInterface
{
    /**
     * @var OpenApiClientInterface
     */
    private $client;

    /**
     * @param OpenApiClientInterface $client
     */
    public function __construct(OpenApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Получение информации о ценах
     *
     * @param int $quantity 0 - товар с любым остатком, 1 - товар с ненулевым остатком, 2 - товар с нулевым остатком
     * @return array
     */
    public function info(int $quantity = 0): array
    {
        if (($quantity < 0) || ($quantity > 2)) {
            throw new InvalidArgumentException('Quantity must be 0, 1 or 2');
        }

        return $this->client->get('public/api/v1/info');
    }

    /**
     * Загрузка цен. За раз можно загрузить не более 1000 номенклатур.
     *
     * @param array<int, array{nmId: integer, price: integer}> $prices
     * @return array|null
     */
    public function prices(array $prices): ?array
    {
        if (count($prices) > 1000) {
            throw new InvalidArgumentException('No more than 1000 nomenclatures can be loaded at a time');
        }

        return $this->client->post('public/api/v1/prices', $prices);
    }
}