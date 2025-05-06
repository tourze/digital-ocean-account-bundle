<?php

namespace DigitalOceanAccountBundle\Tests\Repository;

use DigitalOceanAccountBundle\Repository\DigitalOceanConfigRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class DigitalOceanConfigRepositoryTest extends TestCase
{
    public function testConstruction_createsRepository(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new DigitalOceanConfigRepository($registry);

        $this->assertInstanceOf(DigitalOceanConfigRepository::class, $repository);
    }
}
