<?php

namespace DigitalOceanAccountBundle\Tests\Service;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Repository\DigitalOceanConfigRepository;
use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class DigitalOceanConfigServiceTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private DigitalOceanConfigRepository $configRepository;
    private LoggerInterface $logger;
    private DigitalOceanConfigService $service;
    
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->configRepository = $this->createMock(DigitalOceanConfigRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        
        $this->service = new DigitalOceanConfigService(
            $this->entityManager,
            $this->configRepository,
            $this->logger
        );
    }
    
    public function testGetConfig_withExistingConfig_returnsConfig(): void
    {
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-api-key');
        
        // 模拟仓库返回配置
        $this->configRepository->expects($this->once())
            ->method('findOneBy')
            ->with([], ['id' => 'DESC'])
            ->willReturn($config);
        
        // 执行
        $result = $this->service->getConfig();
        
        // 断言
        $this->assertSame($config, $result);
    }
    
    public function testGetConfig_withNoConfig_returnsNull(): void
    {
        // 模拟仓库返回空
        $this->configRepository->expects($this->once())
            ->method('findOneBy')
            ->with([], ['id' => 'DESC'])
            ->willReturn(null);
        
        // 执行
        $result = $this->service->getConfig();
        
        // 断言
        $this->assertNull($result);
    }
    
    public function testSaveConfig_withNewConfig_createsConfig(): void
    {
        $apiKey = 'test-api-key';
        $remark = 'Test remark';
        
        // 模拟仓库返回null，表示没有现有配置
        $this->configRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        
        // 模拟实体管理器persist和flush方法
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function($config) use ($apiKey, $remark) {
                return $config instanceof DigitalOceanConfig
                    && $config->getApiKey() === $apiKey
                    && $config->getRemark() === $remark;
            }));
        
        $this->entityManager->expects($this->once())
            ->method('flush');
        
        // 模拟日志记录
        $this->logger->expects($this->once())
            ->method('info')
            ->with('DigitalOcean配置已更新', $this->anything());
        
        // 执行
        $config = $this->service->saveConfig($apiKey, $remark);
        
        // 断言
        $this->assertInstanceOf(DigitalOceanConfig::class, $config);
        $this->assertEquals($apiKey, $config->getApiKey());
        $this->assertEquals($remark, $config->getRemark());
    }
    
    public function testSaveConfig_withExistingConfig_updatesConfig(): void
    {
        $apiKey = 'new-api-key';
        $remark = 'New remark';
        
        // 创建现有配置
        $existingConfig = new DigitalOceanConfig();
        $existingConfig->setApiKey('old-api-key')
            ->setRemark('Old remark');
        
        // 模拟仓库返回现有配置
        $this->configRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingConfig);
        
        // 模拟实体管理器persist和flush方法
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function($config) use ($apiKey, $remark) {
                return $config instanceof DigitalOceanConfig
                    && $config->getApiKey() === $apiKey
                    && $config->getRemark() === $remark;
            }));
        
        $this->entityManager->expects($this->once())
            ->method('flush');
        
        // 执行
        $config = $this->service->saveConfig($apiKey, $remark);
        
        // 断言
        $this->assertSame($existingConfig, $config);
        $this->assertEquals($apiKey, $config->getApiKey());
        $this->assertEquals($remark, $config->getRemark());
    }
    
    public function testSaveConfig_withoutRemark_preservesExistingRemark(): void
    {
        $apiKey = 'new-api-key';
        $existingRemark = 'Existing remark';
        
        // 创建现有配置
        $existingConfig = new DigitalOceanConfig();
        $existingConfig->setApiKey('old-api-key')
            ->setRemark($existingRemark);
        
        // 模拟仓库返回现有配置
        $this->configRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingConfig);
        
        // 执行
        $config = $this->service->saveConfig($apiKey);
        
        // 断言
        $this->assertEquals($existingRemark, $config->getRemark());
    }
}