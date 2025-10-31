<?php

namespace DigitalOceanAccountBundle\Tests\Service;

use DigitalOceanAccountBundle\Entity\Account;
use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Service\AccountService;
use DigitalOceanAccountBundle\Tests\Helper\TestAccountRepository;
use DigitalOceanAccountBundle\Tests\Helper\TestAccountService;
use DigitalOceanAccountBundle\Tests\Helper\TestDigitalOceanClient;
use DigitalOceanAccountBundle\Tests\Helper\TestDigitalOceanConfigService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Psr\Log\NullLogger;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(AccountService::class)]
#[RunTestsInSeparateProcesses]
final class AccountServiceTest extends AbstractIntegrationTestCase
{
    private TestDigitalOceanClient $client;

    private TestDigitalOceanConfigService $configService;

    private TestAccountRepository $accountRepository;

    // LoggerInterface 不再需要 mock，因为使用容器中的真实服务

    private TestAccountService $service;

    protected function onSetUp(): void
    {
        $this->setUpTestServices();
    }

    private function setUpTestServices(): void
    {
        // 使用测试辅助类替代复杂匿名类
        $this->client = new TestDigitalOceanClient();

        $this->configService = new TestDigitalOceanConfigService();

        $this->accountRepository = new TestAccountRepository();

        // 直接创建服务实例，避免容器初始化问题
        $entityManager = self::getEntityManager();
        $this->service = new TestAccountService(
            $this->client,
            $this->configService,
            $entityManager,
            $this->accountRepository,
            new NullLogger()
        );
    }

    public function testGetAccountWithValidResponseReturnsAccountData(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'test@example.com',
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'status' => 'active',
                'email_verified' => true,
            ],
        ];

        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');

        // 配置匿名类的行为
        $this->configService = $this->configService->createWithConfig($config);
        $this->client->setRequestResult($apiResponse);

        // 重新创建服务实例以使用新的配置
        $entityManager = self::getEntityManager();
        $this->service = new TestAccountService(
            $this->client,
            $this->configService,
            $entityManager,
            $this->accountRepository,
            new NullLogger()
        );

        // 执行
        $result = $this->service->getAccount();

        // 断言
        $this->assertEquals($apiResponse['account'], $result);
    }

    public function testGetAccountWithEmptyResponseReturnsEmptyArray(): void
    {
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');

        // 配置匿名类的行为
        $this->configService = $this->configService->createWithConfig($config);
        $this->client->setRequestResult([]);

        // 重新创建服务实例以使用新的配置
        $entityManager = self::getEntityManager();
        $this->service = new TestAccountService(
            $this->client,
            $this->configService,
            $entityManager,
            $this->accountRepository,
            new NullLogger()
        );

        // 执行
        $result = $this->service->getAccount();

        // 断言
        $this->assertEquals([], $result);
    }

    public function testSyncAccountWithEmptyApiResponseThrowsException(): void
    {
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');

        // 配置匿名类的行为
        $this->configService = $this->configService->createWithConfig($config);
        $this->client->setRequestResult([]);

        // 重新创建服务实例以使用新的配置
        $entityManager = self::getEntityManager();
        $this->service = new TestAccountService(
            $this->client,
            $this->configService,
            $entityManager,
            $this->accountRepository,
            new NullLogger()
        );

        // 断言
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('获取账号信息失败');

        // 执行
        $this->service->syncAccount();
    }

    public function testSyncAccountWithValidDataCreatesNewAccount(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'test@example.com',
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'status' => 'active',
                'email_verified' => true,
                'team' => [
                    'name' => 'Test Team',
                ],
                'droplet_limit' => 10,
                'floating_ip_limit' => 5,
                'reserved_ip_limit' => 3,
                'volume_limit' => 20,
            ],
        ];

        $this->setupMockServices($apiResponse);
        $this->setupRepositoryForNewAccount();
        $this->setupEntityManagerForPersist();
        $this->setupLoggerForSync();

        // 执行
        $account = $this->service->syncAccount();

        // 基本验证
        $this->assertInstanceOf(Account::class, $account);
        $this->verifyAccountCreation($account, $apiResponse['account']);
    }

    /**
     * @param array<string, mixed> $apiResponse
     */
    private function setupMockServices(array $apiResponse): void
    {
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');

        // 配置匿名类的行为
        $this->configService = $this->configService->createWithConfig($config);
        $this->client->setRequestResult($apiResponse);

        // 重新创建服务实例以使用新的配置
        $entityManager = self::getEntityManager();
        $this->service = new TestAccountService(
            $this->client,
            $this->configService,
            $entityManager,
            $this->accountRepository,
            new NullLogger()
        );
    }

    private function setupRepositoryForNewAccount(): void
    {
        // 配置匿名类的行为
        $this->accountRepository->setFindOneByResult(null);
    }

    private function setupEntityManagerForPersist(): void
    {
        // 在测试中，我们不需要设置 EntityManager 的期望，因为使用的是真实的 EntityManager
        // 真实的 EntityManager 会正确处理 persist 和 flush 操作
    }

    private function setupLoggerForSync(): void
    {
        // 由于 Logger 是容器中的真实服务，我们无法 mock 其调用
        // 在集成测试中，我们信任 Logger 服务能正常工作
        // 所以不需要设置 Logger 的期望
    }

    /**
     * @param array<string, mixed> $expectedData
     */
    private function verifyAccountCreation(Account $account, array $expectedData): void
    {
        $this->assertEquals($expectedData['email'], $account->getEmail());
        $this->assertEquals($expectedData['uuid'], $account->getUuid());
        $this->assertEquals($expectedData['status'], $account->getStatus());
        $this->assertTrue($account->getEmailVerified());
        $this->assertIsArray($expectedData['team']);
        $this->assertEquals($expectedData['team']['name'], $account->getTeamName());
        $this->assertIsInt($expectedData['droplet_limit']);
        $this->assertEquals((string) $expectedData['droplet_limit'], $account->getDropletLimit());
        $this->assertIsInt($expectedData['floating_ip_limit']);
        $this->assertEquals((string) $expectedData['floating_ip_limit'], $account->getFloatingIpLimit());
        $this->assertIsInt($expectedData['reserved_ip_limit']);
        $this->assertEquals((string) $expectedData['reserved_ip_limit'], $account->getReservedIpLimit());
        $this->assertIsInt($expectedData['volume_limit']);
        $this->assertEquals((string) $expectedData['volume_limit'], $account->getVolumeLimit());
    }

    public function testSyncAccountWithValidDataUpdatesExistingAccount(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'new@example.com',
                'uuid' => '87654321-4321-4321-4321-210987654321',
                'status' => 'active',
                'email_verified' => true,
            ],
        ];

        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');

        // 创建已存在的账号
        $existingAccount = new Account();
        $existingAccount->setEmail('old@example.com');
        $existingAccount->setUuid('12345678-1234-1234-1234-123456789012');
        $existingAccount->setStatus('inactive');
        $existingAccount->setEmailVerified(false);

        // 配置匿名类的行为
        $configuredConfigService = $this->configService->createWithConfig($config);
        $this->client->setRequestResult($apiResponse);

        // 模拟仓库返回现有账号
        $this->accountRepository->setFindOneByResult($existingAccount);

        // 重新创建服务实例以使用新的配置
        $entityManager = self::getEntityManager();
        $this->service = new TestAccountService(
            $this->client,
            $configuredConfigService,
            $entityManager,
            $this->accountRepository,
            new NullLogger()
        );

        // 在集成测试中，我们使用真实的 EntityManager 和 Logger
        // 真实的 EntityManager 会正确处理 persist 和 flush 操作
        // Logger 会正确记录日志，我们不需要 mock 它

        // 执行
        $account = $this->service->syncAccount();

        // 断言
        $this->assertSame($existingAccount, $account);
        $this->assertEquals('new@example.com', $account->getEmail());
        $this->assertEquals('87654321-4321-4321-4321-210987654321', $account->getUuid());
        $this->assertEquals('active', $account->getStatus());
        $this->assertTrue($account->getEmailVerified());
    }

    public function testSyncAccountWithoutTeamDataKeepsPreviousTeamName(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'test@example.com',
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'status' => 'active',
                'email_verified' => true,
            ],
        ];

        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');

        // 创建已存在的账号，设置团队名称
        $existingAccount = new Account();
        $existingAccount->setEmail('old@example.com');
        $existingAccount->setTeamName('Old Team');

        // 配置匿名类的行为
        $configuredConfigService = $this->configService->createWithConfig($config);
        $this->client->setRequestResult($apiResponse);

        // 模拟仓库返回现有账号
        $this->accountRepository->setFindOneByResult($existingAccount);

        // 重新创建服务实例以使用新的配置
        $entityManager = self::getEntityManager();
        $this->service = new TestAccountService(
            $this->client,
            $configuredConfigService,
            $entityManager,
            $this->accountRepository,
            new NullLogger()
        );

        // 在集成测试中，我们使用真实的 EntityManager 和 Logger
        // 真实的 EntityManager 会正确处理 persist 和 flush 操作
        // Logger 会正确记录日志，我们不需要 mock 它

        // 执行
        $account = $this->service->syncAccount();

        // 断言
        $this->assertEquals('Old Team', $account->getTeamName());
    }

    public function testSyncAccountWithoutLimitsDataMaintainsExistingLimits(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'test@example.com',
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'status' => 'active',
                'email_verified' => true,
            ],
        ];

        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');

        // 创建已存在的账号，设置限制值
        $existingAccount = new Account();
        $existingAccount->setEmail('old@example.com');
        $existingAccount->setDropletLimit('10');
        $existingAccount->setFloatingIpLimit('5');
        $existingAccount->setReservedIpLimit('3');
        $existingAccount->setVolumeLimit('20');

        // 配置匿名类的行为
        $configuredConfigService = $this->configService->createWithConfig($config);
        $this->client->setRequestResult($apiResponse);

        // 模拟仓库返回现有账号
        $this->accountRepository->setFindOneByResult($existingAccount);

        // 重新创建服务实例以使用新的配置
        $entityManager = self::getEntityManager();
        $this->service = new TestAccountService(
            $this->client,
            $configuredConfigService,
            $entityManager,
            $this->accountRepository,
            new NullLogger()
        );

        // 在集成测试中，我们使用真实的 EntityManager 和 Logger
        // 真实的 EntityManager 会正确处理 persist 和 flush 操作
        // Logger 会正确记录日志，我们不需要 mock 它

        // 执行
        $account = $this->service->syncAccount();

        // 断言限制值保持不变
        $this->assertEquals('10', $account->getDropletLimit());
        $this->assertEquals('5', $account->getFloatingIpLimit());
        $this->assertEquals('3', $account->getReservedIpLimit());
        $this->assertEquals('20', $account->getVolumeLimit());
    }
}
