<?php

namespace DigitalOceanAccountBundle\Tests\Repository;

use DigitalOceanAccountBundle\Entity\Account;
use DigitalOceanAccountBundle\Repository\AccountRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(AccountRepository::class)]
#[RunTestsInSeparateProcesses]
final class AccountRepositoryTest extends AbstractRepositoryTestCase
{
    private AccountRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(AccountRepository::class);
    }

    public function testRepositoryExistsInContainer(): void
    {
        $this->assertInstanceOf(AccountRepository::class, $this->repository);
    }

    // find方法测试

    public function testCountWithNullCriteriaShouldCountNullFields(): void
    {
        $accountWithTeam = $this->createTestAccount('team@example.com');
        $accountWithTeam->setTeamName('Test Team');

        $accountWithoutTeam1 = $this->createTestAccount('noteam1@example.com');
        $accountWithoutTeam1->setTeamName(null);

        $accountWithoutTeam2 = $this->createTestAccount('noteam2@example.com');
        $accountWithoutTeam2->setTeamName(null);

        $this->repository->save($accountWithTeam);
        $this->repository->save($accountWithoutTeam1);
        $this->repository->save($accountWithoutTeam2);

        $count = $this->repository->count(['teamName' => null]);

        $this->assertSame(2, $count);
    }

    public function testCountWithNullDropletLimitShouldCountNullFields(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setDropletLimit('10');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setDropletLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $count = $this->repository->count(['dropletLimit' => null]);

        $this->assertSame(1, $count);
    }

    public function testCountWithNullFloatingIpLimitShouldCountNullFields(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setFloatingIpLimit('5');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setFloatingIpLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $count = $this->repository->count(['floatingIpLimit' => null]);

        $this->assertSame(1, $count);
    }

    public function testCountWithNullReservedIpLimitShouldCountNullFields(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setReservedIpLimit('3');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setReservedIpLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $count = $this->repository->count(['reservedIpLimit' => null]);

        $this->assertSame(1, $count);
    }

    public function testCountWithNullVolumeLimitShouldCountNullFields(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setVolumeLimit('100GB');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setVolumeLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $count = $this->repository->count(['volumeLimit' => null]);

        $this->assertSame(1, $count);
    }

    // findBy方法测试

    public function testFindByWithNullFieldValuesShouldFindNullRecords(): void
    {
        $accountWithTeam = $this->createTestAccount('team@example.com');
        $accountWithTeam->setTeamName('Test Team');

        $accountWithoutTeam1 = $this->createTestAccount('noteam1@example.com');
        $accountWithoutTeam1->setTeamName(null);

        $accountWithoutTeam2 = $this->createTestAccount('noteam2@example.com');
        $accountWithoutTeam2->setTeamName(null);

        $this->repository->save($accountWithTeam);
        $this->repository->save($accountWithoutTeam1);
        $this->repository->save($accountWithoutTeam2);

        $results = $this->repository->findBy(['teamName' => null]);

        $this->assertCount(2, $results);
        foreach ($results as $account) {
            $this->assertInstanceOf(Account::class, $account);
            $this->assertNull($account->getTeamName());
        }
    }

    public function testFindByWithNullDropletLimitShouldFindNullRecords(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setDropletLimit('10');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setDropletLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $results = $this->repository->findBy(['dropletLimit' => null]);

        $this->assertCount(1, $results);
        $this->assertInstanceOf(Account::class, $results[0]);
        $this->assertNull($results[0]->getDropletLimit());
    }

    public function testFindByWithNullFloatingIpLimitShouldFindNullRecords(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setFloatingIpLimit('5');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setFloatingIpLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $results = $this->repository->findBy(['floatingIpLimit' => null]);

        $this->assertCount(1, $results);
        $this->assertInstanceOf(Account::class, $results[0]);
        $this->assertNull($results[0]->getFloatingIpLimit());
    }

    public function testFindByWithNullReservedIpLimitShouldFindNullRecords(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setReservedIpLimit('3');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setReservedIpLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $results = $this->repository->findBy(['reservedIpLimit' => null]);

        $this->assertCount(1, $results);
        $this->assertInstanceOf(Account::class, $results[0]);
        $this->assertNull($results[0]->getReservedIpLimit());
    }

    public function testFindByWithNullVolumeLimitShouldFindNullRecords(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setVolumeLimit('100GB');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setVolumeLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $results = $this->repository->findBy(['volumeLimit' => null]);

        $this->assertCount(1, $results);
        $this->assertInstanceOf(Account::class, $results[0]);
        $this->assertNull($results[0]->getVolumeLimit());
    }

    // findOneBy方法测试

    public function testFindOneByWithNullCriteriaValueShouldFindNullFields(): void
    {
        $accountWithTeam = $this->createTestAccount('team@example.com');
        $accountWithTeam->setTeamName('Test Team');

        $accountWithoutTeam = $this->createTestAccount('noteam@example.com');
        $accountWithoutTeam->setTeamName(null);

        $this->repository->save($accountWithTeam);
        $this->repository->save($accountWithoutTeam);

        $result = $this->repository->findOneBy(['teamName' => null]);

        $this->assertInstanceOf(Account::class, $result);
        $this->assertNull($result->getTeamName());
        $this->assertSame('noteam@example.com', $result->getEmail());
    }

    public function testFindOneByWithNullDropletLimitShouldFindNullFields(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setDropletLimit('10');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setDropletLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $result = $this->repository->findOneBy(['dropletLimit' => null]);

        $this->assertInstanceOf(Account::class, $result);
        $this->assertNull($result->getDropletLimit());
        $this->assertSame('nolimit@example.com', $result->getEmail());
    }

    public function testFindOneByWithNullFloatingIpLimitShouldFindNullFields(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setFloatingIpLimit('5');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setFloatingIpLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $result = $this->repository->findOneBy(['floatingIpLimit' => null]);

        $this->assertInstanceOf(Account::class, $result);
        $this->assertNull($result->getFloatingIpLimit());
        $this->assertSame('nolimit@example.com', $result->getEmail());
    }

    public function testFindOneByWithNullReservedIpLimitShouldFindNullFields(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setReservedIpLimit('3');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setReservedIpLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $result = $this->repository->findOneBy(['reservedIpLimit' => null]);

        $this->assertInstanceOf(Account::class, $result);
        $this->assertNull($result->getReservedIpLimit());
        $this->assertSame('nolimit@example.com', $result->getEmail());
    }

    public function testFindOneByWithNullVolumeLimitShouldFindNullFields(): void
    {
        $accountWithLimit = $this->createTestAccount('limit@example.com');
        $accountWithLimit->setVolumeLimit('100GB');

        $accountWithoutLimit = $this->createTestAccount('nolimit@example.com');
        $accountWithoutLimit->setVolumeLimit(null);

        $this->repository->save($accountWithLimit);
        $this->repository->save($accountWithoutLimit);

        $result = $this->repository->findOneBy(['volumeLimit' => null]);

        $this->assertInstanceOf(Account::class, $result);
        $this->assertNull($result->getVolumeLimit());
        $this->assertSame('nolimit@example.com', $result->getEmail());
    }

    public function testFindOneByWithOrderByShouldRespectOrdering(): void
    {
        $account1 = $this->createTestAccount('b@example.com', true, 'active');
        $account2 = $this->createTestAccount('a@example.com', true, 'active');
        $this->repository->save($account1);
        $this->repository->save($account2);

        $result = $this->repository->findOneBy(['status' => 'active'], ['email' => 'ASC']);

        $this->assertInstanceOf(Account::class, $result);
        $this->assertSame('a@example.com', $result->getEmail());
    }

    // findAll方法测试

    public function testFindAllShouldReturnRepositoryType(): void
    {
        $results = $this->repository->findAll();

        $this->assertIsArray($results);
        foreach ($results as $account) {
            $this->assertInstanceOf(Account::class, $account);
        }
    }

    // save方法测试
    public function testSaveWithNewEntityShouldPersistToDatabase(): void
    {
        $account = $this->createTestAccount('new@example.com');

        $this->repository->save($account);

        $this->assertNotNull($account->getId());
        $foundAccount = $this->repository->find($account->getId());
        $this->assertInstanceOf(Account::class, $foundAccount);
        $this->assertSame('new@example.com', $foundAccount->getEmail());
    }

    public function testSaveWithExistingEntityShouldUpdateDatabase(): void
    {
        $account = $this->createTestAccount('original@example.com');
        $this->repository->save($account);

        $account->setEmail('updated@example.com');
        $this->repository->save($account);

        $foundAccount = $this->repository->find($account->getId());
        $this->assertInstanceOf(Account::class, $foundAccount);
        $this->assertSame('updated@example.com', $foundAccount->getEmail());
    }

    public function testSaveWithFlushFalseShouldNotCommitToDatabase(): void
    {
        $account = $this->createTestAccount('notflushed@example.com');

        $this->repository->save($account, false);

        // 清除实体管理器缓存
        self::getEntityManager()->clear();

        $foundAccount = $this->repository->findOneBy(['email' => 'notflushed@example.com']);
        $this->assertNull($foundAccount);
    }

    // remove方法测试
    public function testRemoveWithExistingEntityShouldDeleteFromDatabase(): void
    {
        $account = $this->createTestAccount('todelete@example.com');
        $this->repository->save($account);
        $accountId = $account->getId();

        $this->repository->remove($account);

        $foundAccount = $this->repository->find($accountId);
        $this->assertNull($foundAccount);
    }

    public function testRemoveWithFlushFalseShouldNotCommitToDatabase(): void
    {
        $account = $this->createTestAccount('notremoved@example.com');
        $this->repository->save($account);
        $accountId = $account->getId();

        $this->repository->remove($account, false);

        // 清除实体管理器缓存
        self::getEntityManager()->clear();

        $foundAccount = $this->repository->find($accountId);
        $this->assertInstanceOf(Account::class, $foundAccount);
    }

    // 数据库不可用时的健壮性测试

    // 辅助方法
    private function createTestAccount(
        string $email = 'test@example.com',
        bool $emailVerified = true,
        string $status = 'active',
    ): Account {
        $account = new Account();
        $account->setEmail($email);
        $account->setUuid(uniqid('uuid_', true));
        $account->setStatus($status);
        $account->setEmailVerified($emailVerified);

        return $account;
    }

    protected function createNewEntity(): object
    {
        return $this->createTestAccount();
    }

    protected function getRepository(): AccountRepository
    {
        return $this->repository;
    }
}
