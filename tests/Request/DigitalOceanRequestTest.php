<?php

namespace DigitalOceanAccountBundle\Tests\Request;

use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(DigitalOceanRequest::class)]
final class DigitalOceanRequestTest extends RequestTestCase
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

    public function testSetAndGetApiKeyReturnsExpectedKey(): void
    {
        $request = $this->createRequestInstance();
        $apiKey = 'test-api-key-12345';

        $request->setApiKey($apiKey);

        $this->assertEquals($apiKey, $request->getApiKey());
    }

    public function testGetApiKeyWhenNotSetReturnsNull(): void
    {
        $request = $this->createRequestInstance();

        $this->assertNull($request->getApiKey());
    }

    public function testGetRequestMethodReturnsGetByDefault(): void
    {
        $request = $this->createRequestInstance();

        $this->assertEquals('GET', $request->getRequestMethod());
    }

    public function testGetRequestOptionsReturnsEmptyArrayByDefault(): void
    {
        $request = $this->createRequestInstance();

        $this->assertEquals([], $request->getRequestOptions());
    }

    public function testGetRequestPathReturnsExpectedPath(): void
    {
        $request = $this->createRequestInstance();

        $this->assertEquals('/test-path', $request->getRequestPath());
    }
}
