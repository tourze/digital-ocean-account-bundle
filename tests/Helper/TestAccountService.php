<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use DigitalOceanAccountBundle\Entity\Account;
use DigitalOceanAccountBundle\Exception\DigitalOceanException;
use DigitalOceanAccountBundle\Request\Account\GetAccountRequest;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * 测试用的Account服务类
 *
 * @internal
 */
final class TestAccountService
{
    public function __construct(
        private readonly TestDigitalOceanClient $client,
        private readonly TestDigitalOceanConfigService $configService,
        private readonly EntityManagerInterface $entityManager,
        private readonly TestAccountRepository $accountRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 获取账号信息
     * @return array<string, mixed>
     */
    public function getAccount(): array
    {
        $request = new GetAccountRequest();
        $this->prepareRequest($request);

        $response = $this->client->request($request);

        // 确保account字段存在且为数组
        $account = $response['account'] ?? [];
        /** @var array<string, mixed> $result */
        $result = is_array($account) ? $account : [];
        return $result;
    }

    /**
     * 同步账号信息到数据库
     */
    public function syncAccount(): Account
    {
        $accountData = $this->getAccount();

        if ([] === $accountData) {
            throw new DigitalOceanException('获取账号信息失败');
        }

        $account = $this->findOrCreateAccount();
        $this->updateAccountBasicInfo($account, $accountData);
        $this->updateAccountLimits($account, $accountData);

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        $this->logger->info('DigitalOcean账号信息已同步', ['id' => $account->getId()]);

        return $account;
    }

    /**
     * 为请求设置API Key
     */
    private function prepareRequest(GetAccountRequest $request): GetAccountRequest
    {
        $config = $this->configService->getConfig();
        if (null === $config) {
            throw new DigitalOceanException('未配置 DigitalOcean API Key');
        }

        $request->setApiKey($config->getApiKey());

        return $request;
    }

    /**
     * 查找现有账号或创建新账号
     */
    private function findOrCreateAccount(): Account
    {
        $account = $this->accountRepository->findOneBy([]);

        return $account ?? new Account();
    }

    /**
     * 更新账号基础信息
     * @param array<string, mixed> $accountData
     */
    private function updateAccountBasicInfo(Account $account, array $accountData): void
    {
        // 更新账号信息 - 确保类型安全
        $email = $accountData['email'] ?? '';
        $uuid = $accountData['uuid'] ?? '';
        $status = $accountData['status'] ?? '';
        $emailVerified = $accountData['email_verified'] ?? false;

        $account->setEmail(is_string($email) ? $email : '');
        $account->setUuid(is_string($uuid) ? $uuid : '');
        $account->setStatus(is_string($status) ? $status : '');
        $account->setEmailVerified(is_bool($emailVerified) ? $emailVerified : false);

        // 处理团队信息
        if (isset($accountData['team']) && is_array($accountData['team'])) {
            $teamName = $accountData['team']['name'] ?? null;
            $account->setTeamName(is_string($teamName) ? $teamName : null);
        }
    }

    /**
     * 更新账号限制信息
     * @param array<string, mixed> $accountData
     */
    private function updateAccountLimits(Account $account, array $accountData): void
    {
        $this->updateLimit($account, $accountData, 'droplet_limit', 'setDropletLimit');
        $this->updateLimit($account, $accountData, 'floating_ip_limit', 'setFloatingIpLimit');
        $this->updateLimit($account, $accountData, 'reserved_ip_limit', 'setReservedIpLimit');
        $this->updateLimit($account, $accountData, 'volume_limit', 'setVolumeLimit');
    }

    /**
     * 更新单个限制值
     * @param array<string, mixed> $accountData
     */
    private function updateLimit(Account $account, array $accountData, string $key, string $method): void
    {
        if (isset($accountData[$key])) {
            $limit = $accountData[$key];
            $value = is_scalar($limit) ? (string) $limit : '';

            // 使用 match 表达式避免动态方法调用
            match ($method) {
                'setDropletLimit' => $account->setDropletLimit($value),
                'setFloatingIpLimit' => $account->setFloatingIpLimit($value),
                'setReservedIpLimit' => $account->setReservedIpLimit($value),
                'setVolumeLimit' => $account->setVolumeLimit($value),
                default => throw new \InvalidArgumentException("Unknown method: {$method}"),
            };
        }
    }
}
