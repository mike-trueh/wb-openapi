<?php

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function loadResponse(string $name)
    {
        $path = sprintf('%s/Response/%s.json', __DIR__, $name);

        $this->assertFileExists($path);

        return json_decode(file_get_contents($path), true);
    }

    public function loadRequest(string $name)
    {
        $path = sprintf('%s/Request/%s.json', __DIR__, $name);

        $this->assertFileExists($path);

        return json_decode(file_get_contents($path), true);
    }
}