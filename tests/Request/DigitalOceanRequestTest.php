<?php

namespace DigitalOceanAccountBundle\Tests\Request;

use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use PHPUnit\Framework\TestCase;

class DigitalOceanRequestTest extends TestCase
{
    /**
     * 创建一个匿名子类实例用于测试
     */
    private function createRequestInstance(): DigitalOceanRequest
    {
        return new class extends DigitalOceanRequest {
            public function getRequestPath(): string
            {
                return '/test-path';
            }
        };
    }

    public function testSetAndGetApiKey_returnsExpectedKey(): void
    {
        $request = $this->createRequestInstance();
        $apiKey = 'test-api-key-12345';

        $result = $request->setApiKey($apiKey);

        $this->assertSame($request, $result);
        $this->assertEquals($apiKey, $request->getApiKey());
    }

    public function testGetApiKey_whenNotSet_returnsNull(): void
    {
        $request = $this->createRequestInstance();

        $this->assertNull($request->getApiKey());
    }

    public function testGetRequestMethod_returnsGetByDefault(): void
    {
        $request = $this->createRequestInstance();

        $this->assertEquals('GET', $request->getRequestMethod());
    }

    public function testGetRequestOptions_returnsEmptyArrayByDefault(): void
    {
        $request = $this->createRequestInstance();

        $this->assertEquals([], $request->getRequestOptions());
    }

    public function testGetRequestPath_returnsExpectedPath(): void
    {
        $request = $this->createRequestInstance();

        $this->assertEquals('/test-path', $request->getRequestPath());
    }
}
