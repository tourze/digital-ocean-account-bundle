<?php

namespace DigitalOceanAccountBundle\Service;

use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * DigitalOcean服务基类
 */
abstract class AbstractDigitalOceanService
{
    public function __construct(
        protected readonly DigitalOceanClient $client,
        protected readonly DigitalOceanConfigService $configService,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 为请求设置API Key
     */
    protected function prepareRequest(DigitalOceanRequest $request): DigitalOceanRequest
    {
        $config = $this->configService->getConfig();
        if ($config === null) {
            throw new \RuntimeException('未配置 DigitalOcean API Key');
        }

        $request->setApiKey($config->getApiKey());

        return $request;
    }
}
