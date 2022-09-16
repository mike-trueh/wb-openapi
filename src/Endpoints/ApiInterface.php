<?php

namespace WbOpenApi\Endpoints;

use WbOpenApi\Client\OpenApiClientInterface;

interface ApiInterface
{
    /**
     * @param OpenApiClientInterface $client
     */
    public function __construct(OpenApiClientInterface $client);
}