<?php

namespace DigitalOceanAccountBundle\Tests\Service;

use DigitalOceanAccountBundle\Abstract\AbstractDigitalOceanService;
use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Exception\DigitalOceanException;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;
use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;
use DigitalOceanAccountBundle\Tests\Helper\TestConcreteDigitalOceanService;
use DigitalOceanAccountBundle\Tests\Helper\TestDigitalOceanRequest;
use DigitalOceanAccountBundle\Tests\Helper\TestDigitalOceanRequestWithApiKey;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(AbstractDigitalOceanService::class)]
#[RunTestsInSeparateProcesses]
final class AbstractDigitalOceanServiceTest extends AbstractIntegrationTestCase
{
    private DigitalOceanClient $client;

    private DigitalOceanConfigService $configService;

    private LoggerInterface $logger;

    private TestConcreteDigitalOceanService $service;

    protected function onSetUp(): void
    {
        $this->setUpTestServices();
    }

    private function setUpTestServices(): void
    {
        // 获取真实的服务，因为TestConcreteDigitalOceanService需要正确的类型
        $this->client = self::getService(DigitalOceanClient::class);
        $this->configService = self::getService(DigitalOceanConfigService::class);
        $this->logger = new NullLogger();

        // 创建一个具体的服务类来测试抽象类
        $entityManager = self::getEntityManager();
        $this->service = new TestConcreteDigitalOceanService(
            $this->client,
            $this->configService,
            $entityManager,
            $this->logger
        );
    }

    public function testPrepareRequestWithValidConfig(): void
    {
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-api-key');

        // 使用测试辅助类替代 DigitalOceanRequest Mock
        $request = new TestDigitalOceanRequestWithApiKey();

        // 将配置保存到数据库以供服务使用
        $entityManager = self::getEntityManager();
        $entityManager->persist($config);
        $entityManager->flush();

        // 执行
        $result = $this->service->testPrepareRequest($request);

        // 断言
        $this->assertSame($request, $result);
        $this->assertTrue($request->wasSetApiKeyCalled());
        $this->assertEquals('test-api-key', $request->getReceivedApiKey());
    }

    public function testPrepareRequestWithoutConfigThrowsException(): void
    {
        // 使用测试辅助类替代 DigitalOceanRequest Mock
        $request = new TestDigitalOceanRequest();

        // 清空数据库中的配置以模拟未配置情况
        $entityManager = self::getEntityManager();
        $entityManager->createQuery('DELETE FROM DigitalOceanAccountBundle\Entity\DigitalOceanConfig')->execute();

        // 断言抛出异常
        $this->expectException(DigitalOceanException::class);
        $this->expectExceptionMessage('未配置 DigitalOcean API Key');

        // 执行
        $this->service->testPrepareRequest($request);
    }

    public function testConstructorSetsPropertiesCorrectly(): void
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
        $entityManager = self::getEntityManager();
        $this->assertSame($entityManager, $entityManagerProperty->getValue($this->service));

        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $this->assertSame($this->logger, $loggerProperty->getValue($this->service));
    }

    public function testAbstractServiceStructure(): void
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
        $this->assertEquals(DigitalOceanClient::class, (string) $parameters[0]->getType());
        $this->assertNotNull($parameters[1]->getType());
        $this->assertEquals(DigitalOceanConfigService::class, (string) $parameters[1]->getType());
        $this->assertNotNull($parameters[2]->getType());
        $this->assertEquals(EntityManagerInterface::class, (string) $parameters[2]->getType());
        $this->assertNotNull($parameters[3]->getType());
        $this->assertEquals(LoggerInterface::class, (string) $parameters[3]->getType());

        // 验证所有参数都是只读的
        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue($property->isReadOnly());
        }
    }

    public function testPrepareRequestMethodStructure(): void
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
        $this->assertEquals(DigitalOceanRequest::class, (string) $parameters[0]->getType());

        // 验证返回类型
        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertEquals(DigitalOceanRequest::class, (string) $returnType);
    }

    public function testInheritanceFromAbstractService(): void
    {
        // 创建一个测试用的具体服务类
        $entityManager = self::getEntityManager();
        $concreteService = new class($this->client, $this->configService, $entityManager, $this->logger) extends AbstractDigitalOceanService {
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
