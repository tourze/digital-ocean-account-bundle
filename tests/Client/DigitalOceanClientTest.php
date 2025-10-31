<?php

namespace DigitalOceanAccountBundle\Tests\Client;

use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Exception\MissingApiKeyException;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use DigitalOceanAccountBundle\Tests\Helper\MockResponse;
use HttpClientBundle\Request\RequestInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(DigitalOceanClient::class)]
#[RunTestsInSeparateProcesses]
final class DigitalOceanClientTest extends AbstractIntegrationTestCase
{
    private DigitalOceanClient $client;

    protected function onSetUp(): void
    {
        $this->client = self::getService(DigitalOceanClient::class);
    }

    public function testGetBaseUrlReturnsCorrectUrl(): void
    {
        $this->assertEquals('https://api.digitalocean.com/v2', $this->client->getBaseUrl());
    }

    public function testGetRequestUrlReturnsCorrectUrl(): void
    {
        $request = new class implements RequestInterface {
            public function getRequestPath(): string
            {
                return '/test-path';
            }

            public function getRequestMethod(): string
            {
                return 'GET';
            }

            public function getRequestOptions(): ?array
            {
                return null;
            }
        };

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($this->client);
        $method = $reflection->getMethod('getRequestUrl');
        $method->setAccessible(true);

        $url = $method->invoke($this->client, $request);

        $this->assertEquals('https://api.digitalocean.com/v2/test-path', $url);
    }

    public function testGetRequestMethodReturnsMethodFromRequest(): void
    {
        $request = new class implements RequestInterface {
            public function getRequestPath(): string
            {
                return '/test';
            }

            public function getRequestMethod(): string
            {
                return 'POST';
            }

            public function getRequestOptions(): ?array
            {
                return null;
            }
        };

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($this->client);
        $method = $reflection->getMethod('getRequestMethod');
        $method->setAccessible(true);

        $requestMethod = $method->invoke($this->client, $request);

        $this->assertEquals('POST', $requestMethod);
    }

    public function testGetRequestOptionsWithApiKeyAddsAuthorizationHeader(): void
    {
        $apiKey = 'test-api-key-12345';
        // 使用匿名类替代 DigitalOceanRequest Mock
        $request = new class($apiKey) extends DigitalOceanRequest {
            private string $apiKey;

            public function __construct(string $apiKey)
            {
                $this->apiKey = $apiKey;
            }

            /**
             * @return array<string, mixed>
             */
            public function getRequestOptions(): array
            {
                return [];
            }

            public function getApiKey(): string
            {
                return $this->apiKey;
            }

            // 实现抽象方法
            public function getRequestPath(): string
            {
                return '/test';
            }

            public function getRequestMethod(): string
            {
                return 'GET';
            }
        };

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($this->client);
        $method = $reflection->getMethod('getRequestOptions');
        $method->setAccessible(true);

        $options = $method->invoke($this->client, $request);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('headers', $options);
        $this->assertIsArray($options['headers']);
        $this->assertEquals('Bearer ' . $apiKey, $options['headers']['Authorization']);
        $this->assertEquals('application/json', $options['headers']['Content-Type']);
    }

    public function testGetRequestOptionsWithoutApiKeyThrowsException(): void
    {
        // 使用匿名类替代 DigitalOceanRequest Mock
        $request = new class extends DigitalOceanRequest {
            /**
             * @return array<string, mixed>
             */
            public function getRequestOptions(): array
            {
                return [];
            }

            public function getApiKey(): ?string
            {
                return null;
            }

            // 实现抽象方法
            public function getRequestPath(): string
            {
                return '/test';
            }

            public function getRequestMethod(): string
            {
                return 'GET';
            }
        };

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($this->client);
        $method = $reflection->getMethod('getRequestOptions');
        $method->setAccessible(true);

        $this->expectException(MissingApiKeyException::class);
        $this->expectExceptionMessage('请求缺少API Key');

        $method->invoke($this->client, $request);
    }

    public function testFormatResponseParsesJsonCorrectly(): void
    {
        $request = new class implements RequestInterface {
            public function getRequestPath(): string
            {
                return '/test';
            }

            public function getRequestMethod(): string
            {
                return 'GET';
            }

            public function getRequestOptions(): ?array
            {
                return null;
            }
        };

        $jsonResponse = '{"key": "value", "nested": {"subkey": "subvalue"}}';
        $response = $this->createMockResponse($jsonResponse);

        $expectedArray = [
            'key' => 'value',
            'nested' => [
                'subkey' => 'subvalue',
            ],
        ];

        // 使用反射调用受保护的方法
        $reflection = new \ReflectionClass($this->client);
        $method = $reflection->getMethod('formatResponse');
        $method->setAccessible(true);

        $result = $method->invoke($this->client, $request, $response);

        $this->assertEquals($expectedArray, $result);
    }

    private function createMockResponse(string $content): ResponseInterface
    {
        return new MockResponse($content);
    }
}
