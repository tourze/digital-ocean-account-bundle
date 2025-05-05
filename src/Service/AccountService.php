<?php

namespace DigitalOceanAccountBundle\Service;

use DigitalOceanAccountBundle\Client\DigitalOceanClient;
use DigitalOceanAccountBundle\Entity\Account;
use DigitalOceanAccountBundle\Repository\AccountRepository;
use DigitalOceanAccountBundle\Request\Account\GetAccountRequest;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Tourze\Symfony\AopDoctrineBundle\Attribute\Transactional;

class AccountService extends AbstractDigitalOceanService
{
    public function __construct(
        DigitalOceanClient $client,
        DigitalOceanConfigService $configService,
        EntityManagerInterface $entityManager,
        private readonly AccountRepository $accountRepository,
        LoggerInterface $logger,
    ) {
        parent::__construct($client, $configService, $entityManager, $logger);
    }

    /**
     * 获取账号信息
     */
    public function getAccount(): array
    {
        $request = new GetAccountRequest();
        $this->prepareRequest($request);

        $response = $this->client->request($request);

        return $response['account'] ?? [];
    }

    /**
     * 同步账号信息到数据库
     */
    #[Transactional]
    public function syncAccount(): Account
    {
        $accountData = $this->getAccount();

        if (empty($accountData)) {
            throw new \RuntimeException('获取账号信息失败');
        }

        // 查找现有账号或创建新账号
        $account = $this->accountRepository->findOneBy([]) ?? new Account();

        // 更新账号信息
        $account->setEmail($accountData['email'] ?? '')
            ->setUuid($accountData['uuid'] ?? '')
            ->setStatus($accountData['status'] ?? '')
            ->setEmailVerified($accountData['email_verified'] ?? false);

        if (isset($accountData['team'])) {
            $account->setTeamName($accountData['team']['name'] ?? null);
        }

        // 更新账号限制信息
        if (isset($accountData['droplet_limit'])) {
            $account->setDropletLimit((string)$accountData['droplet_limit']);
        }

        if (isset($accountData['floating_ip_limit'])) {
            $account->setFloatingIpLimit((string)$accountData['floating_ip_limit']);
        }

        if (isset($accountData['reserved_ip_limit'])) {
            $account->setReservedIpLimit((string)$accountData['reserved_ip_limit']);
        }

        if (isset($accountData['volume_limit'])) {
            $account->setVolumeLimit((string)$accountData['volume_limit']);
        }

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        $this->logger->info('DigitalOcean账号信息已同步', ['id' => $account->getId()]);

        return $account;
    }
}
