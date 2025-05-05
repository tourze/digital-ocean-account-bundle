<?php

namespace DigitalOceanAccountBundle\Client;

use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use HttpClientBundle\Client\ApiClient;
use HttpClientBundle\Request\RequestInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * DigitalOcean API 客户端
 */
class DigitalOceanClient extends ApiClient
{
    /**
     * 获取请求URL
     */
    protected function getRequestUrl(RequestInterface $request): string
    {
        return 'https://api.digitalocean.com/v2' . $request->getRequestPath();
    }

    /**
     * 获取请求方法
     */
    protected function getRequestMethod(RequestInterface $request): string
    {
        return $request->getRequestMethod() ?? 'GET';
    }

    /**
     * 获取请求选项，添加API认证头
     */
    protected function getRequestOptions(RequestInterface $request): ?array
    {
        $options = $request->getRequestOptions() ?? [];

        // 添加认证头
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        // 从请求对象获取API Key
        if ($request instanceof DigitalOceanRequest) {
            $apiKey = $request->getApiKey();
            if ($apiKey) {
                $options['headers']['Authorization'] = 'Bearer ' . $apiKey;
                $options['headers']['Content-Type'] = 'application/json';
            } else {
                throw new \RuntimeException('请求缺少API Key');
            }
        }

        return $options;
    }

    /**
     * 格式化响应
     */
    protected function formatResponse(RequestInterface $request, ResponseInterface $response): mixed
    {
        $content = $response->getContent();
        return json_decode($content, true);
    }

    /**
     * 获取基础URL
     */
    public function getBaseUrl(): string
    {
        return 'https://api.digitalocean.com/v2';
    }
}
