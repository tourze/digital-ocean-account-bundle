<?php

namespace DigitalOceanAccountBundle\Tests\Repository;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use DigitalOceanAccountBundle\Repository\DigitalOceanConfigRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(DigitalOceanConfigRepository::class)]
#[RunTestsInSeparateProcesses]
final class DigitalOceanConfigRepositoryTest extends AbstractRepositoryTestCase
{
    private DigitalOceanConfigRepository $repository;

    protected function createNewEntity(): object
    {
        return $this->createTestConfig();
    }

    protected function onSetUp(): void
    {
        $this->repository = self::getService(DigitalOceanConfigRepository::class);
    }

    public function testRepositoryExistsInContainer(): void
    {
        $this->assertInstanceOf(DigitalOceanConfigRepository::class, $this->repository);
    }

    // find方法测试

    public function testCountWithNullRemarkShouldCountNullFields(): void
    {
        $configWithRemark = $this->createTestConfig('dop_v1_key_with_remark', '有备注的配置');
        $configWithoutRemark1 = $this->createTestConfig('dop_v1_key_no_remark_1');
        $configWithoutRemark1->setRemark(null);
        $configWithoutRemark2 = $this->createTestConfig('dop_v1_key_no_remark_2');
        $configWithoutRemark2->setRemark(null);

        $this->repository->save($configWithRemark);
        $this->repository->save($configWithoutRemark1);
        $this->repository->save($configWithoutRemark2);

        $count = $this->repository->count(['remark' => null]);

        $this->assertSame(2, $count);
    }

    // findBy方法测试

    public function testFindByWithNullRemarkShouldFindNullRecords(): void
    {
        $configWithRemark = $this->createTestConfig('dop_v1_key_with_remark', '有备注的配置');
        $configWithoutRemark1 = $this->createTestConfig('dop_v1_key_no_remark_1');
        $configWithoutRemark1->setRemark(null);
        $configWithoutRemark2 = $this->createTestConfig('dop_v1_key_no_remark_2');
        $configWithoutRemark2->setRemark(null);

        $this->repository->save($configWithRemark);
        $this->repository->save($configWithoutRemark1);
        $this->repository->save($configWithoutRemark2);

        $results = $this->repository->findBy(['remark' => null]);

        $this->assertCount(2, $results);
        foreach ($results as $config) {
            $this->assertInstanceOf(DigitalOceanConfig::class, $config);
            $this->assertNull($config->getRemark());
        }
    }

    // findOneBy方法测试

    public function testFindOneByWithNullRemarkShouldFindNullFields(): void
    {
        $configWithRemark = $this->createTestConfig('dop_v1_key_with_remark', '有备注的配置');
        $configWithoutRemark = $this->createTestConfig('dop_v1_key_without_remark');
        $configWithoutRemark->setRemark(null);

        $this->repository->save($configWithRemark);
        $this->repository->save($configWithoutRemark);

        $result = $this->repository->findOneBy(['remark' => null]);

        $this->assertInstanceOf(DigitalOceanConfig::class, $result);
        $this->assertNull($result->getRemark());
        $this->assertSame('dop_v1_key_without_remark', $result->getApiKey());
    }

    public function testFindOneByWithOrderByShouldRespectOrdering(): void
    {
        $config1 = $this->createTestConfig('dop_v1_same_key', '配置B');
        $config2 = $this->createTestConfig('dop_v1_same_key', '配置A');
        $this->repository->save($config1);
        $this->repository->save($config2);

        $result = $this->repository->findOneBy(['apiKey' => 'dop_v1_same_key'], ['remark' => 'ASC']);

        $this->assertInstanceOf(DigitalOceanConfig::class, $result);
        $this->assertSame('配置A', $result->getRemark());
    }

    // findAll方法测试

    // save方法测试
    public function testSaveWithNewEntityShouldPersistToDatabase(): void
    {
        $config = $this->createTestConfig('dop_v1_new_key', '新配置');

        $this->repository->save($config);

        $this->assertNotNull($config->getId());
        $foundConfig = $this->repository->find($config->getId());
        $this->assertInstanceOf(DigitalOceanConfig::class, $foundConfig);
        $this->assertSame('dop_v1_new_key', $foundConfig->getApiKey());
    }

    public function testSaveWithExistingEntityShouldUpdateDatabase(): void
    {
        $config = $this->createTestConfig('dop_v1_original_key', '原始配置');
        $this->repository->save($config);

        $config->setApiKey('dop_v1_updated_key');
        $config->setRemark('更新后的配置');
        $this->repository->save($config);

        $foundConfig = $this->repository->find($config->getId());
        $this->assertInstanceOf(DigitalOceanConfig::class, $foundConfig);
        $this->assertSame('dop_v1_updated_key', $foundConfig->getApiKey());
        $this->assertSame('更新后的配置', $foundConfig->getRemark());
    }

    public function testSaveWithFlushFalseShouldNotCommitToDatabase(): void
    {
        $config = $this->createTestConfig('dop_v1_not_flushed_key', '未提交配置');

        $this->repository->save($config, false);

        // 清除实体管理器缓存
        self::getEntityManager()->clear();

        $foundConfig = $this->repository->findOneBy(['apiKey' => 'dop_v1_not_flushed_key']);
        $this->assertNull($foundConfig);
    }

    // remove方法测试
    public function testRemoveWithExistingEntityShouldDeleteFromDatabase(): void
    {
        $config = $this->createTestConfig('dop_v1_to_delete_key', '待删除配置');
        $this->repository->save($config);
        $configId = $config->getId();

        $this->repository->remove($config);

        $foundConfig = $this->repository->find($configId);
        $this->assertNull($foundConfig);
    }

    public function testRemoveWithFlushFalseShouldNotCommitToDatabase(): void
    {
        $config = $this->createTestConfig('dop_v1_not_removed_key', '未删除配置');
        $this->repository->save($config);
        $configId = $config->getId();

        $this->repository->remove($config, false);

        // 清除实体管理器缓存
        self::getEntityManager()->clear();

        $foundConfig = $this->repository->find($configId);
        $this->assertInstanceOf(DigitalOceanConfig::class, $foundConfig);
    }

    // 数据库不可用时的健壮性测试

    // 辅助方法
    private function createTestConfig(
        string $apiKey = 'dop_v1_test_api_key',
        ?string $remark = '测试配置',
    ): DigitalOceanConfig {
        $config = new DigitalOceanConfig();
        $config->setApiKey($apiKey);
        $config->setRemark($remark);

        return $config;
    }

    protected function getRepository(): DigitalOceanConfigRepository
    {
        return $this->repository;
    }
}
