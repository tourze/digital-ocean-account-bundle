<?php

namespace DigitalOceanAccountBundle\Service;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Repository\DigitalOceanConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Tourze\Symfony\AopDoctrineBundle\Attribute\Transactional;

class DigitalOceanConfigService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DigitalOceanConfigRepository $repository,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 获取配置
     */
    public function getConfig(): ?DigitalOceanConfig
    {
        return $this->repository->findOneBy([], ['id' => 'DESC']);
    }

    /**
     * 保存配置
     */
    #[Transactional]
    public function saveConfig(string $apiKey, ?string $remark = null): DigitalOceanConfig
    {
        $config = $this->getConfig();

        if ($config === null) {
            $config = new DigitalOceanConfig();
        }

        $config->setApiKey($apiKey);

        if ($remark !== null) {
            $config->setRemark($remark);
        }

        $this->entityManager->persist($config);
        $this->entityManager->flush();

        $this->logger->info('DigitalOcean配置已更新', ['id' => $config->getId()]);

        return $config;
    }
}
