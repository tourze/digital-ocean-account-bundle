<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use DigitalOceanAccountBundle\Entity\Account;

/**
 * 测试用的Account仓储类
 *
 * @internal
 */
final class TestAccountRepository
{
    private ?Account $findOneByResult = null;

    /**
     * 设置 findOneBy 方法的返回值
     */
    public function setFindOneByResult(?Account $account): void
    {
        $this->findOneByResult = $account;
    }

    /**
     * 模拟 findOneBy 方法
     *
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Account
    {
        return $this->findOneByResult;
    }

    /**
     * 模拟 save 方法
     */
    public function save(Account $entity, bool $flush = true): void
    {
        // 测试用实现 - 无需实际操作
    }

    /**
     * 模拟 remove 方法
     */
    public function remove(Account $entity, bool $flush = true): void
    {
        // 测试用实现 - 无需实际操作
    }

    /**
     * 模拟 find 方法
     */
    public function find(mixed $id, ?int $lockMode = null, ?int $lockVersion = null): ?Account
    {
        return $this->findOneByResult;
    }

    /**
     * 模拟 findAll 方法
     *
     * @return Account[]
     */
    public function findAll(): array
    {
        return null !== $this->findOneByResult ? [$this->findOneByResult] : [];
    }

    /**
     * 模拟 findBy 方法
     *
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     * @return Account[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return null !== $this->findOneByResult ? [$this->findOneByResult] : [];
    }
}
