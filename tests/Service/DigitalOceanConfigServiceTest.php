<?php

namespace DigitalOceanAccountBundle\Tests\Service;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(DigitalOceanConfigService::class)]
#[RunTestsInSeparateProcesses]
final class DigitalOceanConfigServiceTest extends AbstractIntegrationTestCase
{
    // LoggerInterface 不再需要 mock，因为使用容器中的真实服务

    private DigitalOceanConfigService $service;

    protected function onSetUp(): void
    {
        $this->setupTestServices();
    }

    private function setupTestServices(): void
    {
        // 从容器获取服务，遵循集成测试标准
        $this->service = self::getService(DigitalOceanConfigService::class);
    }

    public function testGetConfigWithExistingConfigReturnsConfig(): void
    {
        // 创建配置并持久化到数据库
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-api-key');

        $entityManager = self::getEntityManager();
        $entityManager->persist($config);
        $entityManager->flush();

        // 执行
        $result = $this->service->getConfig();

        // 断言
        $this->assertInstanceOf(DigitalOceanConfig::class, $result);
        $this->assertEquals('test-api-key', $result->getApiKey());
    }

    public function testGetConfigWithNoConfigReturnsNull(): void
    {
        // 清空数据库中的配置
        $entityManager = self::getEntityManager();
        $entityManager->createQuery('DELETE FROM ' . DigitalOceanConfig::class)->execute();

        // 执行
        $result = $this->service->getConfig();

        // 断言
        $this->assertNull($result);
    }

    public function testGetConfigReturnsLatestConfig(): void
    {
        // 创建多个配置，测试返回最新的
        $config1 = new DigitalOceanConfig();
        $config1->setApiKey('test-api-key-1');
        $config2 = new DigitalOceanConfig();
        $config2->setApiKey('test-api-key-2');

        $entityManager = self::getEntityManager();
        $entityManager->persist($config1);
        $entityManager->flush();

        // 稍微延迟以确保第二个配置的创建时间更晚
        usleep(1000);

        $entityManager->persist($config2);
        $entityManager->flush();

        // 执行
        $result = $this->service->getConfig();

        // 断言 - 应该返回最新的配置
        $this->assertInstanceOf(DigitalOceanConfig::class, $result);
        $this->assertEquals('test-api-key-2', $result->getApiKey());
    }

    public function testSaveConfigCreatesNewConfigWhenNoneExists(): void
    {
        // 清空数据库中的配置
        $entityManager = self::getEntityManager();
        $entityManager->createQuery('DELETE FROM ' . DigitalOceanConfig::class)->execute();

        // 执行
        $result = $this->service->saveConfig('new-api-key', 'test remark');

        // 断言
        $this->assertInstanceOf(DigitalOceanConfig::class, $result);
        $this->assertEquals('new-api-key', $result->getApiKey());
        $this->assertEquals('test remark', $result->getRemark());
        $this->assertNotNull($result->getId()); // 确保已保存到数据库
    }

    public function testSaveConfigUpdatesExistingConfig(): void
    {
        // 创建现有配置并保存到数据库
        $existingConfig = new DigitalOceanConfig();
        $existingConfig->setApiKey('old-api-key');
        $existingConfig->setRemark('old remark');

        $entityManager = self::getEntityManager();
        $entityManager->persist($existingConfig);
        $entityManager->flush();

        $existingId = $existingConfig->getId();

        // 执行
        $result = $this->service->saveConfig('updated-api-key', 'updated remark');

        // 断言
        $this->assertInstanceOf(DigitalOceanConfig::class, $result);
        $this->assertEquals($existingId, $result->getId()); // 应该是同一个配置
        $this->assertEquals('updated-api-key', $result->getApiKey());
        $this->assertEquals('updated remark', $result->getRemark());
    }

    public function testSaveConfigWithoutRemarkKeepsExistingRemark(): void
    {
        // 创建现有配置并保存到数据库
        $existingConfig = new DigitalOceanConfig();
        $existingConfig->setApiKey('old-api-key');
        $existingConfig->setRemark('existing remark');

        $entityManager = self::getEntityManager();
        $entityManager->persist($existingConfig);
        $entityManager->flush();

        // 执行 - 不传入 remark 参数
        $result = $this->service->saveConfig('updated-api-key');

        // 断言
        $this->assertEquals('updated-api-key', $result->getApiKey());
        $this->assertEquals('existing remark', $result->getRemark()); // 保持原有的 remark
    }

    public function testSaveConfigWithNullRemarkKeepsExistingRemark(): void
    {
        // 创建现有配置并保存到数据库
        $existingConfig = new DigitalOceanConfig();
        $existingConfig->setApiKey('old-api-key');
        $existingConfig->setRemark('existing remark');

        $entityManager = self::getEntityManager();
        $entityManager->persist($existingConfig);
        $entityManager->flush();

        // 执行 - 显式传入 null remark
        $result = $this->service->saveConfig('updated-api-key', null);

        // 断言
        $this->assertEquals('updated-api-key', $result->getApiKey());
        $this->assertEquals('existing remark', $result->getRemark()); // 保持原有的 remark
    }
}
