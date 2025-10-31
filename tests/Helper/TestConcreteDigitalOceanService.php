<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use DigitalOceanAccountBundle\Abstract\AbstractDigitalOceanService;
use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * 测试用的具体DigitalOcean服务类
 *
 * @internal
 */
final class TestConcreteDigitalOceanService extends AbstractDigitalOceanService
{
    public function __construct(
        DigitalOceanClient $client,
        DigitalOceanConfigService $configService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
    ) {
        parent::__construct($client, $configService, $entityManager, $logger);
    }

    /**
     * 公开prepareRequest方法以供测试
     */
    public function testPrepareRequest(DigitalOceanRequest $request): DigitalOceanRequest
    {
        return $this->prepareRequest($request);
    }

    /**
     * 测试用方法
     */
    public function doSomething(): string
    {
        return 'test';
    }
}
