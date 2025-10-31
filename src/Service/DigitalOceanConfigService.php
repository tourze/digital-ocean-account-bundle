<?php

namespace DigitalOceanAccountBundle\Service;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Repository\DigitalOceanConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\Symfony\AopDoctrineBundle\Attribute\Transactional;

#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'digital_ocean_account')]
readonly class DigitalOceanConfigService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DigitalOceanConfigRepository $repository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * 获取配置
     */
    public function getConfig(): ?DigitalOceanConfig
    {
        $config = $this->repository->findOneBy([], ['id' => 'DESC']);
        assert($config instanceof DigitalOceanConfig || null === $config);

        return $config;
    }

    /**
     * 保存配置
     */
    #[Transactional]
    public function saveConfig(string $apiKey, ?string $remark = null): DigitalOceanConfig
    {
        $config = $this->getConfig();

        if (null === $config) {
            $config = new DigitalOceanConfig();
        }

        $config->setApiKey($apiKey);

        if (null !== $remark) {
            $config->setRemark($remark);
        }

        $this->entityManager->persist($config);
        $this->entityManager->flush();

        $this->logger->info('DigitalOcean配置已更新', ['id' => $config->getId()]);

        return $config;
    }
}
