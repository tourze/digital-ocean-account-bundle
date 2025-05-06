<?php

namespace DigitalOceanAccountBundle\Tests\Repository;

use DigitalOceanAccountBundle\Repository\AccountRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    public function testConstruction_createsRepository(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new AccountRepository($registry);

        $this->assertInstanceOf(AccountRepository::class, $repository);
    }
}
