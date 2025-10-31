<?php

namespace DigitalOceanAccountBundle\Abstract;

use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Exception\DigitalOceanException;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * DigitalOcean服务基类
 */
#[Autoconfigure(autowire: false)]
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
        if (null === $config) {
            throw new DigitalOceanException('未配置 DigitalOcean API Key');
        }

        $request->setApiKey($config->getApiKey());

        return $request;
    }
}
