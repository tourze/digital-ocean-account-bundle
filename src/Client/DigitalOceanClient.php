<?php

namespace DigitalOceanAccountBundle\Client;

use DigitalOceanAccountBundle\Exception\MissingApiKeyException;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use HttpClientBundle\Client\ApiClient;
use HttpClientBundle\Request\RequestInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;

/**
 * DigitalOcean API 客户端
 */
#[WithMonologChannel(channel: 'digital_ocean_account')]
class DigitalOceanClient extends ApiClient
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly HttpClientInterface $httpClient,
        private readonly LockFactory $lockFactory,
        private readonly CacheInterface $cache,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly AsyncInsertService $asyncInsertService,
    ) {
    }

    protected function getLockFactory(): LockFactory
    {
        return $this->lockFactory;
    }

    protected function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function getCache(): CacheInterface
    {
        return $this->cache;
    }

    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    protected function getAsyncInsertService(): AsyncInsertService
    {
        return $this->asyncInsertService;
    }

    protected function getRequestUrl(RequestInterface $request): string
    {
        return 'https://api.digitalocean.com/v2' . $request->getRequestPath();
    }

    protected function getRequestMethod(RequestInterface $request): string
    {
        return $request->getRequestMethod() ?? 'GET';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getRequestOptions(RequestInterface $request): ?array
    {
        /** @var array<string, mixed> $options */
        $options = $request->getRequestOptions() ?? [];

        // 添加认证头
        if (!isset($options['headers']) || !is_array($options['headers'])) {
            $options['headers'] = [];
        }

        // 从请求对象获取API Key
        if ($request instanceof DigitalOceanRequest) {
            $apiKey = $request->getApiKey();
            if (null !== $apiKey && '' !== $apiKey) {
                $options['headers']['Authorization'] = 'Bearer ' . $apiKey;
                $options['headers']['Content-Type'] = 'application/json';
            } else {
                throw new MissingApiKeyException('请求缺少API Key');
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
