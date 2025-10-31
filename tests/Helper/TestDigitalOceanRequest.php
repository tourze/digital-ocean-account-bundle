<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use DigitalOceanAccountBundle\Request\DigitalOceanRequest;

/**
 * 测试用的DigitalOcean请求类
 *
 * @internal
 */
final class TestDigitalOceanRequest extends DigitalOceanRequest
{
    private string $requestPath = '/test';

    private string $requestMethod = 'GET';

    /** @var array<string, mixed> */
    private array $requestOptions = [];

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
}
