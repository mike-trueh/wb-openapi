<?php

namespace WbOpenApi\Endpoints;

use InvalidArgumentException;
use WbOpenApi\Client\OpenApiClientInterface;

class Supply implements ApiInterface
{
    /**
     * Supply types
     */
    const TYPES = ['ACTIVE', 'ON_DELIVERY'];

    /**
     * @var OpenApiClientInterface
     */
    private $client;

    /**
     * @inheritDoc
     */
    public function __construct(OpenApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Список активных поставок
     *
     * @return array|null
     */
    public function getActiveSupplies(): ?array
    {
        return $this->getSupplies('ACTIVE');
    }

    /**
     * Список поставок
     *
     * @link https://openapi.wildberries.ru/#tag/Marketplace/paths/~1api~1v2~1supplies/get
     * @param string $type Enum: ACTIVE - активные поставки, ON_DELIVERY - поставки в пути (которые ещё не приняты на складе).
     * @return array|null
     */
    public function getSupplies(string $type = 'ACTIVE'): ?array
    {
        if (!in_array($type, static::TYPES)) {
            throw new InvalidArgumentException('Type should be ' . implode(',', static::TYPES) . '. ' . $type . ' given');
        }

        return $this->client->get('api/v2/supplies');
    }

    /**
     * Список поставок в пути
     *
     * @return array|null
     */
    public function getDeliverySupplies(): ?array
    {
        return $this->getSupplies('ON_DELIVERY');
    }

    /**
     * Создание новой поставки.
     *
     * @link https://openapi.wildberries.ru/#tag/Marketplace/paths/~1api~1v2~1supplies/post
     * @return array|null
     */
    public function newSupply(): ?array
    {
        return $this->client->post('api/v2/supplies');
    }
}