<?php

namespace Endpoints;

use InvalidArgumentException;
use TestCase;
use WbOpenApi\Client\Curl;
use WbOpenApi\Endpoints\Prices;

class PricesTest extends TestCase
{
    public function testInfo()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('PricesInfo'));

        $priceInfo = new Prices($mock);

        $this->expectException(InvalidArgumentException::class);
        $priceInfo->info(-1);

        $this->expectException(InvalidArgumentException::class);
        $priceInfo->info(3);

        $result = $priceInfo->info();
        $this->assertEquals([["nmId" => 1234567, "price" => 1000, "discount" => 10, "promoCode" => 5]], $result);
    }

    public function testPrices()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn(null);

        $result = (new Prices($mock))->prices([['nmId' => 1, 'price' => 1]]);
        $this->assertNull($result);
    }
}