<?php

namespace DigitalOceanAccountBundle\Tests\Client;

use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use HttpClientBundle\Request\RequestInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DigitalOceanClientTest extends TestCase
{
    public function testGetBaseUrl_returnsCorrectUrl(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $client = new DigitalOceanClient($httpClient);

        $this->assertEquals('https://api.digitalocean.com/v2', $client->getBaseUrl());
    }

    public function testGetRequestUrl_returnsCorrectUrl(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $client = new DigitalOceanClient($httpClient);

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestPath')
            ->willReturn('/test-path');

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('getRequestUrl');
        $method->setAccessible(true);

        $url = $method->invoke($client, $request);

        $this->assertEquals('https://api.digitalocean.com/v2/test-path', $url);
    }

    public function testGetRequestMethod_returnsMethodFromRequest(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $client = new DigitalOceanClient($httpClient);

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestMethod')
            ->willReturn('POST');

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('getRequestMethod');
        $method->setAccessible(true);

        $requestMethod = $method->invoke($client, $request);

        $this->assertEquals('POST', $requestMethod);
    }

    public function testGetRequestOptions_withApiKey_addsAuthorizationHeader(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $client = new DigitalOceanClient($httpClient);

        $apiKey = 'test-api-key-12345';
        $request = $this->createMock(DigitalOceanRequest::class);

        $request->expects($this->once())
            ->method('getRequestOptions')
            ->willReturn([]);

        $request->expects($this->once())
            ->method('getApiKey')
            ->willReturn($apiKey);

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('getRequestOptions');
        $method->setAccessible(true);

        $options = $method->invoke($client, $request);

        $this->assertIsArray($options);
        $this->assertArrayHasKey('headers', $options);
        $this->assertEquals('Bearer ' . $apiKey, $options['headers']['Authorization']);
        $this->assertEquals('application/json', $options['headers']['Content-Type']);
    }

    public function testGetRequestOptions_withoutApiKey_throwsException(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $client = new DigitalOceanClient($httpClient);

        $request = $this->createMock(DigitalOceanRequest::class);

        $request->expects($this->once())
            ->method('getRequestOptions')
            ->willReturn([]);

        $request->expects($this->once())
            ->method('getApiKey')
            ->willReturn(null);

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('getRequestOptions');
        $method->setAccessible(true);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('请求缺少API Key');

        $method->invoke($client, $request);
    }

    public function testFormatResponse_parsesJsonCorrectly(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $client = new DigitalOceanClient($httpClient);

        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $jsonResponse = '{"key": "value", "nested": {"subkey": "subvalue"}}';
        $expectedArray = [
            'key' => 'value',
            'nested' => [
                'subkey' => 'subvalue'
            ]
        ];

        $response->expects($this->once())
            ->method('getContent')
            ->willReturn($jsonResponse);

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('formatResponse');
        $method->setAccessible(true);

        $result = $method->invoke($client, $request, $response);

        $this->assertEquals($expectedArray, $result);
    }
}
