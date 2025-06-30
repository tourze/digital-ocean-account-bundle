<?php

namespace DigitalOceanAccountBundle\Tests\Integration\Service;

use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Exception\DigitalOceanException;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use DigitalOceanAccountBundle\Service\AbstractDigitalOceanService;
use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * 用于测试的具体服务类
 */
class ConcreteDigitalOceanService extends AbstractDigitalOceanService
{
    public function testPrepareRequest(DigitalOceanRequest $request): DigitalOceanRequest
    {
        return $this->prepareRequest($request);
    }
}

class AbstractDigitalOceanServiceTest extends TestCase
{
    private DigitalOceanClient $client;
    private DigitalOceanConfigService $configService;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private ConcreteDigitalOceanService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(DigitalOceanClient::class);
        $this->configService = $this->createMock(DigitalOceanConfigService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        // 创建一个具体的服务类来测试抽象类
        $this->service = new ConcreteDigitalOceanService(
            $this->client,
            $this->configService,
            $this->entityManager,
            $this->logger
        );
    }

    public function test_prepare_request_with_valid_config(): void
    {
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-api-key');

        // 创建请求
        $request = $this->createMock(DigitalOceanRequest::class);

        // 配置模拟对象
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);

        $request->expects($this->once())
            ->method('setApiKey')
            ->with('test-api-key');

        // 执行
        $result = $this->service->testPrepareRequest($request);

        // 断言
        $this->assertSame($request, $result);
    }

    public function test_prepare_request_without_config_throws_exception(): void
    {
        // 创建请求
        $request = $this->createMock(DigitalOceanRequest::class);

        // 配置模拟对象返回null
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn(null);

        // 断言抛出异常
        $this->expectException(DigitalOceanException::class);
        $this->expectExceptionMessage('未配置 DigitalOcean API Key');

        // 执行
        $this->service->testPrepareRequest($request);
    }

    public function test_constructor_sets_properties_correctly(): void
    {
        // 使用反射检查构造函数是否正确设置了属性
        $reflection = new \ReflectionClass($this->service);
        
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $this->assertSame($this->client, $clientProperty->getValue($this->service));

        $configServiceProperty = $reflection->getProperty('configService');
        $configServiceProperty->setAccessible(true);
        $this->assertSame($this->configService, $configServiceProperty->getValue($this->service));

        $entityManagerProperty = $reflection->getProperty('entityManager');
        $entityManagerProperty->setAccessible(true);
        $this->assertSame($this->entityManager, $entityManagerProperty->getValue($this->service));

        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $this->assertSame($this->logger, $loggerProperty->getValue($this->service));
    }

    public function test_abstract_service_structure(): void
    {
        $reflection = new \ReflectionClass(AbstractDigitalOceanService::class);
        
        // 验证是抽象类
        $this->assertTrue($reflection->isAbstract());
        
        // 验证构造函数参数
        $constructor = $reflection->getConstructor();
        $this->assertNotNull($constructor);
        
        $parameters = $constructor->getParameters();
        $this->assertCount(4, $parameters);
        
        // 验证参数类型
        $this->assertNotNull($parameters[0]->getType());
        $this->assertEquals(DigitalOceanClient::class, (string)$parameters[0]->getType());
        $this->assertNotNull($parameters[1]->getType());
        $this->assertEquals(DigitalOceanConfigService::class, (string)$parameters[1]->getType());
        $this->assertNotNull($parameters[2]->getType());
        $this->assertEquals(EntityManagerInterface::class, (string)$parameters[2]->getType());
        $this->assertNotNull($parameters[3]->getType());
        $this->assertEquals(LoggerInterface::class, (string)$parameters[3]->getType());
        
        // 验证所有参数都是只读的
        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue($property->isReadOnly());
        }
    }

    public function test_prepare_request_method_structure(): void
    {
        $reflection = new \ReflectionClass(AbstractDigitalOceanService::class);
        
        // 验证prepareRequest方法存在
        $this->assertTrue($reflection->hasMethod('prepareRequest'));
        
        $method = $reflection->getMethod('prepareRequest');
        
        // 验证方法是protected
        $this->assertTrue($method->isProtected());
        
        // 验证方法参数
        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertNotNull($parameters[0]->getType());
        $this->assertEquals(DigitalOceanRequest::class, (string)$parameters[0]->getType());
        
        // 验证返回类型
        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertEquals(DigitalOceanRequest::class, (string)$returnType);
    }

    public function test_inheritance_from_abstract_service(): void
    {
        // 创建一个测试用的具体服务类
        $concreteService = new class(
            $this->client,
            $this->configService,
            $this->entityManager,
            $this->logger
        ) extends AbstractDigitalOceanService {
            public function doSomething(): string
            {
                return 'test';
            }
        };
        
        // 验证继承关系
        $this->assertInstanceOf(AbstractDigitalOceanService::class, $concreteService);
        
        // 验证可以调用自己的方法
        $this->assertEquals('test', $concreteService->doSomething());
    }
}