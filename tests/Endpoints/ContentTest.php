<?php

namespace Endpoints;

use TestCase;
use WbOpenApi\Client\Curl;
use WbOpenApi\Endpoints\Content;

class ContentTest extends TestCase
{
    public function testCardsList()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('CardsList'));

        $cards = new Content($mock);
        $result = $cards->cardsList();

        $this->assertFalse($result['error']);
        $this->assertArrayHasKey('data', $result);
        $this->assertCount(1, $result['data']['cards']);
    }

    public function testCardsCursorList()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('CardsCursorList'));

        $cards = new Content($mock);
        $result = $cards->cardsCursorList();

        $this->assertFalse($result['error']);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('cursor', $result['data']);
        $this->assertArrayHasKey('cards', $result['data']);
        $this->assertCount(1, $result['data']['cards']);
    }

    public function testCardsErrorList()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('CardsErrorList'));

        $cards = new Content($mock);
        $result = $cards->cardsErrorList();

        $this->assertFalse($result['error']);
        $this->assertArrayHasKey('data', $result);
        $this->assertCount(1, $result['data']);
    }

    public function testCardsFilter()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('CardsFilter'));

        $cards = new Content($mock);
        $result = $cards->cardsFilter(['6000000001']);

        $this->assertFalse($result['error']);
        $this->assertArrayHasKey('data', $result);
        $this->assertCount(1, $result['data']);
    }

    public function testCardsUpdate()
    {
        $mock = $this->getMockBuilder(Curl::class)->setConstructorArgs([''])->onlyMethods(['request'])->getMock();
        $mock->method('request')->willReturn($this->loadResponse('CardsUpdate'));

        $cards = new Content($mock);
        $result = $cards->cardsUpdate($this->loadRequest('CardsUpdate'));

        $this->assertFalse($result['error']);
        $this->assertArrayHasKey('data', $result);
        $this->assertNull($result['data']);
    }
}