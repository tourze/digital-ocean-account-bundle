<?php

namespace DigitalOceanAccountBundle\Repository;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;

/**
 * @extends ServiceEntityRepository<DigitalOceanConfig>
 */
#[AsRepository(entityClass: DigitalOceanConfig::class)]
class DigitalOceanConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DigitalOceanConfig::class);
    }

    public function save(DigitalOceanConfig $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DigitalOceanConfig $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
