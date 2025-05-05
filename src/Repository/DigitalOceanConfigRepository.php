<?php

namespace DigitalOceanAccountBundle\Repository;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DigitalOceanConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method DigitalOceanConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method DigitalOceanConfig[] findAll()
 * @method DigitalOceanConfig[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DigitalOceanConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DigitalOceanConfig::class);
    }
}
