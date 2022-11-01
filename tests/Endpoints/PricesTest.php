<?php

namespace Endpoints;

use InvalidArgumentException;
use TestCase;
use WbOpenApi\Client\Curl;
use WbOpenApi\Endpoints\Prices;

class PricesTest extends TestCase
{
    /**
     * @return int[][]
     */
    public function invalidInfoValues(): array
    {
        return [[-1], [3]];
    }

    /**
     * @dataProvider invalidInfoValues
     */
    public function testInfoWithErrors($invalidInfoValue)
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('PricesInfo'));

        $this->expectException(InvalidArgumentException::class);
        (new Prices($mock))->info($invalidInfoValue);
    }

    public function testInfo()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('PricesInfo'));

        $result = (new Prices($mock))->info();
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