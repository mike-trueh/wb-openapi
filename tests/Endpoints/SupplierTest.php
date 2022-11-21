<?php

namespace Endpoints;

use DateTime;
use InvalidArgumentException;
use TestCase;
use WbOpenApi\Client\CurlStats;
use WbOpenApi\Endpoints\Statistics;

class SupplierTest extends TestCase
{
    public function testIncomes()
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierIncomes'));

        $result = (new Statistics($mock))->incomes(new DateTime());
        $this->assertArrayHasKey('incomeId', $result[0]);
        $this->assertCount(1, $result);
    }

    public function testStocks()
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierStocks'));

        $result = (new Statistics($mock))->stocks(new DateTime());
        $this->assertArrayHasKey('SCCode', $result[0]);
        $this->assertCount(1, $result);
    }

    /**
     * @return int[][]
     */
    public function invalidFlagValues(): array
    {
        return [[-1], [2]];
    }

    /**
     * @dataProvider invalidFlagValues
     */
    public function testOrdersWithError($invalidFlagValue)
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierOrders'));

        $this->expectException(InvalidArgumentException::class);
        (new Statistics($mock))->orders(new DateTime(), $invalidFlagValue);
    }

    public function testOrders()
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierOrders'));

        $result = (new Statistics($mock))->orders(new DateTime());
        $this->assertArrayHasKey('supplierArticle', $result[0]);
        $this->assertCount(1, $result);
    }

    /**
     * @dataProvider invalidFlagValues
     */
    public function testSalesWithError($invalidFlagValue)
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierSales'));

        $this->expectException(InvalidArgumentException::class);
        (new Statistics($mock))->sales(new DateTime(), $invalidFlagValue);
    }

    public function testSales()
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierSales'));

        $result = (new Statistics($mock))->sales(new DateTime());
        $this->assertArrayHasKey('supplierArticle', $result[0]);
        $this->assertCount(1, $result);
    }

    public function testReportWithError()
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierReport'));

        $this->expectException(InvalidArgumentException::class);
        (new Statistics($mock))->reportDetailByPeriod(new DateTime(), new DateTime('yesterday'));
    }

    public function testReport()
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierReport'));

        $result = (new Statistics($mock))->reportDetailByPeriod(new DateTime('yesterday'), new DateTime());
        $this->assertArrayHasKey('realizationreport_id', $result[0]);
        $this->assertCount(1, $result);
    }

    public function testExcise()
    {
        $mock = $this->getMockBuilder(CurlStats::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('SupplierExcise'));

        $result = (new Statistics($mock))->exciseGoods(new DateTime());
        $this->assertArrayHasKey('id', $result[0]);
        $this->assertCount(1, $result);
    }
}