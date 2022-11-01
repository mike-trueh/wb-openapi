<?php

namespace Endpoints;

use InvalidArgumentException;
use TestCase;
use WbOpenApi\Client\Curl;
use WbOpenApi\Endpoints\Supply;

class SupplyTest extends TestCase
{
    public function testGetWithError()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SuppliesList'));

        $this->expectException(InvalidArgumentException::class);
        (new Supply($mock))->getSupplies('INVALID');
    }

    public function testGet()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SuppliesList'));

        $expectedResult = ['supplies' => [["supplyId" => "WB-GI-1234567"]]];

        $result = (new Supply($mock))->getSupplies();
        $this->assertEquals($expectedResult, $result);

        $result = (new Supply($mock))->getActiveSupplies();
        $this->assertEquals($expectedResult, $result);

        $result = (new Supply($mock))->getDeliverySupplies();
        $this->assertEquals($expectedResult, $result);
    }

    public function testNewSupply()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('NewSupply'));

        $result = (new Supply($mock))->newSupply();
        $this->assertEquals(["supplyId" => "WB-GI-1234567"], $result);
    }
}