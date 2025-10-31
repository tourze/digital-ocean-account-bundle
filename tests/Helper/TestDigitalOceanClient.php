<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use HttpClientBundle\Request\RequestInterface;

/**
 * 测试用的DigitalOcean客户端
 *
 * @internal
 */
final class TestDigitalOceanClient
{
    /** @var array<string, mixed> */
    private array $requestResult = [];

    /**
     * 设置请求的返回结果
     *
     * @param array<string, mixed> $result
     */
    public function setRequestResult(array $result): void
    {
        $this->requestResult = $result;
    }

    /**
     * 模拟 request 方法
     *
     * @return array<string, mixed>
     */
    public function request(RequestInterface $request): array
    {
        return $this->requestResult;
    }

    /**
     * 获取基础URL
     */
    public function getBaseUrl(): string
    {
        return 'https://api.digitalocean.com/v2';
    }

    /**
     * 模拟获取请求URL
     */
    public function getRequestUrl(RequestInterface $request): string
    {
        return $this->getBaseUrl() . $request->getRequestPath();
    }

    /**
     * 模拟获取请求方法
     */
    public function getRequestMethod(RequestInterface $request): string
    {
        return $request->getRequestMethod() ?? 'GET';
    }

    /**
     * 模拟获取请求选项
     * @return array<array-key, mixed>|null
     */
    public function getRequestOptions(RequestInterface $request): ?array
    {
        return $request->getRequestOptions();
    }
}
