<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use DigitalOceanAccountBundle\Request\DigitalOceanRequest;

/**
 * 测试用的带API Key的DigitalOcean请求类
 *
 * @internal
 */
final class TestDigitalOceanRequestWithApiKey extends DigitalOceanRequest
{
    private string $requestPath = '/test';

    private string $requestMethod = 'GET';

    /** @var array<string, mixed> */
    private array $requestOptions = [];

    private bool $setApiKeyCalled = false;

    private ?string $receivedApiKey = null;

    public function setRequestPath(string $path): void
    {
        $this->requestPath = $path;
    }

    public function getRequestPath(): string
    {
        return $this->requestPath;
    }

    public function setRequestMethod(string $method): void
    {
        $this->requestMethod = $method;
    }

    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function setRequestOptions(array $options): void
    {
        $this->requestOptions = $options;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequestOptions(): array
    {
        return $this->requestOptions;
    }

    /**
     * 重写setApiKey方法以记录调用
     */
    public function setApiKey(string $apiKey): void
    {
        $this->setApiKeyCalled = true;
        $this->receivedApiKey = $apiKey;
        parent::setApiKey($apiKey);
    }

    /**
     * 检查是否调用了setApiKey
     */
    public function wasSetApiKeyCalled(): bool
    {
        return $this->setApiKeyCalled;
    }

    /**
     * 获取接收到的API Key
     */
    public function getReceivedApiKey(): ?string
    {
        return $this->receivedApiKey;
    }
}
