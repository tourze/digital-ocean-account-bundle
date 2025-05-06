<?php

namespace DigitalOceanAccountBundle\Tests\Service;

use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Entity\Account;
use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Repository\AccountRepository;
use DigitalOceanAccountBundle\Request\Account\GetAccountRequest;
use DigitalOceanAccountBundle\Service\AccountService;
use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AccountServiceTest extends TestCase
{
    private DigitalOceanClient $client;
    private DigitalOceanConfigService $configService;
    private EntityManagerInterface $entityManager;
    private AccountRepository $accountRepository;
    private LoggerInterface $logger;
    private AccountService $service;
    
    protected function setUp(): void
    {
        $this->client = $this->createMock(DigitalOceanClient::class);
        $this->configService = $this->createMock(DigitalOceanConfigService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        
        $this->service = new AccountService(
            $this->client,
            $this->configService,
            $this->entityManager,
            $this->accountRepository,
            $this->logger
        );
    }
    
    public function testGetAccount_withValidResponse_returnsAccountData(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'test@example.com',
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'status' => 'active',
                'email_verified' => true
            ]
        ];
        
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');
        
        // 配置模拟对象
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);
        
        $this->client->expects($this->once())
            ->method('request')
            ->with($this->isInstanceOf(GetAccountRequest::class))
            ->willReturn($apiResponse);
        
        // 执行
        $result = $this->service->getAccount();
        
        // 断言
        $this->assertEquals($apiResponse['account'], $result);
    }
    
    public function testGetAccount_withEmptyResponse_returnsEmptyArray(): void
    {
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');
        
        // 配置模拟对象
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);
        
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn([]);
        
        // 执行
        $result = $this->service->getAccount();
        
        // 断言
        $this->assertEquals([], $result);
    }
    
    public function testSyncAccount_withEmptyApiResponse_throwsException(): void
    {
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');
        
        // 配置模拟对象
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);
        
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn([]);
        
        // 断言
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('获取账号信息失败');
        
        // 执行
        $this->service->syncAccount();
    }
    
    public function testSyncAccount_withValidData_createsNewAccount(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'test@example.com',
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'status' => 'active',
                'email_verified' => true,
                'team' => [
                    'name' => 'Test Team'
                ],
                'droplet_limit' => 10,
                'floating_ip_limit' => 5,
                'reserved_ip_limit' => 3,
                'volume_limit' => 20
            ]
        ];
        
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');
        
        // 配置模拟对象
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);
        
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($apiResponse);
        
        // 模拟仓库返回空，表示没有现有账号
        $this->accountRepository->expects($this->once())
            ->method('findOneBy')
            ->with([])
            ->willReturn(null);
        
        // 模拟持久化方法调用
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($account) {
                return $account instanceof Account
                    && $account->getEmail() === 'test@example.com'
                    && $account->getUuid() === '12345678-1234-1234-1234-123456789012'
                    && $account->getStatus() === 'active'
                    && $account->getEmailVerified() === true
                    && $account->getTeamName() === 'Test Team'
                    && $account->getDropletLimit() === '10'
                    && $account->getFloatingIpLimit() === '5'
                    && $account->getReservedIpLimit() === '3'
                    && $account->getVolumeLimit() === '20';
            }));
        
        $this->entityManager->expects($this->once())
            ->method('flush');
        
        // 执行
        $account = $this->service->syncAccount();
        
        // 断言
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('test@example.com', $account->getEmail());
        $this->assertEquals('12345678-1234-1234-1234-123456789012', $account->getUuid());
        $this->assertEquals('active', $account->getStatus());
        $this->assertTrue($account->getEmailVerified());
        $this->assertEquals('Test Team', $account->getTeamName());
        $this->assertEquals('10', $account->getDropletLimit());
        $this->assertEquals('5', $account->getFloatingIpLimit());
        $this->assertEquals('3', $account->getReservedIpLimit());
        $this->assertEquals('20', $account->getVolumeLimit());
    }
    
    public function testSyncAccount_withValidData_updatesExistingAccount(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'new@example.com',
                'uuid' => '87654321-4321-4321-4321-210987654321',
                'status' => 'active',
                'email_verified' => true
            ]
        ];
        
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');
        
        // 创建已存在的账号
        $existingAccount = new Account();
        $existingAccount->setEmail('old@example.com')
            ->setUuid('12345678-1234-1234-1234-123456789012')
            ->setStatus('inactive')
            ->setEmailVerified(false);
        
        // 配置模拟对象
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);
        
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($apiResponse);
        
        // 模拟仓库返回现有账号
        $this->accountRepository->expects($this->once())
            ->method('findOneBy')
            ->with([])
            ->willReturn($existingAccount);
        
        // 模拟持久化方法调用
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($account) {
                return $account instanceof Account
                    && $account->getEmail() === 'new@example.com'
                    && $account->getUuid() === '87654321-4321-4321-4321-210987654321'
                    && $account->getStatus() === 'active'
                    && $account->getEmailVerified() === true;
            }));
        
        $this->entityManager->expects($this->once())
            ->method('flush');
        
        // 执行
        $account = $this->service->syncAccount();
        
        // 断言
        $this->assertSame($existingAccount, $account);
        $this->assertEquals('new@example.com', $account->getEmail());
        $this->assertEquals('87654321-4321-4321-4321-210987654321', $account->getUuid());
        $this->assertEquals('active', $account->getStatus());
        $this->assertTrue($account->getEmailVerified());
    }
    
    public function testSyncAccount_withoutTeamData_keepsPreviousTeamName(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'test@example.com',
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'status' => 'active',
                'email_verified' => true
            ]
        ];
        
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');
        
        // 创建已存在的账号，设置团队名称
        $existingAccount = new Account();
        $existingAccount->setEmail('old@example.com')
            ->setTeamName('Old Team');
        
        // 配置模拟对象
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);
        
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($apiResponse);
        
        // 模拟仓库返回现有账号
        $this->accountRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingAccount);
        
        // 执行
        $account = $this->service->syncAccount();
        
        // 断言
        $this->assertEquals('Old Team', $account->getTeamName());
    }
    
    public function testSyncAccount_withoutLimitsData_maintainsExistingLimits(): void
    {
        $apiResponse = [
            'account' => [
                'email' => 'test@example.com',
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'status' => 'active',
                'email_verified' => true
            ]
        ];
        
        // 创建配置
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-token');
        
        // 创建已存在的账号，设置限制值
        $existingAccount = new Account();
        $existingAccount->setEmail('old@example.com')
            ->setDropletLimit('10')
            ->setFloatingIpLimit('5')
            ->setReservedIpLimit('3')
            ->setVolumeLimit('20');
        
        // 配置模拟对象
        $this->configService->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);
        
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($apiResponse);
        
        // 模拟仓库返回现有账号
        $this->accountRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingAccount);
        
        // 执行
        $account = $this->service->syncAccount();
        
        // 断言限制值保持不变
        $this->assertEquals('10', $account->getDropletLimit());
        $this->assertEquals('5', $account->getFloatingIpLimit());
        $this->assertEquals('3', $account->getReservedIpLimit());
        $this->assertEquals('20', $account->getVolumeLimit());
    }
} 