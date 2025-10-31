<?php

namespace DigitalOceanAccountBundle\Request;

use HttpClientBundle\Request\ApiRequest;

/**
 * DigitalOcean API 请求基类
 */
abstract class DigitalOceanRequest extends ApiRequest
{
    private ?string $apiKey = null;

    /**
     * 设置API Key
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * 获取API Key
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * 默认 GET 请求方法
     */
    public function getRequestMethod(): ?string
    {
        return 'GET';
    }

    /**
     * 默认空选项，认证头由Client统一处理
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        return [];
    }
}
